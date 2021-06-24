<?php
App::uses('AppController', 'Controller');

class VmFuelsController  extends AppController
{

    public $components = array('Paginator');

    public $uses = array(
        'VmFuel',
        'VmCrossedKm',
        'HrWorker',
        'VmVehicle'
    );
    public function beforeFilter()
    {
        $this->Auth->allow('index', 'save', 'view');
        if (
			strtolower($this->request['action']) == 'view' ||
			strtolower($this->request['action']) == 'save'
		) {
			$vm_fuel_id = !empty($this->request['pass']) ? $this->request['pass'][0] : 0;

			if ($vm_fuel_id) {
				$vm_fuel = $this->VmFuel->findById($vm_fuel_id);
				if (empty($vm_fuel)) {
					$this->Session->setFlash(__('Traženo gorivo nije pronađeno'), 'flash_error');
					return $this->redirect(array('controller' => 'vmFuels', 'action' => 'index'));
				}
			}
			else if(strtolower($this->request['action']) != 'save')
			{
				$this->Session->setFlash(__('Niste prosledili id goriva'), 'flash_error');
				return $this->redirect(array('controller' => 'vmFuels', 'action' => 'index'));
			}

		}
    }




    public function index()
    {
        $this->set('title_for_layout', __('Goriva - MikroERP'));

        $vm_vehicles = $this->VmVehicle->find(
            'list',
            array('fields' =>
            array('VmVehicle.id', 'VmVehicle.brand_and_model'))
        );
        $this->set('vm_vehicles', $vm_vehicles);

        $hr_workers = $this->HrWorker->find(
            'list',
            array('fields' =>
            array('HrWorker.id', 'HrWorker.first_name'))
        );
        $this->set('hr_workers', $hr_workers);


        $conditions = array();
        $joins = array();

        if (isset($this->request->query['vm_vehicle_id'])  && $this->request->query['vm_vehicle_id'] != '') {
			$vm_vehicle_id = $this->request->query['vm_vehicle_id'];

			$conditions[] = array(
                'VmCrossedKm.vm_vehicle_id =' => $vm_vehicle_id
			);

			$this->request->data['vm_vehicle_id'] = $vm_vehicle_id;
		}


        if (isset($this->request->query['hr_worker_id'])  && $this->request->query['hr_worker_id'] != '') {
			$hr_worker_id = $this->request->query['hr_worker_id'];

			$conditions[] = array(
                'VmCrossedKm.hr_worker_id =' => $hr_worker_id
			);


			$this->request->data['hr_worker_id'] = $hr_worker_id;
		}







        $options = array(
            'joins'=>$joins,
            'conditions'=> $conditions,
            'recursive' => 2,
            'limit'=>5
        );



        $this->Paginator->settings = $options;
        $this->set('vm_fuels', $this->Paginator->paginate());
       

    }

    public function save($vm_fuel_id = null)
    {
        if ($vm_fuel_id == null) {
            $this->set('action', 'add');
            $success = __('Uspešno ste dodali gorivo');
            $this->VmFuel->create();
            $vm_vehicles = $this->VmVehicle->find(
                'list',
                array(
                    'fields' => array('VmVehicle.id', 'VmVehicle.brand_and_model')
                )
            );
            $this->set('vm_vehicles', $vm_vehicles);
        } else {
            $this->set('action', 'edit');
            $success = __('Uspešno ste izmenili gorivo');
            $this->VmFuel->id = $vm_fuel_id;
            $vm_fuel = $this->VmFuel->findById($vm_fuel_id);
            $this->VmCrossedKm->id = $vm_fuel['VmFuel']['vm_crossed_km_id'];
            $this->set('vm_fuel', $vm_fuel);
            if($this->request->is('get')){
                $this->request->data = $vm_fuel;
            }
        }
        $hr_workers = $this->HrWorker->find(
            'list',
            array(
                'fields' => array('HrWorker.id', 'HrWorker.first_name')
            )
        );
        $this->set('hr_workers', $hr_workers);



        if($this->request->is('post') || $this->request->is('put'))
        {
            $this->request->data['VmCrossedKm']['vm_vehicle_id'] =
            $this->request->data['VmFuel']['vm_vehicle_id'];
            $this->request->data['VmFuel']['id'] = $this->VmFuel->id;
            $this->request->data['VmCrossedKm']['id'] = $this->VmCrossedKm->id;



            if($this->VmFuel->saveAll($this->request->data)){
                $this->Session->setFlash($success, 'flash_success');
                if (
                    strpos(strtolower($this->referer()), 'vmvehicles') !== false ||
                    strpos(strtolower($this->referer()), 'vm_vehicles') !== false
                ) {
                    return $this->redirect($this->referer() . '#tab_vm_fuels');
                } else {
                    return $this->redirect(array('controller' => 'vmFuels', 'action' => 'index'));
                }
            }
            else{
                $this->Session->setFlash('Došlo je do greške', 'flash_error');
                $this->Session->write('errors.VmFuel', $this->VmFuel->validationErrors);
                $this->Session->write('errors.VmFuelVmCrossedKm', $this->VmCrossedKm->validationErrors);
                $this->Session->write(
                    'errors.data',
                    $this->request->data
                );
                if (
                    strpos(strtolower($this->referer()), 'vmvehicles') !== false ||
                    strpos(strtolower($this->referer()), 'vm_vehicles') !== false
                ) {
                    return $this->redirect($this->referer() . '#tab_vm_fuels');
                } 
            }
        }
    }



