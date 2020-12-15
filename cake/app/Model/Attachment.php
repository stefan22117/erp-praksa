<?php
class Attachment extends AppModel {

    // Variables
    public $name = 'Attachment';

    public $allowed_file_types;
    
    public $permissionFunctions = array(
        'view' => array(
            'name' => 'checkPermissionAttachmentView',
            'message' => 'Nemate dozvolu da vidite priloge!'
        ),
        'save' => array(
            'name' => 'checkPermissionAttachmentSave',
            'message' => 'Nemate dozvolu da dodajete priloge!'
        ),
        'delete' => array(
            'name' => 'checkPermissionAttachmentDelete',
            'message' => 'Nemate dozvolu da brišete priloge!'
        ),
        'download' => array(
            'name' => 'checkPermissionAttachmentDownload',
            'message' => 'Nemate dozvolu da preuzimate priloge!'
        )
    );

    public $allowedFileTypeFunction = 'setAttachmentAllowedFileTypes';

    /**
     * List of special characters
     * @var array
    */
    public $special_characters = array('\\', '/',':','*','?','"','<','>','|');

    /**
     * Charater to replace with
     * @var string
    */
    public $replace = '-';

    // Associations
    
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        ),
        'AttachmentType' => array(
            'className' => 'AttachmentType',
            'foreignKey' => 'attachment_type_id'
        )
    );

    // Recursion

    public $recursive = -1;

    // Behaviors

    public $actsAs = array('Containable');

    // Pagination

    public $paginate = array(
        'conditions' => array(
            'Attachment.deleted' => 0
        ),
        'order' => 'Attachment.created ASC',
        'limit' => 10
    );

    // Validation

    public $validate = array(
        'user_id' => array(
            'userIdRule1' => array(
                'rule' => array('notEmpty'),
                'message' => 'Korisnik nije definisan',
                'required' => true
            ),
            'userIdRule2' => array(
                'rule' => array('exist', 'User'),
                'message' => 'Korisnik ne postoji',
                'required' => true
            )
        ),
        'attachment_type_id' => array(
            'attachmentTypeIdRule1' => array(
                'rule' => array('notEmpty'),
                'message' => 'Tip priloga nije definisan',
                'required' => true,
                'allowEmpty' => false
            ),
            'attachmentTypeIdRule2' => array(
                'rule' => array('exist', 'AttachmentType'),
                'message' => 'Tip priloga ne postoji',
                'required' => true,
                'allowEmpty' => false
            )
        ),
        'model' => array(
            'modelRule1' => array(
                'rule' => array('notEmpty'),
                'message' => 'Model nije definisan',
                'required' => true
            )
        ),
        'foreign_key' => array(
            'foreignKeyIdRule1' => array(
                'rule' => array('notEmpty'),
                'message' => 'Strani ključ nije definisan',
                'required' => true
            ),
            'foreignKeyIdRule2' => array(
                'rule' => array('existModelData'),
                'message' => 'Model ili zapis koji ima ovaj id ne postoje u bazi',
                'required' => true
            )
        ),
        'name' => array(
            'nameRule1' => array(
                'rule' => array('notEmpty'),
                'message' => 'Naziv priloga nije definisan',
                'required' => true
            ),
			'nameSpecialCharacters' => array(
				//'rule'    => '/^[\W]+$/',
				'rule'    => 'specialCharacterValidation',
				'message' => 'Ime ne sme sadržati specijalne karaktere: \ / : * ? " < > |'
            )
        ),
        'file_name' => array(
            'fileNameRule1' => array(
                'rule' => array('notEmpty'),
                'message' => 'Naziv fajla nije definisan',
                'required' => true
            )
        ),
        'link' => array(
            'linkRule1' => array(
                'rule' => array('notEmpty'),
                'message' => 'Link do priloga nije definisan',
                'required' => true
            )
        ),
        'date' => array(
            'dateRule1' => array(
                'rule' => array('date', 'ymd'),
                'message' => 'Datum nije ispravan',
                'required' => false,
                'allowEmpty' => true,
            )
        ),
    );

    public function __construct() {
        parent::__construct();

        $this->Configuration = ClassRegistry::init('Configuration');

        $this->allowed_file_types = $this->Configuration->getConfigurationData(
            'Attachment', 'allowed_file_types'
        );
    }

	public function specialCharacterValidation($check){
		$result = true;

		$length = strlen($check['name']);
		for ( $i = 0; $i < $length; $i++ ){
			if ( in_array($check['name'][$i], $this->special_characters) ){
				$result = false;
				break;
			}
		}
		return $result;
	}//~!

    /**
     * Check if model and data with foreign_key id exists in DB
     *
     * @throws nothing
     * @param array $check
     * @return boolean
     */
    public function existModelData($check) {
        $model = $this->data['Attachment']['model'];
        $foreign_key = $this->data['Attachment']['foreign_key'];
        // Initial conditions //
        $conditions = array(
            $model.'.id' => $foreign_key
        );
        if($obj = ClassRegistry::init($model)) {
            // Check if deleted column exist //
            if(in_array('deleted', array_keys($obj->getColumnTypes()))) {
                // If exist, add condition //
                $conditions['deleted'] = 0;
            }
            // Return true if exist //
            return $obj->find('count', array(
                'conditions' => $conditions,
                'recursive' => -1
            ));
        } else {
            return false;
        }
    }//~!

    /**
     * Check if name is unique for model and foreign_key
     *
     * @throws nothing
     * @param array $check
     * @return boolean
     */
    public function uniqueName($check) {
        return !$this->find('first', array(
            'conditions' => array(
                'Attachment.model' => $this->data['Attachment']['model'],
                'Attachment.foreign_key' => $this->data['Attachment']['foreign_key'],
                'Attachment.name' => $check['name'],
                'Attachment.deleted' => 0
            )
        ));
    }//~!

    // Callbacks

    /**
     * Pre-validation logic
     *
     * @throws nothing
     * @param array $options
     * @return boolean
     */
    public function beforeValidate($options = array()) {
        if(empty($this->data['Attachment']['name'])) {
            $extension = substr($this->data['Attachment']['file_name'], strrpos($this->data['Attachment']['file_name'], '.') + 1);
            $this->data['Attachment']['name'] = basename($this->data['Attachment']['file_name'], ".".$extension);
        }
        return true;
    }//~!

    // Getters

    /**
     * Get all attachments for model and foreign key
     *
     * @throws nothing
     * @param none
     * @return array
     */
    public function getAll($model, $foreignKey) {
        $data = $this->find('all', array(
            'recursive' => -1,
            'joins' => array(
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'conditions' => array('User.id = Attachment.user_id')
                ),
                array(
                    'table' => 'attachment_types',
                    'alias' => 'AttachmentType',
                    'type' => 'left',
                    'conditions' => array('AttachmentType.id = Attachment.attachment_type_id')
                )
            ),
            'fields' => 'Attachment.*, AttachmentType.type, User.first_name, User.last_name, User.username',
            'order' => array('Attachment.created' => 'DESC'),
            'conditions' => array(
                'Attachment.model' => $model,
                'Attachment.foreign_key' => $foreignKey,
                'Attachment.deleted' => 0
            )
        ));
        return $data;
    }//~!

    /**
     * Get attachment by id
     *
     * @throws nothing
     * @param int $id
     * @return array
     */
    public function get($id) {
        $data = $this->find('first', array(
            'conditions' => array(
                'Attachment.id' => $id,
                'Attachment.deleted' => 0
            )
        ));
        return $data;
    }//~!

    /**
     * Check permission
     *
     * @throws nothing
     * @param string $model
     * @param int $foreign_key
     * @param string $method
     * @return array
     */
    public function checkPermission($model, $foreign_key, $method) {
        $this->{$model} = ClassRegistry::init($model);
        $function = $this->permissionFunctions[$method]['name'];
        $message = $this->permissionFunctions[$method]['message'];
        if(method_exists($model, $function)) {
            if(!$this->{$model}->{$function}($foreign_key)) {
                exit($message);
            }
        }
        return true;
    }//~!

    /**
     * Check permissions and set buttons
     *
     * @throws nothing
     * @param string $model
     * @param int $foreign_key
     * @return array
     */
    public function setButtons($model, $foreign_key) {
        $this->{$model} = ClassRegistry::init($model);
        $buttonShow = array();
        foreach ($this->permissionFunctions as $key => $function) {
            if($key != 'view') {
                $buttonShow[$key] = true;
                if(method_exists($model, $function['name']))
                    $buttonShow[$key] = $this->{$model}->{$function['name']}($foreign_key);
            }
        }
        return $buttonShow;
    }//~!

    /**
     * Set allowed file types
     *
     * @throws nothing
     * @param string $model
     * @param int $foreign_key
     * @return boolean
     */
    public function setAllowedFileTypes($model, $foreign_key) {
        $this->{$model} = ClassRegistry::init($model);
        if (method_exists($model, $this->allowedFileTypeFunction)) {
            $this->allowed_file_types = $this->{$model}->{$this->allowedFileTypeFunction}();
        }
        return true;
    }//~!

    /**
     * Copy attachment by id
     *
     * @throws nothing
     * @param int $id
     * @return array
     */
    public function copyAttachment($id) {
        // Get attachment //
        $data = $this->get($id);
        // Get file name and file path //
        $fileName = $data['Attachment']['file_name'];
        $filePath = $data['Attachment']['link'];
        // Set new file path //
        $newPath = '';
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') { //This is a server using Windows            
            //Set path
            $newPath = APP.'private'.DS.'Attachments'.DS;
            if(!file_exists($newPath)){
                mkdir($newPath, 0777, true);
            }                
        } else { //This is a server not using Windows;
            $file_owner = get_current_user();
            $newPath = DS.'home'.DS.'repository'.DS.$file_owner.DS.'Attachments'.DS;
            if(!file_exists($newPath)){
                mkdir($newPath, 0755, true);
            }
        }
        $extension = substr($fileName, strrpos($fileName, '.') + 1);
        $newPath = $newPath.time().'.'.$extension;
        // Copy file //
        if(copy($filePath, $newPath)) {
            $data['Attachment']['link'] = $newPath;
        } else {
            throw new Exception('Fajl ne može biti kopiran!');
        }
        // Save data //
        $data['Attachment']['id'] = null;
        $this->id = null;
        if(!$this->save($data)) {
            unlink($data['Attachment']['link']);
            throw new Exception('Prilog ne može biti kopiran. Greška: '.array_shift($this->validationErrors)[0]);
        }
        $data['Attachment']['id'] = $this->id;
        return $data;
    }//~!

    /**
     * Copy attachment to a new model and foreign key
     *
     * @throws nothing
     * @param int $id
     * @param string $model
     * @param int $foreignKey
     * @return array
     */
    public function copyAttachmentModelForeignKey($id, $model, $foreignKey) {
        // Get attachment //
        $data = $this->get($id);
        // Set model and foreign key //
        $data['Attachment']['model'] = $model;
        $data['Attachment']['foreign_key'] = $foreignKey;
       // Get file name and file path //
        $fileName = $data['Attachment']['file_name'];
        $filePath = $data['Attachment']['link'];
        // Set new file path //
        $newPath = '';
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') { //This is a server using Windows            
            //Set path
            $newPath = APP.'private'.DS.'Attachments'.DS;
            if(!file_exists($newPath)){
                mkdir($newPath, 0777, true);
            }                
        } else { //This is a server not using Windows;
            $file_owner = get_current_user();
            $newPath = DS.'home'.DS.'repository'.DS.$file_owner.DS.'Attachments'.DS;
            if(!file_exists($newPath)){
                mkdir($newPath, 0755, true);
            }
        }
        $extension = substr($fileName, strrpos($fileName, '.') + 1);
        $newPath = $newPath.time().'.'.$extension;
        // Copy file //
        if(copy($filePath, $newPath)) {
            $data['Attachment']['link'] = $newPath;
        } else {
            throw new Exception('Fajl ne može biti kopiran!');
        }
        // Save data //
        $data['Attachment']['id'] = null;
        $this->id = null;
        if(!$this->save($data)) {
            unlink($data['Attachment']['link']);
            throw new Exception('Prilog ne može biti kopiran. Greška: '.array_shift($this->validationErrors)[0]);
        }
        $data['Attachment']['id'] = $this->id;
        return $data;
     }//~!

    /**
     * Update all attachments from a model and foreign key to a new model and new foreign key
     *
     * @throws nothing
     * @param string $modelFrom
     * @param int $foreignKeyFrom
     * @param string $modelTo
     * @param int $foreignKeyTo
     * @return boolean
     */
    public function transferAttachments($modelFrom, $foreignKeyFrom, $modelTo, $foreignKeyTo) {
        // Get attachments //
        $attachments = $this->getAll($modelFrom, $foreignKeyFrom);
        // Get data source, init error and start transaction //
        $dataSource = $this->getDataSource();
        $error = 0;
        $dataSource->begin();
        foreach ($attachments as $attachment) {
            $this->id = $attachment['Attachment']['id'];
            $attachment['Attachment']['model'] = $modelTo;
            $attachment['Attachment']['foreign_key'] = $foreignKeyTo;
            if(!$this->save($attachment)) $error++;
        }
        // Check error //
        if (!$error) $dataSource->commit();
        else $dataSource->rollback();
        return !$error;
    }//~!

    // Save

    /**
     * Save new or update existing systematization
     *
     * @throws Exception
     * @param array $data
     * @param int $id
     * @return int
     */
    public function saveAttachment($file, $data, $id = null) {
        if(empty($data)) {
            throw new Exception('Podaci nisu definisani');
        }
        $this->id = $id;
        if(!empty($id) && !$this->exists()) {
            throw new Exception('Prilog ne postoji');
        }
        //Check for path existance
        $path = '';
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') { //This is a server using Windows            
            //Set path
            $path = APP.'private'.DS.'Attachments'.DS;
            if(!file_exists($path)){
                mkdir($path, 0777, true);
            }                
        } else { //This is a server not using Windows;
            $file_owner = get_current_user();
            $path = DS.'home'.DS.'repository'.DS.$file_owner.DS.'Attachments'.DS;
            if(!file_exists($path)){
                mkdir($path, 0755, true);
            }
        }
        $extension = substr($file['name'], strrpos($file['name'], '.') + 1);
        $file['filename'] = $path.time().'.'.$extension;
        // Upload file //
        if(move_uploaded_file($file['tmpName'], $file['filename'])) {
            $data['Attachment']['name'] = $data['Attachment']['file_name'];
            $data['Attachment']['file_name'] = $file['name'];
            $data['Attachment']['link'] = $file['filename'];
        } else {
            throw new Exception('Fajl ne može biti uploadovan!');
        }
        if(!$this->save($data)) {
            unlink($data['Attachment']['link']);
            throw new Exception('Prilog ne može biti sačuvan. Greška: '.end($this->validationErrors)[0]);
        }
        $id = $this->id;
        return $id;
    }//~!

    // Delete

    /**
     * Delete attachment
     *
     * @throws Exception
     * @param int $id
     * @return boolean
     */
    public function remove($id) {
        if(empty($id)) {
            throw new Exception('Id nije definisan');
        }
        $this->id = $id;
        if(!$this->exists()) {
            throw new Exception('Prilog ne postoji');
        }
        $attachment = $this->get($id);
        $data = $attachment['Attachment'];
        $data['deleted'] = '1';
        unset($this->validate['name']['nameRule2']);
        if(!$this->save($data)) {
            throw new Exception('Prilog ne može biti izbrisan. Greška: '.array_shift($this->validationErrors)[0]);
        }
        return true;
    }//~!

}
