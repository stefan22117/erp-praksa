<?php
App::uses('AuthComponent', 'Controller/Component');

class User extends AppModel {
    var $name = 'User';
    public $order = array('User.created DESC', 'User.id DESC');
    public $actsAs = array('Acl' => array('type' => 'requester'));
    
    public $belongsTo = array(
        'Group' => array(
            'className' => 'Group',
            'foreignKey' => 'group_id'
        )
    );


    public $hasMany = array(
        'Feedback' => array(
            'className' => 'Feedback',
            'foreignKey' => 'user_id'
            ),
        'FeedbackComment' => array(
            'className' => 'FeedbackComment',
            'foreignKey' => 'user_id'
            ),
        'Notification' => array(
            'className' => 'Notification',
            'foreignKey' => 'user_id'
            ),
        'Comment' => array(
            'className' => 'Comment',
            'foreignKey' => 'user_id'
        ),
    );
    
    public $validate = array(
        'first_name' => array(
            'firstNameRule' => array(
                'rule' => 'notEmpty',
                'message' => 'A first name is required'
            )
        ),
        'last_name' => array(
            'lastNameRule' => array(
                'rule' => 'notEmpty',
                'message' => 'A last name is required'
            )
        ),
        'username' => array(
            'usernameRule' => array(
                'rule' => 'notEmpty',
                'message' => 'A username is required'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'Username already exists in database'
            )
        ),
        'password' => array(
            'passwordRule' => array(
                'rule' => 'notEmpty',
                'message' => 'Molimo unesite lozinku'
            ),
            'passwordRule2' => array(
                'rule' => array('minLength', 8),
                'message' => 'Lozinka mora imati minimum 8 karaktera'
                ),
            'passwordRule3' => array(
                'rule' => array('validatePassword'),
                'message' => 'Lozinka mora sadržati minimum 1 slovo i minimum 1 broj'
                )
        ),
        'email' => array(
            'emailRule' => array(
                'rule' => 'email',
                'message' => 'Email is not correct format'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'Email already exists in database'
                )
        ),
        'group_id' => array(
            'groupIdRule' => array(
                'rule' => 'notEmpty',
                'message' => 'A group must be selected'
            )
        ),
        'worker_id' => array(
            'workerIdRule' => array(
                'rule' => array('checkWorker'),
                'message' => 'Zaposleni je već povezan sa korisničkim nalogom',
                'required' => false
            )
        )        
    );

    /*
    *   Virtual fields and model aliases
    *   When you are using virtualFields and models with aliases that are not the same as their name, 
    *   you can run into problems as virtualFields do not update to reflect the bound alias. 
    *   If you are using virtualFields in models that have more than one alias it is best to define 
    *   the virtualFields in your model’s constructor
    */
    public function __construct($id = false, $table = null, $ds = null) {
        //Call parent constuctor
        parent::__construct($id, $table, $ds);    
        //Set virtual fields with aliases
        $this->virtualFields['full_name'] = sprintf(
            'CONCAT(%s.first_name, " ", %s.last_name, " (", %s.username, ")")', $this->alias, $this->alias, $this->alias
        );
        $this->virtualFields['name'] = sprintf(
            'CONCAT(%s.first_name, " ", %s.last_name)', $this->alias, $this->alias
        );
    }//~!

    /**
     * Check if valid worker is selected
    */
    public function checkWorker($check){
        if (empty($this->data['User']['worker_id'])){
            return true;
        }else{
            $user = $this->find(
                'all',
                array(
                    'recursive' => -1,
                    'conditions' => array(
                        'worker_id' => $this->data['User']['worker_id'],
                        'id !=' => $this->data['User']['id']
                    )
                )
            );

            if (!empty($user)){
                return false;
            }
            
            return $this->Worker->find('count', array('recursive' => -1, 'conditions' => array('id' => $this->data['User']['worker_id']))) == 1;
        }
    }//~!

    /**
     * Check if password contain at least one letter and one number
    */
    public function validatePassword($check){
        $password = $check['password'];
        $result = (preg_match('/[A-Za-z]/', $password) && preg_match('/[0-9]/', $password)) ? true : false;

        return $result;
    }//~!

