<?php
class CodebookConnection extends AppModel{
    var $name = 'CodebookConnection';

    public $belongsTo = array(
        'Codebook' => array(
            'className' => 'Codebook',
            'foreignKey' => 'codebook_id'
        )      
    );

    public $hasMany = array(
        'CodebookConnectionData' => array(
            'className' => 'CodebookConnectionData',
            'foreignKey' => 'codebook_connection_id'
        ),
        'FmChartAccount' => array(
            'className' => 'FmChartAccount',
            'foreignKey' => 'codebook_connection_id'
        )      
    );

    public $validate = array(
        'codebook_id' => array(
            'codeBookIdRule1' => array(
                'rule' => array('codebookValidation'),
                'message' => 'Šifarnik nije validan!',
                'required' => true
            )
        ),
        'name' => array(
            'nameRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Molimo unesite naziv veze dokumenta',
                'required' => true
            ),
            'nameRule2' => array(
                'rule' => 'isUnique',
                'message' => 'Naziv veze dokumenta mora biti jedinstven'
            )
        ),
        'code' => array(
            'codeRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Molimo unesite šifru veze dokumenta',
                'required' => true
            ),
            'codeRule2' => array(
                'rule' => 'isUnique',
                'message' => 'Šifra veze dokumenta mora biti jedinstvena'
            )
        ),
        'list_method' => array(
            'listMethodRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Molimo unesite funkciju modela za listanje dokumenata'
            ),
            'listMethodRule2' => array(
                'rule' => array('listMethodValidation'),
                'message' => 'Funkcija modela za listanje dokumenata nije validna!',
                'required' => true
            )
        ),
        'list_json_query' => array(
            'listJsonQueryRule1' => array(
                'rule' => array('listJsonQueryNotExist'),
                'message' => 'Parametri akcije za listanje dokumenata već postoje!'
            )/*,
            'listJsonQueryRule2' => array(
                'rule' => array('listJsonQueryValidation'),
                'message' => 'Parametri akcije za listanje dokumenata nisu validni!'
            )*/
        )
    );

    /**
     * Before saving set json query hash string
     *
     * @throws nothing
     * @param $options - settings array
     * @return boolean
     */
    public function beforeValidate($options = array()) {
        $query_string = preg_replace('/\s+/', '', $this->data['CodebookConnection']['list_json_query']);
        $this->data['CodebookConnection']['json_query_hash'] = hash('md5', $query_string);
        return true;
    }//~!

    /**
     * Check if codebook is valid in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function codebookValidation($check){
        $exists = $this->Codebook->find('count', array('conditions' => array('Codebook.id' => $this->data['CodebookConnection']['codebook_id'])));
        return ($exists > 0) ? true : false;
    }//~!

    /**
     * Check if model method listed in connections is valid
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function listMethodValidation($check){
        $codebook = $this->Codebook->find('first', array(
                'conditions' => array('Codebook.id' => $this->data['CodebookConnection']['codebook_id']),
                'fields' => array('Codebook.model_name'),
                'recursive' => -1
            )
        );

        if(!empty($codebook)){
            //Check if model function exists
            $Model = ClassRegistry::init($codebook['Codebook']['model_name']);
            return method_exists($Model, $this->data['CodebookConnection']['list_method']);
        }

        return false;
    }//~!

    /**
     * Check if method parameters are listed in connections
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function listJsonQueryNotExist($check){
        if(!empty($this->data['CodebookConnection']['list_json_query'])){
            $connection = $this->find('first',
                array(
                    'conditions' => array(
                        'CodebookConnection.codebook_id' => $this->data['CodebookConnection']['codebook_id'], 
                        'CodebookConnection.json_query_hash' => $this->data['CodebookConnection']['json_query_hash']
                    ),
                    'fields' => array('CodebookConnection.id'),
                    'recursive' => -1
                )
            );
            if(!empty($connection)){
                if(!empty($this->data['CodebookConnection']['id'])){
                    if($connection['CodebookConnection']['id'] != $this->data['CodebookConnection']['id']){
                        return false;
                    }else{
                        return true;
                    }
                }else{
                    return false;
                }
            }
        }
        return true;
    }//~!

    /**
     * Check if method parameters are valid for given codebook
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function listJsonQueryValidation($check){
        if(!empty($this->data['CodebookConnection']['list_json_query'])){
            //Get codebook model name
            $codebook = $this->Codebook->find('first', array(
                    'conditions' => array('Codebook.id' => $this->data['CodebookConnection']['codebook_id']),
                    'fields' => array('Codebook.model_name'),
                    'recursive' => -1
                )
            );

            if(!empty($codebook)){
                //Load model and get table columns
                $Model = ClassRegistry::init($codebook['Codebook']['model_name']);
                $table_schema = $Model->schema();
                $columns = array_keys($table_schema);

                //Check fields
                $query_list = json_decode($this->data['CodebookConnection']['list_json_query'], true);                
                foreach ($query_list as $field => $value) {
                    if(!in_array($field, $columns))
                        return false;
                }
                //Every field exists
                return true;
            }

            return false;
        }

        return true;
    }//~!    

    /**
     * List connection data
     *
     * @throws nothing
     * @param $connection_id - CodebookConnection.id, $options - array('keywords', 'account_year')
     * @return array with results
     */
    public function listConnectionData($connection_id, $options = array()){
        $result = array();

        //Get connection
        $connection = $this->find('first', array(
                'conditions' => array('CodebookConnection.id' => $connection_id),
                'fields' => array('Codebook.model_name', 'CodebookConnection.list_method', 'CodebookConnection.list_json_query'),
                'recursive' => 0
            )
        );

        if(!empty($connection)){
            //Load model and get data
            $Model = ClassRegistry::init($connection['Codebook']['model_name']);
            $params = array();
            if(!empty($connection['CodebookConnection']['list_json_query'])){
                $params = json_decode($connection['CodebookConnection']['list_json_query'], true);
            }
            $result = $Model->$connection['CodebookConnection']['list_method']($params, $options);
        }

        return $result;
    }//~!

    /**
     * Get list of connections
     *
     * @throws nothing
     * @param none
     * @return array (connection list)
     */
    public function getConnectionList(){
        $result = array();

        $result = $this->find('list');

        return $result;
    }//~!

    /**
     * Get connection code
     *
     * @throws nothing
     * @param $codebook_connection_id - CodebookConnection.id, $data_id - CodebookConnectionData.id
     * @return string containing code
     */
    public function getConnectionCode($codebook_connection_id, $data_id){
        //Get connection
        $connection = $this->find('first', array(
                'conditions' => array('CodebookConnection.id' => $codebook_connection_id),
                'fields' => array('Codebook.model_name', 'Codebook.code_field'),
                'recursive' => 0
            )
        );

        if(!empty($connection)){
            //Load model and get code
            $model_name = $connection['Codebook']['model_name'];
            $code_field = $connection['Codebook']['code_field'];

            $Model = ClassRegistry::init($model_name);
            if($Model){
                $model_data = $Model->find('first', array('conditions' => array($model_name.'.id' => $data_id), 'fields' => array($model_name.'.'.$code_field), 'recursive' => -1));
                if(!empty($model_data)){
                    return $model_data[$model_name][$code_field];
                }
            }            
        }        

        return false;
    }//~!    

    /**
     * Get connection title
     *
     * @throws nothing
     * @param $codebook_connection_id - CodebookConnection.id, $data_id - CodebookConnectionData.id
     * @return string containing code
     */
    public function getConnectionTitle($codebook_connection_id, $data_id){
        //Get connection
        $connection = $this->find('first', array(
                'conditions' => array('CodebookConnection.id' => $codebook_connection_id),
                'fields' => array('Codebook.model_name'),
                'recursive' => 0
            )
        );

        //Check for connection
        if(!empty($connection)){
            //Check if model function exists
            $Model = ClassRegistry::init($connection['Codebook']['model_name']);
            if(method_exists($Model, 'getDataTitle')){
                return $Model->getDataTitle($data_id);
            }
        }

        return false;
    }//~!    

    /**
     * Check if connection exists
     *
     * @throws nothing
     * @param $codebook_connection_id - CodebookConnection.id, $data_id - CodebookConnectionData.id
     * @return boolean
     */
    public function checkConnectionExists($codebook_connection_id, $data_id){
        //Get connection
        $connection = $this->find('first', array(
                'conditions' => array('CodebookConnection.id' => $codebook_connection_id),
                'fields' => array('Codebook.model_name'),
                'recursive' => 0
            )
        );

        //Check for connection
        if(!empty($connection)){
            //Check if model function exists
            $Model = ClassRegistry::init($connection['Codebook']['model_name']);
            $model_count = $Model->find('count', array('conditions' => array($connection['Codebook']['model_name'].'.id' => $data_id), 'recursive' => -1));
            return !empty($model_count);
        }

        return false;
    }//~!
}
?>