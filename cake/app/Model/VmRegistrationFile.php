<?php
App::uses('AppModel', 'Model');
/**
 * VmRegistrationFile Model
 *
 */
class VmRegistrationFile extends AppModel
{
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

	public $validate = array(
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Niste uneli naziv registracionog dokumenta'
			),
			'unique' => array(
				'rule' => array('unique', array('scope' => 'vm_registration_id')),
				'message' => 'Postoji fajl koji se ovako zove, za ovu registraciju'
			)
		),
		'path' => array(
			'noFile' => array(
				'rule' => '/^registration_files\/{1}.*\.{1}.+$/',
				'message' => 'Niste izabrali fajl'
			),
			'noPdf' => array(
				// 'rule'=>'/^vehicle_files\/{1}.+\.pdf{1}$/',
				'rule' => array('extension', array('pdf')),
				'message' => 'Mora biti pdf'
			)
		)

	);
}
