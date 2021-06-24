<?php
App::uses('AppController', 'Controller');

class VmDamagesController  extends AppController
{

    public $components = array(
        'Paginator'
    );

    public $uses = array(
        'VmDamage',
        'VmCrossedKm',
        'HrWorker',
        'VmCompany',
        'VmRepair',
        'VmVehicle'
    );

    public function beforeFilter()
    {
        $this->Auth->allow('index', 'add', 'view', 'edit', 'repair', 'save');

        if (
			strtolower($this->request['action']) == 'view' ||
			strtolower($this->request['action']) == 'save'
		) {
			$vm_damage_id = !empty($this->request['pass']) ? $this->request['pass'][0] : 0;

			if ($vm_damage_id) {
				$vm_damage = $this->VmDamage->findById($vm_damage_id);
				if (empty($vm_damage)) {
					$this->Session->setFlash(__('Tražena šteta nije pronađena'), 'flash_error');
					return $this->redirect(array('controller' => 'vmDamages', 'action' => 'index'));
				}
			}
			else if(strtolower($this->request['action']) != 'save')
			{
				$this->Session->setFlash(__('Niste prosledili id štete'), 'flash_error');
				return $this->redirect(array('controller' => 'vmDamages', 'action' => 'index'));
			}

		}
    }


    public function index()
    {
        $this->set('title_for_layout', __('Štete - MikroERP'));


        $vm_vehicles = $this->VmVehicle->find(
            'list',
            array(
                'fields' => array('VmVehicle.id', 'VmVehicle.brand_and_model')
            )
        );
        $this->set('vm_vehicles', $vm_vehicles);

        $options = array();
        $conditions = array();

        if (isset($this->request->query['keywords']) && $this->request->query['keywords'] != '') {
            $keywords = $this->request->query['keywords'];

            $conditions['OR']['VmDamage.responsible LIKE'] = '%' . $keywords . '%';
            $conditions['OR']['VmDamage.description LIKE'] = '%' . $keywords . '%';
            $this->request->data['VmDamage']['keywords'] = $keywords;
        }

        if (isset($this->request->query['repaired'])) {
            $repaired = $this->request->query['repaired'];
            $conditions['OR']['VmDamage.repaired ='] = true;

            $this->request->data['VmDamage']['repaired'] = $repaired;
        }


        if (isset($this->request->query['vm_vehicle_id'])  && $this->request->query['vm_vehicle_id'] != '') {
            $vm_vehicle_id = $this->request->query['vm_vehicle_id'];

            $conditions['OR']['VmDamage.vm_vehicle_id ='] = $vm_vehicle_id;


            $this->request->data['VmDamage']['vm_vehicle_id'] = $vm_vehicle_id;
        }










        $options = array(
            'conditions' => $conditions,
            'joins' => array(),
            'order' => 'VmDamage.created DESC',
            'recursive' => 2,
            'limit' => 5

        );

        $this->Paginator->settings = $options;
        $this->set('vm_damages', $this->Paginator->paginate());
    }

    public function view($vm_damage_id = null)
    {
        $errors = array();
        //getting errors start
        if ($this->Session->read('errors')) {
            $this->request->data = $this->Session->read('errors.data');
        }



        if ($this->Session->read('errors.VmRepair')) {
            $errors['Repairs'] = true;
            $this->VmRepair->validationErrors = $this->Session->read('errors.VmRepair');
            $this->VmCrossedKm->validationErrors = $this->Session->read('errors.VmCrossedKm');
        }



        $this->set('errors', $errors);
        $this->Session->delete('errors');






        $vm_damage = $this->VmDamage->find(
            'first',
            array(
                'conditions' => array('VmDamage.id' => $vm_damage_id),
                'recursive' => 2
            )
        );
        $this->set('vm_damage', $vm_damage);



        $this->set('hr_workers', $this->HrWorker->find(
            'list',
            array(
                'fields' => array('HrWorker.id', 'HrWorker.first_name')
            )
        ));


        $this->set('vm_companies', $this->VmCompany->find(
            'list',
            array(
                'fields' => array('VmCompany.id', 'VmCompany.name')
            )
        ));

        if ($this->request->is('post')) {

            $spent_time =
                $this->request->data['VmRepair']['spent_time']['day'] * 86400 +
                $this->request->data['VmRepair']['spent_time']['hour'] * 3600 +
                $this->request->data['VmRepair']['spent_time']['min'] * 60;


            $report_date = $this->request->data['VmRepair']['report_datetime']['date'];
            $report_time = $this->request->data['VmRepair']['report_datetime']['time'];


            $report_datetime =
                date(
                    $report_date . ' ' . $report_time
                );





            $vm_crossed_km = ['VmCrossedKm' => [
                'total_kilometers' => $this->request->data['VmRepair']['total_kilometers'],
                'report_datetime' => $report_datetime,
                'hr_worker_id' => $this->request->data['VmRepair']['hr_worker_id'],
                'vm_vehicle_id' =>  $vm_damage['VmVehicle']['id']
            ]];




            var_dump($vm_crossed_km);
            die();
            if ($vm_crossed_km = $this->VmCrossedKm->save($vm_crossed_km)) {


                $vm_repair = [
                    'VmRepair' => [
                        'amount' => $this->request->data['VmRepair']['amount'],
                        'spent_time' => $spent_time,
                        'description' => $this->request->data['VmRepair']['description'],
                        'vm_damage_id' => $vm_damage['VmDamage']['id'],
                        'vm_company_id' => $this->request->data['VmRepair']['vm_company_id'],
                        'vm_crossed_km_id' => $vm_crossed_km['VmCrossedKm']['id']

                    ]
                ];
                $vm_damage['VmDamage']['repaired'] = $this->request->data['VmDamage']['repaired'];
                if ($this->VmRepair->save($vm_repair) && $this->VmDamage->save($vm_damage)) {
                    $this->Session->setFlash('Uspesno ste popravili vozilo', 'flash_success');
                    $this->redirect(['controller' => 'vmvehicles', 'action' => 'view', $vm_damage['VmVehicle']['id']]);
                }
            }
        }
    }



