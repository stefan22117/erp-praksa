<?php
App::uses('AppModel', 'Model');
/**
 * VmVehicleFile Model
 *
 */
class VmVehicleFile extends AppModel {
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
					'message' => 'Niste uneli naziv dokumenta'
				),
				'unique'=>array(
					'rule' => array('unique', array('scope'=>'vm_vehicle_id')),
					'message' => 'Postoji fajl koji se ovako zove, za ovo vozilo'
					)
				),
				'path'=>array(
					'noFile'=>array(
						'rule'=>'/^vehicle_files\/{1}.*\.{1}.+$/',
						'message' => 'Niste izabrali fajl'
					),
					'noPdf'=>array(
						// 'rule'=>'/^vehicle_files\/{1}.+\.pdf{1}$/',
						'rule'=>array('extension', array('pdf')),
						'message' => 'Mora biti pdf'
					)
				)
	
		);
}