    public function parentNode() {
        if (!$this->id && empty($this->data)) {
            return null;
        }
        if (isset($this->data['User']['group_id'])) {
            $groupId = $this->data['User']['group_id'];
        } else {
            $groupId = $this->field('group_id');
        }
        if (!$groupId) {
            return null;
        } else {
            return array('Group' => array('id' => $groupId));
        }
    }//~!

    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }
        return true;
    }//~!

    /**
     * Get list of all users full name
     * @author Boris Urosevic
     * @version 2.0
     * @param array $options [ field => string, group_id => int ]
     *                "field" can be: 'full_name' / 'name' / 'username' / 'first_name' / 'last_name' / 'email'
     * @return array
     * @throws nothing
    */
	public function getAllUsers($options = array()){
		if ( empty( $users ) ){
			// polje koje se dohvata
			$fields = array('full_name', 'name', 'username', 'first_name', 'last_name', 'email');
			if(empty($options['field']) || !in_array($options['field'], $fields)){
			    $field = 'full_name';
			}else{
			    $field = $options['field'];
			}
			
			// uslovi za pretragu
			$conditions[] = array('User.active' => 1);
			if(!empty($options['group_id'])){
			    $conditions[] = array('User.group_id' => $options['group_id']);
			}

			// upit
			$users = $this->find('list', array('fields' => 'User.'.$field, 'conditions' => $conditions));
		}

		return $users;
	}//~!

    /**
        * get user first and last name
        * @param int $id = user id
        * @return string $name
    */
    public function getName($id){
        $name = '';
        $data = $this->find('first', array(
            'recursive' => -1,
            'fields' => 'User.name',
            'conditions' => array(
                'User.id' => $id
                )
            ));
        if(!empty($data)) $name = $data['User']['name'];
        return $name;
    }//~!

    /**
    * check user id
    * @param int $id = user id
    * @return array $check
    */
    public function checkUserID($id){
        $check = array();

        if($id == null){
            $check['error'] = 'Niste izabrali zaposlenog!';
        }else{
            $existance = $this->find('count', array(
                'recursive' => -1,
                'conditions' => array(
                    'User.id' => $id
                    )
                ));
            if($existance == 0){
                $check['error'] = 'Izabrali ste nepostojećeg zaposlenog!';
            }else{
                $activity = $this->find('count', array(
                    'recursive' => -1,
                    'conditions' => array(
                        'User.id' => $id,
                        'User.active' => 1
                        )
                    ));
                if($activity == 0){
                    $check['error'] = 'Zaposleni koga ste izabrali nije aktivan!';  
                }else{
                    $user = $this->find('first', array(
                        'recursive' => -1,
                        'conditions' => array(
                            'User.id' => $id
                            )
                        ));
                    $user['User']['currency'] = '';
                    if($user['User']['currency_id'] > 0){
                        $currency = $this->Currency->find('first', array(
                            'recursive' => -1,
                            'fields' => 'Currency.iso',
                            'conditions' => array(
                                'Currency.id' => $user['User']['currency_id']
                                )
                            ));
                        if(!empty($currency)) $user['User']['currency'] = $currency['Currency']['iso'];
                    }
                    $check['user'] = $user;
                } 
            }
        }

        return $check;
    }//~!

    /**
    * Return a list of active users with id as key and first and last name as title
    * @param none
    * @return array of listed users
    */
    public function getActiveUsersList(){  
        //Get all users
        $users_data = $this->find('all', 
            array(
                'conditions' => array($this->alias.'.active' => 1, $this->alias.'.id >' => 1), 
                'fields' => array($this->alias.'.id', $this->alias.'.first_name', $this->alias.'.last_name'), 
                'order' => array($this->alias.'.first_name ASC', $this->alias.'.last_name ASC'), 
                'recursive' => -1
            )
        );

        $user_func = function($result, $user) {
            $result[$user[$this->alias]['id']] = $user[$this->alias]['first_name'].' '.$user[$this->alias]['last_name'];
            return $result;
        };

        $users = array_reduce($users_data, $user_func);        
        return $users;
    }//~!

    /**
     * Gets user_id with given email
     * @param $email (string)
     * @param $foreign_key = array() - array('group_id' => 5, 'department_id' => 9)
     * @return array
     */
    public function getUserIdFromEmail($email, $foreign_key = array()) {


        $conditions = array($this->alias.'.email LIKE' => $email);

        if(!empty($foreign_key)) {

            foreach ($foreign_key as $column => $id) {
                // Check if column exist
                if(in_array($column, array_keys($this->getColumnTypes()))) {
                    // If exist, add condition
                    $conditions[$this->alias.'.'.$column] = $id;
                }
            }
        }

        $user = $this->find('first', array(
            'conditions' => $conditions, 
            'fields' => array($this->alias.'.id'),
            'recursive' => -1
        ));

        return $user;
    }//~!

    /**
     * getPermissions
     *
     * @param int $userId
     * @return array $data
     */
    public function getPermissions($userId) {
        $data = array();
        $user = $this->find('first', array(
            'fields' => array('User.*', 'Group.*'),
            'joins' => array(
                array(
                    'table' => 'groups',
                    'alias' => 'Group',
                    'conditions' => array('User.group_id = Group.id')
                )
            ),
            'conditions' => array(
                'User.id' => $userId
            ),
            'recursive' => -1
        ));
        $data['user'] = $user;
        return $data;
    }//~!
}

?>