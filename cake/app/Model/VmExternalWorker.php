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
class VmExternalWorker extends AppModel {
    
    public $hasAndBelongsToMany = array(
        'VmVehicle' => array(
        'className' => 'VmVehicle',
        'joinTable'=> 'vm_external_worker_vehicles'
        )
        );
        
}