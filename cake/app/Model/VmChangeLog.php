<?php
App::uses('AppModel', 'Model');
/**
 * VmChangeLog Model
 *
 */
class VmChangeLog extends AppModel {
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
        'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
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