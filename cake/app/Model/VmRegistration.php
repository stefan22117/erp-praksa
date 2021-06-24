<?php
App::uses('AppModel', 'Model');
/**
 * VmRegistration Model
 *
 */
class VmRegistration extends AppModel
{

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
}
