<?php
App::uses('AppModel', 'Model');
/**
 * VmCompany Model
 *
 */
class VmCompany extends AppModel {
    public $hasMany = array(
        'VmRegistration'=>array(
            'className' => 'VmRegistration',
			'foreignKey' => 'vm_company_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
        ),
        'VmRepair'=>array(
            'className' => 'VmRepair',
			'foreignKey' => 'vm_company_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
        ),
        'VmMaintenance'=>array(
            'className' => 'VmMaintenance',
			'foreignKey' => 'vm_company_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
        ),
        'VmExternalWorker'=>array(
            'className' => 'VmExternalWorker',
			'foreignKey' => 'vm_company_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
        )

        );
}