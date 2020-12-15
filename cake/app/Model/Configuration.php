<?php
class Configuration extends AppModel{
    var $name = 'Configuration';

    var $types;
    var $multiple_conditions;

    public $validate;

    /**
     * Override default constructor
     */
    public function __construct()
    {
        //Load parent constructor
        parent::__construct();
        //Set enum fields
        $this->types = array(
            'int' => __("Ceo broj"),
            'float' => __("Decimalni broj"),
            'string' => __("Niz karaktera"),
            'date' => __("Datum"),
            'time' => __("Vreme"),
            'datetime' => __("Datum i vreme"),
            'foreign_key' => __("Veza sa drugim podatkom u bazi"),
            'list'  => __("Lista"),
            'multiple' => __("Više podataka iz baze"),
            'boolean' => __("Fleg"),
            'json' =>  __("JSON"),
        );
        $this->multiple_conditions = array(
            '<' => '<', 
            '>' => '>', 
            '==' => '==', 
            '<=' => '<=',
            '>=' => '>=',
            '!=' => '!='
        );
        //Set validation methods
        $this->validate = array(
            'name' => array(
                'nameRule1' => array(
                    'rule' => 'notEmpty',
                    'message' => __("Naziv konfiguracije nije definisan"),
                    'required' => true
                ),
                'nameRule2' => array(
                    'rule' => array('maxLength', 128),
                    'message' => __("Naziv konfiguracije ne sme preći %d karaktera"),
                    'required' => true
                )
            ),            
            'model' => array(
                'modelRule1' => array(
                    'rule' => 'notEmpty',
                    'message' => __("Model za konfiguraciju nije definisan"),
                    'required' => true
                ),
                'modelRule2' => array(
                    'rule' => array('maxLength', 64),
                    'message' => __("Model za konfiguraciju ne sme preći %d karaktera"),
                    'required' => true
                )
            ),
            'tag' => array(
                'tagRule1' => array(
                    'rule' => 'notEmpty',
                    'message' => __("Tag za konfiguraciju modula nije definisan"),
                    'required' => true
                ),
                'tagRule2' => array(
                    'rule' => array('tagUniqueCheck'),
                    'message' => __("Tag za konfiguraciju modula nije jedinstven"),
                    'required' => true
                ),
                'tagRule3' => array(
                    'rule' => array('maxLength', 64),
                    'message' => __("Tag za konfiguraciju ne sme preći %d karaktera"),
                    'required' => true
                )
            ),
            'value' => array(
                'valueRule1' => array(
                    'rule' => array('valueCheck'),
                    'message' => __("Vrednost konfiguracionog taga nije validna"),
                    'required' => true,
                    'allowEmpty' => true
                )
            ),
            'type' => array(
                'typeRule1' => array(
                    'rule' => 'notEmpty',
                    'message' => __("Vrsta konfiguracionog taga nije definisana"),
                    'required' => true
                ),
                'typeRule2' => array(
                    'rule' => array('typeCheck'),
                    'message' => __("Vrsta konfiguracionog taga nije validna"),
                    'required' => true
                )
            ),
            'foreign_key_model' => array(
                'foreignKeyModelRule1' => array(
                    'rule' => array('foreignKeyModelCheck'),
                    'message' => __("Model za vezu sa drugim podatkom nije validan"),
                    'required' => true
                ),
                'foreignKeyModelRule2' => array(
                    'rule' => array('maxLength', 64),
                    'message' => __("Naziv modela za vezu sa drugim podatkom ne sme preći %d karaktera"),
                    'required' => true,
                    'allowEmpty' => true
                )
            ),
            'foreign_key_id' => array(
                'foreignKeyIdRule1' => array(
                    'rule' => array('foreignKeyIdCheck'),
                    'message' => __("Spoljni ključ za vezu sa drugim podatkom nije validan"),
                    'required' => true
                )
            ),
            'multiple_model' => array(
                'multipleModelRule1' => array(
                    'rule' => array('multipleModelCheck'),
                    'message' => __("Model za vezu sa više podataka iz baze nije validan"),
                    'required' => true
                ),
                'multipleModelRule2' => array(
                    'rule' => array('maxLength', 64),
                    'message' => __("Model za vezu sa više podataka iz baze ne sme preći %d karaktera"),
                    'required' => true,
                    'allowEmpty' => true
                )
            ),
            'multiple_field' => array(
                'multipleFieldRule1' => array(
                    'rule' => array('multipleFieldCheck'),
                    'message' => __("Polje modela za vezu sa više podataka iz baze nije validno"),
                    'required' => true
                ),
                'multipleFieldRule2' => array(
                    'rule' => array('maxLength', 64),
                    'message' => __("Polje modela za vezu sa više podataka iz baze ne sme preći %d karaktera"),
                    'required' => true,
                    'allowEmpty' => true
                )
            ),
            'multiple_condition' => array(
                'multipleConditionRule1' => array(
                    'rule' => array('multipleConditionCheck'),
                    'message' => __("Uslov za vezu sa više podataka iz baze nije validan"),
                    'required' => true
                )
            ),
            'multiple_value' => array(
                'multipleValueRule1' => array(
                    'rule' => array('multipleValueCheck'),
                    'message' => __("Vrednost uslova za vezu sa više podataka iz baze nije validna"),
                    'required' => true
                ),
                'multipleValueRule2' => array(
                    'rule' => array('maxLength', 64),
                    'message' => __("Vrednost uslova za vezu sa više podataka iz baze ne sme preći %d karaktera"),
                    'required' => true,
                    'allowEmpty' => true
                )
            )
        );
    }//~!

