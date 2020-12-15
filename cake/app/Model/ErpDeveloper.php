<?php
class ErpDeveloper extends AppModel {

    // Variables

    var $name = 'ErpDeveloper';

    // Associations
    
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
    );

    public $hasMany = array(
        'ErpUnitDeveloper' => array(
            'className' => 'ErpUnitDeveloper',
            'foreignKey' => 'erp_developer_id'
        )
    );

    // Recursion

    public $recursive = -1;

    // Behaviors

    public $actsAs = array('Containable');

    // Validation    

    public $validate = array(
        'user_id' => array(
            'userIdRule1' => array(
                'rule' => array('notEmpty'),
                'message' => 'Korisnik nije definisan',
                'required' => true,
                'allowEmpty' => false
            ),
            'userIdRule2' => array(
                'rule' => array('exist', 'User'),
                'message' => 'Korisnik ne postoji',
                'required' => true,
                'allowEmpty' => false
            ),
            'userIdRule3' => array(
                'rule' => array('uniqueUser'),
                'message' => 'Korisnik je već definisan kao developer',
                'required' => true,
                'allowEmpty' => false
            )
        ),
        'active' => array(
            'activeRule1' => array(
                'rule' => array('notEmpty'),
                'message' => 'Nije definisano da li je developer aktivan',
                'required' => true,
                'allowEmpty' => false
            ),
            'activeRule2' => array(
                'rule' => array('boolean'),
                'message' => 'Nije ispravan podatak da li je developer aktivan',
                'required' => true,
                'allowEmpty' => false
            ),
        )
    );

    /**
     * Unique user validation
     *
     * @throws nothing
     * @param array $check
     * @return boolean
     */
    public function uniqueUser($check) {
        if(empty($this->data['ErpDeveloper']['id'])) {
            $erpDeveloper = $this->find('first', array(
                'conditions' => array(
                    'ErpDeveloper.user_id' => $check['user_id'],
                    'ErpDeveloper.deleted' => 0
                )
            ));
            if(!empty($erpDeveloper)) return false;
        }
        return true;
    }//~!

    // Save

    public function saveErpDeveloper($data, $id = null) {
        $this->id = $id;
        if(!$this->save($data)) {
            return false;
        }
        return $data;
    }//~!

    // Delete

    public function deleteErpDeveloper($id) {
        if(!empty($id)) {
            $unitDevelopers = $this->ErpUnitDeveloper->find('all', array(
                'conditions' => array(
                    'ErpUnitDeveloper.erp_developer_id' => $id,
                    'ErpUnitDeveloper.deleted' => 0
                )
            ));
            if(!empty($unitDevelopers)) return false;
            $this->id = $id;
            $this->saveField('deleted', '1');
            return true;
        }
    }//~!

}