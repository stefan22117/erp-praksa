<?php
App::uses('AppModel', 'Model');
/**
 * VmCrossedKm Model
 *
 */
class VmCrossedKm extends AppModel
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
    );

    public $hasMany = array(
        'VmFuel' => array(
            'className' => 'VmFuel',
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
        ),
        'VmRepair' => array(
            'className' => 'VmRepair',
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
        ),
        'VmMaintenance' => array(
            'className' => 'VmMaintenance',
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
        ),


    );

    public $validate = array(
        'total_kilometers' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Niste uneli trenutnu kilometražu'
            ),
            'numeric' => array(
                'rule' => array('naturalNumber'),
                'message' => 'Niste pravilno uneli trenutnu kilometražu'
            ),
            'total_kilometers_custom' =>  array(
                'rule' => array('total_kilometers_custom')
            ),

        ),
        'report_datetime' => array(
            'date' => array(
                'rule' => array('date'),
                'message' => 'Niste izabrali datum'
            )
        ),
        'vm_vehicle_id' => array(
            'numeric' => array(
                'rule' => 'numeric',
                'message' => 'Niste izabrali vozilo'
            )
        ),
        'hr_worker_id' => array(
            'numeric' => array(
                'rule' => 'numeric',
                'message' => 'Niste izabrali radnika'
            )
        ),

    );
    public function total_kilometers_custom()
    {

        $vm_vehicle_id = $this->data['VmCrossedKm']['vm_vehicle_id'];
        $vm_crossed_km = 0;
        if ($this->findByVmVehicleId($vm_vehicle_id)) {
            $vm_crossed_km = $this->find(
                'first',
                array(
                    'conditions' => array(
                        'VmCrossedKm.vm_vehicle_id' => $vm_vehicle_id
                    ),
                    'fields' => array('MAX(total_kilometers) as max_total_kilometers')
                )
            );
            $vm_crossed_km = $vm_crossed_km[0]['max_total_kilometers'];
        }

        if ($vm_crossed_km  <= $this->data['VmCrossedKm']['total_kilometers']) {
            return true;
        } else {
            return 'Kilometraža za ovo vozilo mora biti ' . $vm_crossed_km . ' ili veća';
        }
    }
}