    /**
     * Function for pre-save logic
     *
     * @throws nothing
     * @param $options = array() with option parameters
     * @return boolean
     */
    public function beforeValidate($options = array()) {
        //If foreign key are not set and codebook connection then fetch data for foreign key
        if($this->data['Configuration']['type'] == 'foreign_key'){
            //Check if foreign key model and foreign key id are set
            if(
                !isset($this->data['Configuration']['foreign_key_model']) && 
                !isset($this->data['Configuration']['foreign_key_id'])
            ){
                //Check for connection and record
                if(!empty($this->data['Configuration']['connection']) && !empty($this->data['Configuration']['record'])){
                    //Load Codebook connection model
                    $this->CodebookConnection = ClassRegistry::init('CodebookConnection');
                    //Get codebook connection
                    $codebook_connection = $this->CodebookConnection->find('first', array(
                        'conditions' => array('CodebookConnection.id' => $this->data['Configuration']['connection']),
                        'fields' => array('Codebook.model_name'),
                        'recursive' => 0
                    ));
                    //Set foreign key data
                    $this->data['Configuration']['foreign_key_model'] = $codebook_connection['Codebook']['model_name'];
                    $this->data['Configuration']['foreign_key_id'] = $this->data['Configuration']['record'];
                }
            }   
        }else{
            //Reset foreign key data
            $this->data['Configuration']['foreign_key_model'] = null;
            $this->data['Configuration']['foreign_key_id'] = null;
        }
        return true;
    }//~!

