<?php
App::uses('AppModel', 'Model');
/**
 * VmInternalWorkerVehicle Model
 *
 */
class VmInternalWorkerVehicle extends AppModel
{
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
		)
	);

}