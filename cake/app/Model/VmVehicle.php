<?php
App::uses('AppModel', 'Model');
/**
 * VmVehicle Model
 *
 * @property VmChangeLog $VmChangeLog
 * @property VmCrossedKm $VmCrossedKm
 * @property VmDamage $VmDamage
 * @property VmExternalWorkerVehicle $VmExternalWorkerVehicle
 * @property VmExternalWorker $VmExternalWorkerVehicle
 * @property VmFuel $VmFuel
 * @property VmImage $VmImage
 * @property VmInternalWorkerVehicle $VmInternalWorkerVehicle
 * @property VmMaintenance $VmMaintenance
 * @property VmVehicleFile $VmVehicleFile
 * @property VmVehicleInternalWorker $VmVehicleInternalWorker
 */
class VmVehicle extends AppModel
{

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array(
		'brand_and_model' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Niste uneli marku i model',
			),
		),
		'reg_number' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Niste uneli registarski broj',
			),
			'invalid_reg_number' => array(
				'rule' =>  '/^([A-Z]|[a-z]){2}-\d{3,5}-([A-Z]|[a-z]){2}$/',
				'message' => 'Registarski broj mora biti u formatu: AA-123(4)(5)-ZZ'
			),
			'unique' => array(
				'rule' => 'unique',
				'message' => 'Već postoji vozilo sa ovim registarskim brojem'
			)
		),
		'in_use' => array(),
		'active_from' => array(
			'date' => array(
				'rule' => array('date'),
				'message' => 'Izaberite od kad je vozilo aktivno',
			),
		),
		'active_to' => array(


			'date' =>
			array(
				'rule' => 'date',
				'message' => 'Niste pravilno uneli datum',
				'allowEmpty' => true,

			),
			'from_to' => array(
				'rule' => 'from_to_custom',
				'message' => 'Datum do ne moze biti veci od datuma od',
				'allowEmpty' => true
			)
		),
		'horse_power' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Niste uneli konjske snage'
			),
			'naturalNumber' => array(
				'rule' => array('naturalNumber'),
				'message' => 'Niste pravilno uneli konjske snage'
			),
		),
		'engine_capacity_cm3' => array(
			'naturalNumber' => array(
				'rule' => array('naturalNumber'),
				'message' => 'Niste uneli kubikažu'
			),
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'year_of_production' => array(
			'naturalNumber' => array(
				'rule' => array('naturalNumber'),
				'message' => 'Niste uneli godinu proizvodnje'
			),

		),
		'color' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Niste uneli boju'
			),
		),
		'number_of_seats' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Niste uneli broj sedišta'
			),
			'naturalNumber' => array(
				'rule' => array('naturalNumber'),
				'message' => 'Niste pravilno uneli broj sedišta'
			),
		),
		'chassis_number' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Niste uneli broj šasije',
			),
			'invalid_chassis_number' => array(
				'rule' => '/^.{17}$/',
				'message' => 'Broj šasije mora imati 17 cifara i brojeva'
			)
		),
		'engine_number' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Niste uneli broj motora',
			),
			'invalid_engine_number' => array(
				'rule' => '/^.{10}$/',
				'message' => 'Broj motora mora imati 10 cifara i brojeva'
			)
		),
		'date_of_purchase' => array(
			'date' => array(
				'rule' => array('date'),
				'message' => 'Izaberite datum kupovine vozila'
			),
		),
		'price' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Niste uneli cenu'
			),
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Niste pravilno uneli cenu'
			),
		),
		'hr_worker_id' => array(
			'noSelected' => array(
				'rule' => 'multi_custom',
				'message' => 'Izaberite radnika koji koristi ovo vozilo ili...'
			)
		),
		'vm_external_worker_id' => array(
			'noSelected' => array(
				'rule' => 'multi_custom',
				'message' => '...eksternog radnika'
			)
		),
	);

	public function multi_custom($a, $b)
	{
		if (empty($this->data['VmInternalWorkerVehicle']['hr_worker_id']) && empty($this->data['VmExternalWorkerVehicle']['vm_external_worker_id'])) {
			return false;
		}
		return true;
	}


	public function from_to_custom($data)
	{
		$actife_from = $this->data['VmVehicle']['active_from'];
		$active_to = $this->data['VmVehicle']['active_to'];

		if ($actife_from > $active_to) {
			return false;
		}
		return true;
	}

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	/**
	 * hasMany associations
	 *
	 * @var array
	 */
	public $hasAndBelongsToMany = array(
		'VmExternalWorker' => array(
			'className' => 'VmExternalWorker',
			'foreignKey' => 'vm_vehicle_id',
			'joinTable' => 'vm_external_worker_vehicles',
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
	public $hasMany = array(
		'VmChangeLog' => array(
			'className' => 'VmChangeLog',
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
		'VmDamage' => array(
			'className' => 'VmDamage',
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
		'VmFuel' => array(
			'className' => 'VmFuel',
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
		'VmImage' => array(
			'className' => 'VmImage',
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
		'VmInternalWorkerVehicle' => array(
			'className' => 'VmInternalWorkerVehicle',
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
		'VmMaintenance' => array(
			'className' => 'VmMaintenance',
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
		'VmVehicleFile' => array(
			'className' => 'VmVehicleFile',
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
		'VmVehicleInternalWorker' => array(
			'className' => 'VmVehicleInternalWorker',
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

		//
		'VmRegistration' => array(
			'className' => 'VmRegistration',
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
	);

	private $vm_vehicle;
	public function beforeDelete($cascade = true)
	{
		$this->vm_vehicle = $this->findById($this->id);
	}
	public function afterDelete()
	{
		//registrations
		$this->VmRegistration = ClassRegistry::init('VmRegistration');
		$vm_registrations = $this->VmRegistration->findAllByVmVehicleId($this->id);


		$this->VmRegistrationFile = ClassRegistry::init('VmRegistrationFile');
		foreach ($vm_registrations as $vm_registration) {
			$conditions = array('VmRegistrationFile.vm_registration_id' => $vm_registration['VmRegistration']['id']);

			foreach($this->VmRegistrationFile->findAllByVmRegistrationId($vm_registration['VmRegistration']['id']) as $vm_registration_file){
				if (file_exists(WWW_ROOT . 'img' . DS . $vm_registration_file['VmRegistrationFile']['path'])) {
					unlink(WWW_ROOT . 'img' . DS . $vm_registration_file['VmRegistrationFile']['path']);
				}
			}
			$this->VmRegistrationFile->deleteAll(
				$conditions
			);
		}

		//damages
		$this->VmDamage = ClassRegistry::init('VmDamage');
		$vm_damages = $this->VmDamage->findAllByVmVehicleId($this->id);


		$this->VmRepair = ClassRegistry::init('VmRepair');
		foreach ($vm_damages as $vm_damage) {
			$conditions = array('VmRepair.vm_damage_id' => $vm_damage['VmDamage']['id']);

			$this->VmRepair->deleteAll(
				$conditions
			);
		}

		$conditions = array('VmRegistration.vm_vehicle_id' => $this->vm_vehicle['VmVehicle']['id']);

		$this->VmRegistration->deleteAll(
			$conditions
		);
		$conditions = array('VmDamage.vm_vehicle_id' => $this->vm_vehicle['VmVehicle']['id']);

		$this->VmDamage->deleteAll(
			$conditions
		);





		$this->VmFuel = ClassRegistry::init('VmFuel');
		$conditions = array('VmFuel.vm_vehicle_id' => $this->vm_vehicle['VmVehicle']['id']);

		$this->VmFuel->deleteAll(
			$conditions
		);

		$this->VmCrossedKm = ClassRegistry::init('VmCrossedKm');
		$conditions = array('VmCrossedKm.vm_vehicle_id' => $this->vm_vehicle['VmVehicle']['id']);

		$this->VmCrossedKm->deleteAll(
			$conditions
		);

		$this->VmMaintenance = ClassRegistry::init('VmMaintenance');
		$conditions = array('VmMaintenance.vm_vehicle_id' => $this->vm_vehicle['VmVehicle']['id']);

		$this->VmMaintenance->deleteAll(
			$conditions
		);


		$this->VmVehicleFile = ClassRegistry::init('VmVehicleFile');
		$conditions = array('VmVehicleFile.vm_vehicle_id' => $this->vm_vehicle['VmVehicle']['id']);

		foreach($this->VmVehicleFile->findAllByVmVehicleId($this->vm_vehicle['VmVehicle']['id']) as $vm_vehicle_file){
			if (file_exists(WWW_ROOT . 'img' . DS . $vm_vehicle_file['VmVehicleFile']['path'])) {
                unlink(WWW_ROOT . 'img' . DS . $vm_vehicle_file['VmVehicleFile']['path']);
            }
		}
		$this->VmVehicleFile->deleteAll(
			$conditions
		);

		$this->VmImage = ClassRegistry::init('VmImage');
		$conditions = array('VmImage.vm_vehicle_id' => $this->vm_vehicle['VmVehicle']['id']);

		foreach($this->VmImage->findAllByVmVehicleId($this->vm_vehicle['VmVehicle']['id']) as $vm_image){
			if (file_exists(WWW_ROOT . 'img' . DS . $vm_image['VmImage']['path'])) {
                unlink(WWW_ROOT . 'img' . DS . $vm_image['VmImage']['path']);
            }
		}
		$this->VmImage->deleteAll(
			$conditions
		);

		$this->VmExternalWorkerVehicle = ClassRegistry::init('VmExternalWorkerVehicle');
		$conditions = array('VmExternalWorkerVehicle.vm_vehicle_id' => $this->vm_vehicle['VmVehicle']['id']);

		$this->VmExternalWorkerVehicle->deleteAll(
			$conditions
		);

		$this->VmInternalWorkerVehicle = ClassRegistry::init('VmInternalWorkerVehicle');
		$conditions = array('VmInternalWorkerVehicle.vm_vehicle_id' => $this->vm_vehicle['VmVehicle']['id']);

		$this->VmInternalWorkerVehicle->deleteAll(
			$conditions
		);

	}
}
