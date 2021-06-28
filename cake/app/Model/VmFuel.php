<?php
App::uses('AppModel', 'Model');
/**
 * VmFuel Model
 *
 */
class VmFuel extends AppModel
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
		'VmCrossedKm' => array(
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

	public $validate = array(
		'liters' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Niste uneli litre'
			),
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Niste pravilno uneli litre'
			)
		),
		'amount' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Niste uneli ukupnu cenu goriva'
			),
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Niste pravilno uneli ukupnu cenu goriva'
			)
		),
		'vm_vehicle_id' => array(
			'naturalNumber' => array(
				'rule' => array('naturalNumber'),
				'message' => 'Niste izabrali vozilo'
			)
		),
		'vm_crossed_km_id' => array(
			'naturalNumber' => array(
				'rule' => array('naturalNumber'),
				'message' => 'Niste predjene kilometre'
			)
		),

	);


	public $vm_fuel;
	public function beforeDelete($cascade = true)
	{
		$this->vm_fuel = $this->findById($this->id);
	}
	public function afterDelete()
	{
		$this->VmCrossedKm = ClassRegistry::init('VmCrossedKm');

		$conditions = array('VmCrossedKm.id'=> $this->vm_fuel['VmFuel']['vm_crossed_km_id']);

		$this->VmCrossedKm->deleteAll(
			$conditions
		);
	}
}