    /**
     * Check if model tag is already defined
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function tagUniqueCheck($check){
        //Init conditions
        $conditions = array(
            'Configuration.model' => $this->data['Configuration']['model'],
            'Configuration.tag' => $this->data['Configuration']['tag']
        );
        //Skip existing check
        if(!empty($this->data['Configuration']['id'])){
            $conditions['NOT'] = array('Configuration.id' => $this->data['Configuration']['id']);
        }
        //Get configuration count
        $configuration_count = $this->find('count', array('conditions' => $conditions, 'recursive' => -1));
        //Return result
        return $configuration_count == 0;
    }//~!       

    /**
     * Check if model value is valid
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function valueCheck($check){
        //Check for configuration type
        switch ($this->data['Configuration']['type']) {
            //Check integer
            case 'int':
                $check = !empty($this->data['Configuration']['value']) && is_numeric($this->data['Configuration']['value']);
                return $check ? true : __("Vrednost konfiguracionog taga mora biti ceo broj");
                break;
            //Check float
            case 'float':
                $type = gettype($this->data['Configuration']['value']);
                $check = ($type === "float") ? true : preg_match("/^\\d+\\.\\d+$/", $this->data['Configuration']['value']) === 1;
                return $check ? true : __("Vrednost konfiguracionog taga mora biti decimalni broj");
                break;                
            //Check string
            case 'string':
                return !empty($this->data['Configuration']['value']) ? true : __("Niz karaktera konfiguracionog taga nije definisan");
                break;
            //Check date
            case 'date':
                $check = !empty($this->data['Configuration']['value']);
                $check = $check && DateTime::createFromFormat('Y-m-d', $this->data['Configuration']['value']) !== FALSE;
                return $check ? true : __("Vrednost konfiguracionog taga mora biti datum");
                break;                
            //Check time
            case 'time':
                $check = !empty($this->data['Configuration']['value']);
                $check = $check && DateTime::createFromFormat('H:i:s', $this->data['Configuration']['value']) !== FALSE;
                return $check ? true : __("Vrednost konfiguracionog taga mora biti vreme");
                break;
            //Check datetime
            case 'datetime':
                $check = !empty($this->data['Configuration']['value']);
                $check = $check && DateTime::createFromFormat('Y-m-d H:i:s', $this->data['Configuration']['value']) !== FALSE;
                return $check ? true : __("Vrednost konfiguracionog taga mora biti datum i vreme");
                break;                                
            //Check foreign key
            case 'foreign_key':
                return empty($this->data['Configuration']['value']) ? true : __("Vrednost konfiguracionog taga mora biti prazna pošto je podatak vezan za bazu");
                break;
            //Check list
            case 'list':
                $list = explode(',', $this->data['Configuration']['value']);
                $check = !empty($list) && is_array($list);
                return $check ? true : __("Vrednost konfiguracionog taga mora biti lista sa elementima odvojenim zarezom");
                break;
            //Check multiple
            case 'multiple':
                return empty($this->data['Configuration']['value']) ? true : __("Vrednost konfiguracionog taga mora biti prazna pošto je podatak vezan za bazu");
                break;
            //Check boolean
            case 'boolean':
                $booleanList = ['0', '1'];
                $check = $this->data['Configuration']['value'] != '' && in_array($this->data['Configuration']['value'], $booleanList, true);
                return $check ? true : __("Vrednost konfiguracionog taga mora biti logička vrednost: 0 ili 1");
                break;                
            //Check json
            case 'json':
                $json = empty($this->data['Configuration']['value']) ? false : json_decode($this->data['Configuration']['value'], true);
                $check = $json && (json_last_error() == JSON_ERROR_NONE);
                return $check ? true : __("Vrednost konfiguracionog taga mora biti JSON string");
                break;
            default:
                return false;
                break;
        }
    }//~!

    /**
     * Check if type selection is valid
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function typeCheck($check){
        return array_key_exists($this->data['Configuration']['type'], $this->types);
    }//~!

    
    /**
     * Check if foreign key model is valid
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function foreignKeyModelCheck($check){
        //Check if type is foreign key
        if($this->data['Configuration']['type'] == 'foreign_key'){
            //Get model list
            $model_list = array_flip(App::objects('model'));
            //Check if model exists in list
            return array_key_exists($this->data['Configuration']['foreign_key_model'], $model_list);
        }else{
            return empty($this->data['Configuration']['foreign_key_model']) ? true : __("Model za vezu sa drugim podatkom ne treba biti definisan");
        }
    }//~!

    /**
     * Check if foreign key id is valid
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function foreignKeyIdCheck($check){
        //Check if type is foreign key
        if($this->data['Configuration']['type'] == 'foreign_key'){
            //Set model
            $model_name = $this->data['Configuration']['foreign_key_model'];
            $model_id = $this->data['Configuration']['foreign_key_id'];
            $Model = ClassRegistry::init($model_name);
            //Get data count
            $data_count = $Model->find('count', array(
                'conditions' => array($model_name.'.id' => $model_id), 
                'recursive' => -1
            ));
            //Return data count existance
            return $data_count > 0;
        }else{
            return empty($this->data['Configuration']['foreign_key_id']) ? true : __("Spoljni ključ za vezu sa drugim podatkom ne treba biti definisan");
        }
    }//~! 

    /**
     * Check if multiple model is valid
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function multipleModelCheck($check){
        //Check if type is foreign key
        if($this->data['Configuration']['type'] == 'multiple'){
            //Get model list
            $model_list = array_flip(App::objects('model'));
            //Check if model exists in list
            return array_key_exists($this->data['Configuration']['multiple_model'], $model_list);
        }else{
            return empty($this->data['Configuration']['multiple_model']) ? true : __("Model za vezu sa više podataka ne treba biti definisan");
        }
    }//~!

    /**
     * Check if multiple field is valid
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function multipleFieldCheck($check){
        //Check if type is foreign key
        if($this->data['Configuration']['type'] == 'multiple'){
            //Check if model exists first
            if(!$this->multipleModelCheck('multiple_model')){
                return false;
            }
            //Check if model has field
            $Model = ClassRegistry::init($this->data['Configuration']['multiple_model']);
            $schema = $Model->schema();
            return array_key_exists($this->data['Configuration']['multiple_field'], $schema);
        }else{
            return empty($this->data['Configuration']['multiple_field']) ? true : __("Polje modela za vezu sa više podataka ne treba da bude definisano");
        }
    }//~!

    /**
     * Check if multiple condition is valid
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function multipleConditionCheck($check){
        //Check if type is foreign key
        if($this->data['Configuration']['type'] == 'multiple'){
            //Check if condition exists in list
            return array_key_exists($this->data['Configuration']['multiple_condition'], $this->multiple_conditions);
        }else{
            return empty($this->data['Configuration']['multiple_condition']) ? true : __("Uslov za vezu sa više podataka ne treba da bude definisan");
        }
    }//~!

    /**
     * Check if multiple value is valid
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function multipleValueCheck($check){
        //Check if type is foreign key
        if($this->data['Configuration']['type'] == 'multiple'){
            //Check if multiple value exists
            return !empty($this->data['Configuration']['multiple_value']);
        }else{
            return empty($this->data['Configuration']['multiple_value']) ? true : __("Vrednost uslova za vezu sa više podataka ne treba da bude definisana");
        }
    }//~!

    /**
     * Get foreign data
     *
     * @throws nothing
     * @param array $configuration - Configuration.*
     * @return array foreign data
     */    
    public function getForeignData($configuration){
        //Init result
        $result = array();
        //Set model name and id
        $model_name = !empty($configuration['Configuration']['foreign_key_model']) ? $configuration['Configuration']['foreign_key_model'] : null;
        $model_id = !empty($configuration['Configuration']['foreign_key_id']) ? $configuration['Configuration']['foreign_key_id'] : null;
        //Check for model name and id
        if(!empty($model_name) && !empty($model_id)){
            //Load model
            $this->{$model_name} = ClassRegistry::init($model_name);
            //Get data
            $result = $this->{$model_name}->find('first', array(
                'conditions' => array($model_name.".id" => $model_id),
                'recursive' => -1
            ));
        }
        //Return result
        return $result;
    }//~!