    public function save($vm_damage_id = null)
    {
        if ($vm_damage_id == null) {
            $this->set('action', 'add');
            $success = __('Uspešno ste dodali štetu');
            $this->VmDamage->create();
            $vm_vehicles = $this->VmVehicle->find(
                'list',
                array(
                    'fields' => array('VmVehicle.id', 'VmVehicle.brand_and_model')
                )
            );
            $this->set('vm_vehicles', $vm_vehicles);
        } else {
            $this->set('action', 'edit');
            $success = __('Uspešno ste izmenili štetu');
            $this->VmDamage->id = $vm_damage_id;
            $vm_damage = $this->VmDamage->findById($vm_damage_id);
            $this->set('vm_damage', $vm_damage);

            if($this->request->is('get')){

                $this->request->data = $vm_damage;
            }
        }


        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->VmDamage->save($this->request->data)) {
                $this->Session->setFlash($success, 'flash_success');
                
                if (
                    strpos(strtolower($this->referer()), 'vmvehicles') !== false ||
                    strpos(strtolower($this->referer()), 'vm_vehicles') !== false
                ) {
                    return $this->redirect($this->referer() . '#tab_vm_damages');
                } else {
                    return $this->redirect(array('controller' => 'vmDamages', 'action' => 'index'));
                }
            } else {
                $this->Session->setFlash('Došlo je do greške', 'flash_error');
                $this->Session->write('errors.VmDamage', $this->VmDamage->validationErrors);
                $this->Session->write(
                    'errors.data',
                    $this->request->data
                );
                if (
                    strpos(strtolower($this->referer()), 'vmvehicles') !== false ||
                    strpos(strtolower($this->referer()), 'vm_vehicles') !== false
                ) {
                    return $this->redirect($this->referer() . '#tab_vm_damages');
                }
            }
            
        }
    }




    public function add($vm_vehicle_id = null)
    {

        if ($this->request->is('post')) {
            // $this->request->data['VmDamage']['date'] = $this->data['VmDamage']['date']['year'] . '-' .
            //     $this->data['VmDamage']['date']['month'] . '-' . $this->data['VmDamage']['date']['day'];


            $vm_damage = ['VmDamage' => [
                'responsible' => $this->request->data['VmDamage']['responsible'],
                'description' => $this->request->data['VmDamage']['description'],
                'date' => $this->request->data['VmDamage']['date'],
                'vm_vehicle_id' => $vm_vehicle_id
            ]];

            if ($this->VmDamage->save($vm_damage)) {
                $this->Session->setFlash('Uspesno ste ubelezili stetu', 'flash_success');
            } else {
                $this->Session->setFlash('Doslo je do greske pri dodavanju stete', 'flash_error');

                $this->Session->write(
                    'errors.VmDamage',
                    $this->VmDamage->validationErrors
                );
                $this->Session->write(
                    'errors.VmCrossedKm',
                    $this->VmCrossedKm->validationErrors
                );
                $this->Session->write(
                    'errors.data',
                    $this->request->data
                );
            }
            $this->redirect($this->referer() . '#tabr4');
            // $this->redirect(['controller'=>'vmvehicles', 'action'=>'view', $vm_vehicle_id]);
        }
    }

    public function delete($vm_damage_id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        if (!$vm_damage_id) {
            $this->Session->setFlash('Niste prosledili Id', 'flash_error');
        }
        $vm_damage = $this->VmDamage->findById($vm_damage_id);
        if (!$vm_damage) {
            $this->Session->setFlash('Nema štete sa izabranim ID-jem', 'flash_error');
        }
        if ($this->VmDamage->delete($vm_damage_id)) {
            $this->Session->setFlash('Nema štete sa izabranim ID-jem', 'flash_error');
        }

        $this->Session->setFlash('Nema štete sa izabranim ID-jem', 'flash_error');
    }

    public function repair($vm_damage_id = null)
    {
        if (!$vm_damage_id) {
            $this->Session->setFlash('Niste prosledili Id', 'flash_error');
            return $this->redirect($this->referer());
        }
        $vm_damage = $this->VmDamage->findById($vm_damage_id);
        if (!$vm_damage) {
            $this->Session->setFlash('Šteta sa izabranim Id-jem nije pronađena', 'flash_error');
            return $this->redirect($this->referer());
        }

        $vm_damage['VmDamage']['repaired'] = !$vm_damage['VmDamage']['repaired'];

        if ($this->VmDamage->save($vm_damage)) {
            $success = $vm_damage['VmDamage']['repaired'] ?
                'Vozilo je popravljeno' : 'Vozilo nije popravljeno';
            $this->Session->setFlash($success, 'flash_success');
        } else {
            $this->Session->setFlash('Došlo je do greške sa bazom podataka ', 'flash_error');
        }
        $this->redirect($this->referer());
    }
}
