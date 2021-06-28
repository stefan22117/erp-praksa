<?php
App::uses('AppModel', 'Model');
/**
 * VmChangeLog Model
 *
 */
class VmChangeLog extends AppModel
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


	public function saveVmVehicleLog($table = null, $vm_vehicle_id = 0, $session = null, $auth = null)
	{


		if (!$table) {
			//getting all table counts
			$session->delete('VehicleLogCount');

			$tables = array(
				'VmVehicle',
				'VmRegistration',
				'VmRepair',
				'VmFuel',
				'VmDamage',
				'VmMaintenance',
				'VmVehicleFile',
				'VmImage',
				'VmRegistrationFile',
			);
			foreach ($tables as $table_str) {
				if (!$this->$table_str) {
					$this->$table_str = ClassRegistry::init($table_str);
				}
				$count = count($this->$table_str->find('all'));
				$session->write('VehicleLogCount.' . $table_str, $count);
			}
		} else {

			$prev = $session->read('VehicleLogCount.' . $table->name);
			$current = count($table->find('all'));

			$table_name =
				substr($table->name, 0, 2) == 'Vm' ?
				substr($table->name, 2) :
				$table->name;

			$description = $table_name . ' was ';

			$add_update_delete = $current - $prev;




			if ($add_update_delete > 0) //added
			{
				$description .= 'added';
			} else if ($add_update_delete == 0) { //updated
				$description .= 'updated';
			} else { //deleted
				$description .= 'deleted';
			}
			

			$vm_change_logs = array(
				'VmChangeLog' => array(
					'description' => $description,
					'user_id' => $auth->user('id') ? $auth->user('id') : 0,
					'vm_vehicle_id' => $vm_vehicle_id ? $vm_vehicle_id : 0
				)
			);


			$this->create();
			$this->save($vm_change_logs);
		}
	}
}
