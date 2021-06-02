<?php
App::uses('AppModel', 'Model');
/**
 * HrWorker Model
 *
 */
class HrWorker extends AppModel {
    // public $belongsTo = array(
    //     'VmVehicle' => array(
	// 		'className' => 'VmVehicle',
	// 		'foreignKey' => 'vm_vehicle_id',
	// 		'dependent' => false,
	// 		'conditions' => '',
	// 		'fields' => '',
	// 		'order' => '',
	// 		'limit' => '',
	// 		'offset' => '',
	// 		'exclusive' => '',
	// 		'finderQuery' => '',
	// 		'counterQuery' => ''
	// 	)
    //     );

        public $hasMany = array(
            'VmCrossedKm' => array(
                'className' => 'VmCrossedKm',
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
            'VmRegistration' => array(
                'className' => 'VmRegistration',
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
            //jos?
    );
}