    /**
     * Get multiple data
     *
     * @throws nothing
     * @param array $configuration - Configuration.*
     * @return array foreign data
     */    
    public function getMultipleData($configuration){
        //Init result
        $result = array();
        //Set multiple variables
        $multiple_model = !empty($configuration['Configuration']['multiple_model']) ? $configuration['Configuration']['multiple_model'] : null;
        $multiple_field = !empty($configuration['Configuration']['multiple_field']) ? $configuration['Configuration']['multiple_field'] : null;
        $multiple_condition = !empty($configuration['Configuration']['multiple_condition']) ? $configuration['Configuration']['multiple_condition'] : null;
        $multiple_value = !empty($configuration['Configuration']['multiple_value']) ? $configuration['Configuration']['multiple_value'] : null;        
        //Check if all multiple variables are set
        if(!empty($multiple_model) && !empty($multiple_field) && !empty($multiple_condition) && !empty($multiple_value)){
            //Load model
            $this->{$multiple_model} = ClassRegistry::init($multiple_model);
            //Init conditions
            $conditions = array();
            //Build conditions
            $field = $multiple_field;
            if($multiple_condition != '=='){
                $field .= ' '.$multiple_condition;
            }
            //Set conditions
            $conditions[$field] = $multiple_value;
            //Get data
            $result = $this->{$multiple_model}->find('all', array(
                'conditions' => $conditions,
                'recursive' => -1
            ));            
        }
        //Return result
        return $result;
    }//~! 
    
