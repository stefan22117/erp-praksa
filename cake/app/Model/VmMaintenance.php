<?php
App::uses('AppModel', 'Model');
/**
 * VmMaintenance Model
 *
 */
class VmMaintenance extends AppModel
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
		),
		'VmCompany' => array(
			'className' => 'VmCompany',
			'foreignKey' => 'vm_company_id',
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
		'amount' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Niste uneli ukupnu cenu održavanja'
			),
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Niste pravilno uneli ukupnu cenu održavanja'
			)
		),
		'spent_time' => array(
			'naturalNumber' => array(
				'rule' => 'naturalNumber',
				'message' => 'Niste izabrali potrošeno vreme'
			)
		),
		'description' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Niste uneli opis održavanja'
			)
		),
		'vm_vehicle_id' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'message' => 'Niste izabrali vozilo'
			)
		),
		'vm_company_id' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'message' => 'Niste izabrali firmu'
			)
		),




	);



	public $vm_maintenance;
	public function beforeDelete($cascade = true)
	{
		$this->vm_maintenance = $this->findById($this->id);
	}
	public function afterDelete()
	{
		$this->VmCrossedKm = ClassRegistry::init('VmCrossedKm');

		$conditions = array('VmCrossedKm.id'=> $this->vm_maintenance['VmMaintenance']['vm_crossed_km_id']);

		$this->VmCrossedKm->deleteAll(
			$conditions
		);
	}
}
