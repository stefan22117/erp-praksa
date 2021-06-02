<?php
App::uses('AppModel', 'Model');
/**
 * VmFuel Model
 *
 */
class VmFuel extends AppModel {
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
        'VmCrossedKm'=>array(
            'className' => 'VmCrossedKm',
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
        )

        );
}