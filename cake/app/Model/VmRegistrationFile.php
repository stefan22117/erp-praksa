<?php
App::uses('AppModel', 'Model');
/**
 * VmRegistrationFile Model
 *
 */
class VmRegistrationFile extends AppModel {
    public $belongsTo = array(
        'VmRegistration' => array(
			'className' => 'VmRegistration',
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