    public function add_stari($vehicle_id = null)
    {


        // $hr_workers = [];
        // foreach($this->HrWorker->find('all') as $hr_worker){

        //     $hr_workers[$hr_worker['HrWorker']['id']] = $hr_worker['HrWorker']['first_name'];
        // }
        // $this->set('hr_workers', $hr_workers);


        $this->set(
            'hr_workers',
            $this->HrWorker->find('list'),
            array('fields' => array('HrWorker.id', 'HrWorker.first_name'))
        );


        if ($this->request->is('post')) {

            // print('<pre>'. print_r($this->data) .'</pre>');
            // die();

            //ne prikazuje errore ni na posbnoj add stranici


            $vm_crossed_km = array('VmCrossedKm' => array(
                'total_kilometers' => $this->request->data['VmFuel']['total_kilometers'],
                'hr_worker_id' => $this->request->data['VmFuel']['hr_worker_id'],
                'vm_vehicle_id' => $vehicle_id
            ));

            if ($this->request->data['VmFuel']['total_kilometers'] == '') {
                $total_error = 'Prazni kilometri';
                $this->set('total_error', $total_error);
                return;
            }

            $vm_crossed_km = $this->VmCrossedKm->save($vm_crossed_km);

            $vm_fuel = array(
                'VmFuel' => array(
                    'liters' => $this->request->data['VmFuel']['liters'],
                    'amount' => $this->request->data['VmFuel']['amount'],
                    'vm_vehicle_id' => $vehicle_id,
                    'vm_crossed_km_id' => $vm_crossed_km['VmCrossedKm']['id']
                )
            );
            if ($this->VmFuel->save($vm_fuel)) {
                $this->Session->setFlash('You have filled the fuel', 'flash_success');
                $this->redirect(array('controller' => 'vmvehicles', 'action' => 'view', $vehicle_id));
            }

            // $this->redirect(array('controller' => 'vmvehicles', 'action' => 'view', $vehicle_id, '#'=>'tab3'));
            // $this->redirect($this->referer());
        }
    }
















    public function add($vm_vehicle_id = null)
    {

        $this->set(
            'hr_workers',
            $this->HrWorker->find('list'),
            array('fields' => array('HrWorker.id', 'HrWorker.first_name'))
        );


        if ($this->request->is('post')) {


            $this->request->data['VmFuel']['vm_vehicle_id'] =
                $this->request->data['VmCrossedKm']['vm_vehicle_id'] =
                $vm_vehicle_id;

            if ($this->VmFuel->saveAll($this->request->data)) {
                $this->Session->setFlash('Uspešno ste pribeležili sipano gorivo', 'flash_success');
                return $this->redirect(array(
                    'controller' => 'vmVehicles',
                    'action' => 'view',
                    $vm_vehicle_id,
                    '#' => 'tabr3'
                ));
            } else {
                $this->Session->setFlash('Došlo je do greške', 'flash_error');


                $this->Session->write(
                    'errors.VmFuel',
                    $this->VmFuel->validationErrors
                );
                $this->Session->write(
                    'errors.VmCrossedKm',
                    $this->VmCrossedKm->validationErrors
                );
                $this->Session->write(
                    'errors.data',
                    $this->request->data
                );

                $this->redirect($this->referer() . '#tabr3');
            }
        }
    }
}
