<?php
App::uses('AppModel', 'Model');
/**
 * VmCompany Model
 *
 */
class VmCompany extends AppModel
{
	public $hasMany = array(
		'VmRegistration' => array(
			'className' => 'VmRegistration',
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
		'VmRepair' => array(
			'className' => 'VmRepair',
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
		'VmMaintenance' => array(
			'className' => 'VmMaintenance',
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
		'VmExternalWorker' => array(
			'className' => 'VmExternalWorker',
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
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Niste uneli naziv firme'
			)
		),
		'address' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Niste uneli adresu firme'
			)
		),
		'city' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Niste uneli grad u kome se firma nalazi'
			)
		),
		'email' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Niste uneli e-mail firme'
			),
			'email' => array(
				'rule' => array('email'),
				'message' => 'Nepravilno ste uneli e-mail firme'
			),
			'unique' => array(
				'rule' => array('unique'),
				'message' => 'Postoji firma sa ovim e-mailom'
			)
		),
		'zip_code' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Niste uneli poštanski broj'
			),
			'naturalNumber' => array(
				'rule' => array('naturalNumber'),
				'message' => 'Nepravilno ste uneli poštanski broj'
			)
		)
	);



	private $vm_company;
	public function beforeDelete($cascade = true)
	{
		$this->vm_company = $this->findById($this->id);
	}
	public function afterDelete()
	{

		$this->VmRegistration = ClassRegistry::init('VmRegistration');
		$vm_registrations = $this->VmRegistration->findAllByVmCompanyId($this->id);


		$this->VmRegistrationFile = ClassRegistry::init('VmRegistrationFile');
		foreach ($vm_registrations as $vm_registration) {


			$conditions = array('VmRegistrationFile.vm_registration_id' => $vm_registration['VmRegistration']['id']);

			$this->VmRegistrationFile->deleteAll(
				$conditions
			);
		}


		$conditions = array('VmRegistration.vm_company_id' => $this->vm_company['VmCompany']['id']);

		$this->VmRegistration->deleteAll(
			$conditions
		);
	}
}
