<?php
class ErpUnitDeveloper extends AppModel {

    // Variables

    var $name = 'ErpUnitDeveloper';

    var $type = array(
    	'developer' => 'Developer',
    	'maintainer' => 'Maintainer'
    );

    // Associations
    
    public $belongsTo = array(
        'ErpUnit' => array(
            'className' => 'ErpUnit',
            'foreignKey' => 'erp_unit_id'
        ),
        'ErpDeveloper' => array(
            'className' => 'ErpDeveloper',
            'foreignKey' => 'erp_developer_id'
        )
    );

    // Recursion

    public $recursive = -1;

    // Behaviors

    public $actsAs = array('Containable');

    // Validation    

    public $validate = array(
		'erp_unit_id' => array(
			'erpUnitIdRule1' => array(
				'rule' => array('notEmpty'),
				'message' => 'Modul nije definisan',
				'required' => true,
				'allowEmpty' => false
			),
			'erpUnitIdRule2' => array(
				'rule' => array('exist', 'ErpUnit'),
				'message' => 'Modul ne postoji',
				'required' => true,
				'allowEmpty' => false
			)
		),
		'erp_developer_id' => array(
			'erpDeveloperIdRule1' => array(
				'rule' => array('notEmpty'),
				'message' => 'Developer nije definisan',
				'required' => true,
				'allowEmpty' => false
			),
			'erpDeveloperIdRule2' => array(
				'rule' => array('exist', 'ErpDeveloper'),
				'message' => 'Developer ne postoji',
				'required' => true,
				'allowEmpty' => false
			)
		),
		'type' => array(
			'typeRule1' => array(
				'rule' => array('notEmpty'),
				'message' => 'Tip nije definisan',
				'required' => true,
				'allowEmpty' => false
			),
			'typeRule2' => array(
				'rule' => array('inList', array('developer','maintainer')),
				'message' => 'Tip nije ispravan',
				'required' => true,
				'allowEmpty' => false
			)
		)
    );

    // Delete

    public function deleteErpUnitDeveloper($id) {
    	if(!empty($id)) {
    		$this->id = $id;
			$this->saveField('deleted', '1');
	    	return true;
    	}
    }//~!

}