<?php
class CodebookDocumentType extends AppModel{
	var $name = 'CodebookDocumentType'; 

    public $hasMany = array(
        'Codebook' => array(
            'className' => 'Codebook',
            'foreignKey' => 'codebook_document_type_id'
        )      
    );    

    public $validate = array(
        'code' => array(
            'codeRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Šifra vrste dokumenta nije definisana',
                'required' => true
            ),
            'codeRule2' => array(
                'rule' => 'isUnique',
                'message' => 'Šifra vrste dokumenta nije jedinstvena'
            )
        ),
        'name' => array(
            'nameRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Naziv vrste dokumenta nije definisan',
                'required' => true
            ),
            'nameRule2' => array(
                'rule' => 'isUnique',
                'message' => 'Naziv vrste dokumenta nije jedinstven'
            )
        )        
	);

    /**
     * Function for returning list of document types
     * 
     * @author  Marko Jovanovic <marko@mikroe.com>
     * @version 08.03.2016 – Initial release
     * @since   Function available since 08.03.2016
     * @throws  nothing
     * @param   string $model
     * @return  list of document types array('id' => 'title')
    */
    public function getTypeList($model = ''){
        //Init variables
        $result = array();

        //Set chart account lists
        $this->virtualFields = array(
            'title' => 'CONCAT(CodebookDocumentType.code, " - ", CodebookDocumentType.name)'
        );

        $conditions = array();
        if ( !empty( $model ) ){
            $conditions = array('CodebookDocumentModel.model' => $model);
        }
        
        $result = $this->find('list', array(
            'joins' => array(
                array(
                    'table' => 'codebook_document_model_connections',
                    'alias' => 'CodebookDocumentModelConnection',
                    'type' => 'left',
                    'conditions' => array('CodebookDocumentType.id = CodebookDocumentModelConnection.codebook_document_type_id')
                ),
                array(
                    'table' => 'codebook_document_models',
                    'alias' => 'CodebookDocumentModel',
                    'type' => 'left',
                    'conditions' => array('CodebookDocumentModel.id = CodebookDocumentModelConnection.codebook_document_model_id')
                )
            ),
            'fields' => array('CodebookDocumentType.id', 'CodebookDocumentType.title'),
            'conditions' => $conditions
        ));
        $this->virtualFields = array();

        //Return result
        return $result;
    }//~!

    /**
     * Function for returning list of document types with description
     *      
     * @throws  nothing
     * @param   none
     * @return  list of chart accounts                                  
     *          $options = array(
     *              1 => array('name' => 'PORUDZ', 'value' => 1,  'title' => 'Porudzbenica'), 
     *              2 => array('name' => 'PRIMAT', 'value' => 2,  'title' => 'Prijemnica materijala'), 
     *              3 => array('name' => 'DOMKLM', 'value' => 3,  'title' => 'Kalkulac.za prijem materijala iz zemlje')
     *          );
    */
    public function getTypeListWithDecription(){
        //Init variables
        $result = array();

        //Get accounts
        $types = $this->find('all', array('fields' => array('CodebookDocumentType.*'), 'recursive' => -1));

        //Process codebook types
        foreach ($types as $type) {
            $result[$type['CodebookDocumentType']['id']] = array(
                'name' => $type['CodebookDocumentType']['code'], 
                'value' => $type['CodebookDocumentType']['id'],  
                'title' => $type['CodebookDocumentType']['name']
            );
        }

        //Return result
        return $result;
    }//~!    

    /**
     * Get list of codebook document types for codebook connections
     *
     * @throws nothing
     * @param $params - array of parameters, $options - array with options
     * @return array with results
     */
    public function listCodebookConnectionData($params = array(), $options = array()){
        //Set title field
        $this->virtualFields = array('title' => 'CONCAT(CodebookDocumentType.code, " - ", CodebookDocumentType.name)');

        //Set conditions
        $conditions = array();
        if(!empty($options['keywords'])){
            $conditions['OR'][] = array('CodebookDocumentType.code LIKE' => '%'.$options['keywords'].'%');
            $conditions['OR'][] = array('CodebookDocumentType.name LIKE' => '%'.$options['keywords'].'%');
        }

        //Get result
        $result = $this->find('list',
            array(
                'conditions' => $conditions,
                'fields' => array(
                    'CodebookDocumentType.id', 
                    'CodebookDocumentType.title'
                ),
                'recursive' => 0,
                'limit' => 20
            )
        );

        $this->virtualFields = array();

        //Return result
        return $result;
    }//~!

    /**
     * Get CodebookDocumentType title with specific ID
     *
     * @throws nothing
     * @param $params - array of parameters
     * @return false on not found
     */
    public function getDataTitle($codebook_document_type_id){
        $codebook_document_type = $this->find('first', array('conditions' => array('CodebookDocumentType.id' => $codebook_document_type_id), 'fields' => array('CodebookDocumentType.code', 'CodebookDocumentType.name'), 'recursive' => -1));
        if(!empty($codebook_document_type)){
            return $codebook_document_type['CodebookDocumentType']['code'].' - '.$codebook_document_type['CodebookDocumentType']['name'];
        }
        return false;
    }//~!    

    /**
     * Get CodebookDocumentType.id for specific model name
     *
     * @throws nothing
     * @param $model_name - Codebook.model_name
     * @return CodebookDocumentType.id
     */
    public function getModelDocumentTypeId($model_name){
        $codebook = $this->Codebook->find('first', array(
            'conditions' => array('Codebook.model_name' => $model_name),
            'fields' => array('Codebook.codebook_document_type_id'),
            'recursive' => -1
        ));
        return (!empty($codebook['Codebook']['codebook_document_type_id'])) ? $codebook['Codebook']['codebook_document_type_id'] : false;
    }//~!


    /**
     * Get CodebookDocumentType.id by code
     *
     * @throws nothing
     * @param $code - CodebookDocumentType.code
     * @return CodebookDocumentType.id
     */
    public function getTypeIdByCode($code){
        $result = $this->find('first', array(
            'conditions' => array('CodebookDocumentType.code' => $code),
            'fields' => array('CodebookDocumentType.id'),
            'recursive' => -1
        ));
        return (!empty($result['CodebookDocumentType']['id'])) ? $result['CodebookDocumentType']['id'] : null;
    }//~!    
}
?>