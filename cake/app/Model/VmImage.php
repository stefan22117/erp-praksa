<?php
App::uses('AppModel', 'Model');
/**
 * VmVehicleFile Model
 *
 */
class VmImage extends AppModel
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
		)
	);

	public $validate = array(
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Niste uneli naziv slike'
			),
			'unique' => array(
				'rule' => array('unique', array('scope' => 'vm_vehicle_id')),
				'message' => 'Postoji slika koja se ovako zove, za ovo vozilo'
			)
		),
		'path' => array(
			'noFile' => array(
				'rule' => '/^vehicle_images\/{1}.*\.{1}.+$/',
				'message' => 'Niste izabrali sliku'
			),
			'noPdf' => array(
				'rule' => array('extension'),
				'message' => 'Mora biti png, jpg ili jpeg slika'
			)
		),
		'vm_vehicle_id' => array(
			'naturalNumber' => array(
				'rule' => array('naturalNumber'),
				'message' => 'Niste izabrali vozilo za koje dodajete sliku'
			)
		)

	);
}
