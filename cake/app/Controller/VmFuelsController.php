<?php
App::uses('AppController', 'Controller');

class VmFuelsController  extends AppController
{
    public function beforeFilter()
	{
		$this->Auth->allow('index', 'add', 'view');
	}


    public function index(){
        $this->loadModel('VmCrossedKm');
        $this->loadModel('HrWorker');

        $vm_crossed_km_id = $this->VmCrossedKm->find('first',[
            'fields'=> ['MAX(VmCrossedKm.id) as id']
        ])[0]['id'];

        $this->set('podatak', $vm_crossed_km_id);
    }




    public function add($vehicle_id = null){

        $this->loadModel('VmCrossedKm');
        $this->loadModel('HrWorker');

        $hr_workers = [];
        foreach($this->HrWorker->find('all') as $hr_worker){

            $hr_workers[$hr_worker['HrWorker']['id']] = $hr_worker['HrWorker']['first_name'];
        }
        $this->set('hr_workers', $hr_workers);


        if($this->request->is('post')){


            $vm_crossed_km = ['VmCrossedKm'=>[
                'total_kilometers'=> $this->request->data['VmFuel']['total_kilometers'],
                'hr_worker_id'=> $this->request->data['VmFuel']['hr_worker_id'],
                'vm_vehicle_id'=> $vehicle_id
            ]];

            if($vm_crossed_km = $this->VmCrossedKm->save($vm_crossed_km)){

                /*$vm_crossed_km_id = $this->VmCrossedKm->find('first',[
                    'fields'=> ['MAX(VmCrossedKm.id) as id']
                ])[0]['id'];*/
        
                $vm_fuel = [
                    'VmFuel'=>[
                        'liters'=>$this->request->data['VmFuel']['liters'],
                        'amount'=>$this->request->data['VmFuel']['amount'],
                        'vm_vehicle_id'=> $vehicle_id,
                        'vm_crossed_km_id'=>$vm_crossed_km['VmCrossedKm']['id']
                        //ovde mi sacuvava 0 a spolja pise lepo id
                    ]
                    ];
                    if($this->VmFuel->save($vm_fuel)){
                        $this->Session->setFlash('You have filled the fuel', 'flash_success');
                        $this->redirect(['controller'=>'vmvehicles', 'action'=>'view', $vehicle_id]);
                    }
            }

            
            

        }


    }

}