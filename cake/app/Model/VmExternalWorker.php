<?php
App::uses('AppModel', 'Model');
/**
 * VmExternalWorker Model
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
class VmExternalWorker extends AppModel
{

    public $hasAndBelongsToMany = array(
        'VmVehicle' => array(
            'className' => 'VmVehicle',
            'joinTable' => 'vm_external_worker_vehicles'
        )
    );

    public $belongsTo = array(
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


    public $validate = array(
        'first_name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Niste uneli ime eksternog radnika'
            )
        ),
        'last_name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Niste uneli prezime eksternog radnika'
            )
        ),
        'date_of_birth' => array(
            'date' => array(
                'rule' => array('date'),
                'message' => 'Nepravilno ste uneli datum rodjenja'
            )
        ),
        'phone_number'=>array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Niste uneli broj telefona eksternog radnika'
            ),
            'phone'=>array(
                'rule'=> '/^(\+\d{1,3}|0)\d{2}\/?\d{3}\-?\d{4}$/',
                'message'=>'Nepravilno ste uneli broj telefona eksternog radnika'
            ),
            'unique'=> array(
                'rule' => array('unique'),
                'message' => 'Postoji eksterni radnik sa ovim brojem telefona'
            )
            ),
            'email' => array(
                'notempty' => array(
                    'rule' => array('notempty'),
                    'message' => 'Niste uneli e-mail eksternog radnika'
                ),
                'email' => array(
                    'rule' => array('email'),
                    'message' => 'Nepravilno ste uneli e-mail eksternog radnika'
                ),
                'unique'=> array(
                    'rule' => array('unique'),
                    'message' => 'Postoji eksterni radnik sa ovim e-mailom'
                )
            ),
            'vm_company_id'=>array(
                'naturalNumber'=>array(
                    'rule'=>array('naturalNumber'),
                    'message'=>'Niste izabrali firmu eksternog radnika'
                )
            )
    );
}
