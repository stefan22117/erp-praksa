<?php
App::uses('AppModel', 'Model');
/**
 * VmRegistration Model
 *
 */
class VmRegistration extends AppModel
{
	public $virtualFields = array('registration'=>'CONCAT(VmRegistration.registration_date, " - ", VmRegistration.expiration_date)');

	public $validate = array(
		'registration_date' => array(
			'date' => array(
				'rule' => 'date',
				'message' => 'Niste uneli datum registracije'
			)
		),

		'spent_time' => array(
			'naturalNumber' => array(
				'rule' => 'naturalNumber',
				'message' => 'Niste izabrali potrošeno vreme'
			)
		),


		'hr_worker_id' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'message' => 'Niste izabrali radnika koji je registrovao vozilo'
			)
		),

		'expiration_date' => array(
			'date' => array(
				'rule' => 'date',
				'message' => 'Niste uneli datum pravilno isteka registracije'
			)
		),
		'amount' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'message' => 'Niste upisali cenu registracije vozila'
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
				'message' => 'Niste izabrali firmu kod koje se vozilo registrovalo'
			)
		),

	);


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
		),
	);

	public $hasMany = array(
		'VmRegistrationFile' => array(
			'className' => 'VmRegistrationFile',
			'foreignKey' => 'vm_registration_id',
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

	private $vm_registration;
	public function beforeDelete($cascade = true)
	{
		$this->vm_registration = $this->findById($this->id);
	}
	public function afterDelete()
	{
		$this->VmRegistrationFile = ClassRegistry::init('VmRegistrationFile');

		$conditions = array('VmRegistrationFile.vm_registration_id'=> $this->vm_registration['VmRegistration']['id']);

		foreach($this->VmRegistrationFile->findAllByVmRegistrationId($this->vm_registration['VmRegistration']['id']) as $vm_registration_file){
			if (file_exists(WWW_ROOT . 'img' . DS . $vm_registration_file['VmRegistrationFile']['path'])) {
				unlink(WWW_ROOT . 'img' . DS . $vm_registration_file['VmRegistrationFile']['path']);
			}
		}

		$this->VmRegistrationFile->deleteAll(
			$conditions
		);
	}
}