    /**
     * Get configuration data
     *
     * @throws nothing
     * @param string $model - Configuration.model
     * @param string $tag - Configuration.tag
     * @return string|array|false configuration data or false on not found
     */    
    public function getConfigurationData($model, $tag){
        //Get configuration
        $configuration = $this->find('first', array(
            'conditions' => array('Configuration.model' => $model, 'Configuration.tag' => $tag),
            'recursive' => -1
        ));
        //Check if exists
        if(empty($configuration)){
            return false;
        }
        //Init result
        $result = false;
        //Return data based on type
        switch ($configuration['Configuration']['type']) {
            case 'int':
            case 'float':
            case 'string':
            case 'date':
            case 'time':
            case 'datetime':
                //Set result
                $result = $configuration['Configuration']['value'];
                break;    
            case 'boolean':
                $result = boolval($configuration['Configuration']['value']);
                break;
            case 'list':
                //Set result
                $result = explode(',', $configuration['Configuration']['value']);
                break;    
            case 'foreign_key':
                //Set result
                $result = $this->getForeignData($configuration);
                break;
            case 'multiple':
                //Set result
                $result = $this->getMultipleData($configuration);
                break;                    
            case 'json':
                //Set result
                $result = json_decode($configuration['Configuration']['value'], true);
                break;
            default:
                break;
        }
        //Return result
        return $result;
    }//~! 

    /**
     * Get configuration connection and record if exists
     *
     * @throws nothing
     * @param array $configuration - Reference to Configuration.*
     * @return nothing
     */    
    public function setConfigurationConnectionAndRecord(&$configuration){
        //If configuration is foreign type set connection and record
        if(!empty($configuration['Configuration']['type']) && $configuration['Configuration']['type'] == 'foreign_key'){
            //Load codebook connection
            $this->CodebookConnection = ClassRegistry::init('CodebookConnection');
            //Set model name
            $model_name = $configuration['Configuration']['foreign_key_model'];
            $model_id = $configuration['Configuration']['foreign_key_id'];
            //Get codebook by model name
            $codebook_connection = $this->CodebookConnection->find('first', array(
                'conditions' => array('Codebook.model_name' => $model_name),
                'fields' => array('CodebookConnection.*'),
                'recursive' => 0
            ));
            //Set connection data
            $configuration['Configuration']['connection'] = $codebook_connection['CodebookConnection']['id'];
            $connection_title = $codebook_connection['CodebookConnection']['code'].' - '.$codebook_connection['CodebookConnection']['name'];
            $configuration['Configuration']['connection_title'] = $connection_title;
            //Set record data
            $configuration['Configuration']['record'] = $model_id;
            //Set record title
            $code = $this->CodebookConnection->getConnectionCode($codebook_connection['CodebookConnection']['id'], $model_id);
            $title = $this->CodebookConnection->getConnectionTitle($codebook_connection['CodebookConnection']['id'], $model_id);
            $configuration['Configuration']['record_title'] = $code.' - '.$title;            
        }
    }//~!

    /**
     * Update existing configurations with data
     *
     * @throws nothing
     * @param array $configuration - Reference to Configuration.*
     * @return nothing
     */    
    public function updateExistingConfigurations(&$configurations){
        //Init index
		$index = 0;
		foreach ($configurations['Configuration'] as $config) {
			//Get existing
			$existing_config = $this->find('first', array(
				'conditions' => array(
					'Configuration.model' => $config['model'],
					'Configuration.tag' => $config['tag']
				),
				'fields' => array('Configuration.*'),
				'recursive' => -1
			));
			//Check existing
			if(!empty($existing_config)){
				$configurations['Configuration'][$index] = $existing_config['Configuration'];
			}
			$index++;
		}
    }//~! 
}
?>