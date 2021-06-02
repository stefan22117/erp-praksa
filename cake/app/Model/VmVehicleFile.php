<?php
App::uses('AppModel', 'Model');
/**
 * VmVehicleFile Model
 *
 */
class VmVehicleFile extends AppModel {
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
		)
        );
}