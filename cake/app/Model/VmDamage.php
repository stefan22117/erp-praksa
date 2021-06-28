<?php
App::uses('AppModel', 'Model');
/**
 * VmDamage Model
 *
 */
class VmDamage extends AppModel
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
	public $validate = array(
		'responsible' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Niste upisali ko je odgovoran za štetu'
			)
		),
		'description' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Niste upisali opis'
			)
		),
		'date' => array(
			'date' => array(
				'rule' => array('date'),
				'message' => 'Niste izabrali datum'
			)
		),
		'vm_vehicle_id' => array(
			'naturalNumber' => array(
				'rule' => array('naturalNumber'),
				'message' => 'Niste izabrali vozilo'
			)
		)
	);


	private $vm_repairs;
	public function beforeDelete($cascade = true)
	{
		$this->VmRepair = ClassRegistry::init('VmRepair');
		$this->vm_repairs = $this->VmRepair->find(
			'list',
			array(
				'fields' => array('VmRepair.vm_crossed_km_id'),
				'conditions' => array('VmRepair.vm_damage_id' => $this->id)
			)
		);
		
	}
	public function afterDelete()
	{

		$this->VmRepair = ClassRegistry::init('VmRepair');
		$this->VmCrossedKm = ClassRegistry::init('VmCrossedKm');

		$conditions = array(
			'VmRepair.vm_damage_id' => $this->id,
		);

		$this->VmRepair->deleteAll(
			$conditions
		);

		$conditions = array(
			'VmCrossedKm.id' => $this->vm_repairs,
		);

		$this->VmCrossedKm->deleteAll(
			$conditions
		);
	}
}
