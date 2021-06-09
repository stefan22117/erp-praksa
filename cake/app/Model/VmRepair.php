<?php
App::uses('AppModel', 'Model');
/**
 * VmRepair Model
 *
 */
class VmRepair extends AppModel {
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

}