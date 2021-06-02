<?php
App::uses('AppModel', 'Model');
/**
 * VmCrossedKm Model
 *
 */
class VmCrossedKm extends AppModel {
    public $belongsTo = array(
        'VmVehicle' => array(
			'className' => 'VmVehicle',
			'foreignKey' => 'vm_vehicle_id',
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
        'HrWorker' => array(
			'className' => 'HrWorker',
			'foreignKey' => 'hr_worker_id',
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
        );

        public $hasMany = array(
            'VmFuel' => array(
                'className' => 'VmFuel',
                'foreignKey' => 'vm_crossed_km_id',
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
            'VmRepair' => array(
                'className' => 'VmRepair',
                'foreignKey' => 'vm_crossed_km_id',
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
            'VmMaintenance' => array(
                'className' => 'VmMaintenance',
                'foreignKey' => 'vm_crossed_km_id',
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
    
    
    );
}