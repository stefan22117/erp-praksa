<?php
App::uses('AppModel', 'Model');
/**
 * VmRepair Model
 *
 */
class VmRepair extends AppModel
{
	public $belongsTo = array(
		'VmDamage' => array(
			'className' => 'VmDamage',
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
		'amount' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Niste uneli cenu popravke'
			),
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Niste pravilno uneli cenu popravke'
			)
		),
		'spent_time' => array(
			'naturalNumber' => array(
				'rule' => 'naturalNumber',
				'message' => 'Niste izabrali potroseno vreme'
			)
		),
		'description' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Niste uneli opis popravke'
			)
		),
		'vm_damage_id' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'message' => 'Niste izabrali koju štetu želite da popravite'
			)
		),
		'vm_company_id' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'message' => 'Niste izabrali firmu'
			)
		),
	);
}
