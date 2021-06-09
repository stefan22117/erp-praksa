<?php
App::uses('AppModel', 'Model');
/**
 * VmDamage Model
 *
 */
class VmDamage extends AppModel {
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

		public $hasMany = array(
			'VmRepair' => array(
				'className' => 'VmRepair',
				'foreignKey' => 'vm_damage_id',
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