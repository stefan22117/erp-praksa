<?php
App::uses('AppController', 'Controller');

class VmRepairsController extends AppController
{

    public $components = array('Paginator');

    public $uses = array(
        'VmRepair',
        'VmCrossedKm',
        'VmDamage',
        'HrWorker',
        'VmCompany',
        'VmVehicle',
        'VmChangeLog',
    );



    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('index', 'view', 'save', 'delete');

        if (
            strtolower($this->request['action']) == 'view' ||
            strtolower($this->request['action']) == 'save' ||
            strtolower($this->request['action']) == 'delete'
        ) {
            $vm_repair_id = !empty($this->request['pass']) ? $this->request['pass'][0] : 0;

            if ($vm_repair_id) {
                $vm_repair = $this->VmRepair->findById($vm_repair_id);
                if (empty($vm_repair)) {
                    $this->Session->setFlash(__('Tražena popravka nije pronađena'), 'flash_error');
                    return $this->redirect(array('controller' => 'vmRepairs', 'action' => 'index'));
                }
            } else if (strtolower($this->request['action']) != 'save') {
                $this->Session->setFlash(__('Niste prosledili id popravke'), 'flash_error');
                return $this->redirect(array('controller' => 'vmRepairs', 'action' => 'index'));
            }
        }
    }


    public function index()
    {
        $this->set('title_for_layout', __('Popravke - MikroERP'));

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

        $vm_companies = $this->VmCompany->find(
            'list',
            array('fields' =>
            array('VmCompany.id', 'VmCompany.name'))
        );
        $this->set('vm_companies', $vm_companies);

        //conditions
        $conditions = array();
        $joins = array();

        if (isset($this->request->query['keywords']) && $this->request->query['keywords'] != '') {
            $keywords = $this->request->query['keywords'];

            $conditions['OR']['VmRepair.description LIKE'] = '%' . $keywords . '%';
            $conditions['OR']['VmDamage.description LIKE'] = '%' . $keywords . '%';

            $this->request->data['keywords'] = $keywords;
        }

        if (isset($this->request->query['vm_vehicle_id'])  && $this->request->query['vm_vehicle_id'] != '') {
            $vm_vehicle_id = $this->request->query['vm_company_id'];

            $conditions[] = array(
                'VmDamage.vm_vehicle_id =' => $vm_vehicle_id
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

        if (isset($this->request->query['vm_company_id'])  && $this->request->query['vm_company_id'] != '') {
            $vm_company_id = $this->request->query['vm_company_id'];

            $conditions[] = array(
                'VmRepair.vm_company_id =' => $vm_company_id
            );

            $this->request->data['vm_company_id'] = $vm_company_id;
        }








        //conditions



        $options = array(
            'joins' => $joins,
            'conditions' => $conditions,
            'recursive' => 2,
            'limit' => 5
        );

        $this->Paginator->settings = $options;
        $this->set('vm_repairs', $this->Paginator->paginate());
    }


    public function view($vm_repair_id = null)
    {

        $vm_repair = $this->VmRepair->find(
            'first',
            array(
                'conditions' => array(
                    'VmRepair.id' => $vm_repair_id
                ),
                'recursive' => 2
            )
        );
        $this->set('vm_repair', $vm_repair);

        $spent_time = $vm_repair['VmRepair']['spent_time'];
        $m = floor(($spent_time % 3600) / 60);
        $h = floor(($spent_time % 86400) / 3600);
        $d = floor($spent_time / 86400);

        $spent_time = '';
        $d ? $spent_time = ($d == 1 ?  __(' dan i ') : $d . __(' dana i ')) : null;
        $spent_time .= $h . ':' . $m;


        $this->set('spent_time', $spent_time);

        return;
        $vm_repairs = $this->VmMaintenance->findAllById($vm_repair_id);

        $vm_damage = $this->VmDamage->findById($vm_repairs[0]['VmRepair']['vm_damage_id']);

        $vm_vehicle = $this->Vmvehicle->findById($vm_damage['VmDamage']['vm_vehicle_id']);




        $this->set('vm_repairs', $vm_repairs);
        $this->set('vm_vehicle', $vm_vehicle);
    }


    public function save($vm_repair_id = null)
    {
        if ($vm_repair_id == null) {
            $this->set('action', 'add');
            $success = __('Uspešno ste dodali popravku');
            $this->VmRepair->create();
            $vm_vehicles = $this->VmVehicle->find(
                'list',
                array(
                    'fields' => array('VmVehicle.id', 'VmVehicle.brand_and_model')
                )
            );
            $this->set('vm_vehicles', $vm_vehicles);
            $vm_damages = $this->VmDamage->find(
                'list',
                array(
                    'fields' => array('VmDamage.id', 'VmDamage.description')
                )
            );
            $this->set('vm_damages', $vm_damages);
            $vm_repair = $vm_crossed_km = $vm_damage = null;
        } else {
            $this->set('action', 'edit');
            $success = __('Uspešno ste izmenili popravku');
            $this->VmRepair->id = $vm_repair_id;

            $vm_repair = $this->VmRepair->findById($vm_repair_id);

            $this->VmCrossedKm->id = $vm_repair['VmRepair']['vm_crossed_km_id'];


            $this->set('vm_repair', $vm_repair);

            $m = floor(($vm_repair['VmRepair']['spent_time'] % 3600) / 60);
            $h = floor(($vm_repair['VmRepair']['spent_time'] % 86400) / 3600);
            $d = floor($vm_repair['VmRepair']['spent_time'] / 86400);




            $vm_repair['VmRepair']['spent_time'] = array(
                'day' => $d,
                'hour' => $h,
                'min' => $m,
            );


            if ($this->request->is('get')) {
                $this->request->data = $vm_repair;
            }
        }
        $hr_workers = $this->HrWorker->find(
            'list',
            array(
                'fields' => array('HrWorker.id', 'HrWorker.first_name')
            )
        );
        $this->set('hr_workers', $hr_workers);

        $vm_companies = $this->VmCompany->find(
            'list',
            array(
                'fields' => array('VmCompany.id', 'VmCompany.name')
            )
        );
        $this->set('vm_companies', $vm_companies);


        if ($this->request->is('ajax')) {

            $vm_vehicle_id = $this->request->data['vm_vehicle_id'];
            $conditions = array();
            if ($vm_vehicle_id != null) {
                $conditions = array('VmDamage.vm_vehicle_id' => $vm_vehicle_id);
            }



            $vm_damages = $this->VmDamage->find(
                'list',
                array(
                    'conditions' => $conditions,
                    'fields' => array('VmDamage.id', 'VmDamage.description')
                )
            );
            $this->set('vm_damages', $vm_damages);
            $this->set('_serialize', 'vm_damages');
        } else if ($this->request->is('post') || $this->request->is('put')) {
            $vm_repair = $this->request->data;

            $vm_damage = $this->VmDamage->findById($vm_repair['VmRepair']['vm_damage_id']);
            // $vm_crossed_km['VmCrossedKm']['total_kilometers'] =

            $vm_repair['VmDamage'] = $vm_damage['VmDamage'];

            $vm_repair['VmCrossedKm']['id'] = $this->VmCrossedKm->id;

            $vm_repair['VmRepair']['spent_time'] =
                $vm_repair['VmRepair']['spent_time']['day'] * 86400 +
                $vm_repair['VmRepair']['spent_time']['hour'] * 3600 +
                $vm_repair['VmRepair']['spent_time']['min'] * 60;

            $vm_repair['VmCrossedKm']['vm_vehicle_id'] = $vm_repair['VmDamage']['vm_vehicle_id'];



            $vm_repair['VmRepair']['id'] = $this->VmRepair->id;
            $vm_repair['VmCrossedKm']['id'] = $this->VmCrossedKm->id;

            if ($this->VmRepair->saveAll($vm_repair)) {
                $this->VmChangeLog->saveVmVehicleLog($this->VmRepair, $vm_repair['VmDamage']['vm_vehicle_id'], $this->Session, $this->Auth);

                $this->Session->setFlash($success, 'flash_success');
                if (
                    strpos(strtolower($this->referer()), 'vmvehicles') !== false ||
                    strpos(strtolower($this->referer()), 'vm_vehicles') !== false ||
                    strpos(strtolower($this->referer()), 'vmcompanies') !== false ||
                    strpos(strtolower($this->referer()), 'vm_companies') !== false
                ) {
                    return $this->redirect($this->referer() . '#tab_vm_repairs');
                } else if (
                    strpos(strtolower($this->referer()), 'vmdamages') !== false ||
                    strpos(strtolower($this->referer()), 'vm_damages') !== false
                ) {
                    return $this->redirect($this->referer() . '#addNewRepairForm');
                } else {
                    return $this->redirect(array('controller' => 'vmRepairs', 'action' => 'index'));
                }
            } else {

                $this->Session->setFlash('Greška pri dodavanju registracije', 'flash_error');
                $this->Session->write('errors.VmRepair', $this->VmRepair->validationErrors);
                $this->Session->write('errors.VmRepairVmCrossedKm', $this->VmCrossedKm->validationErrors);
                $this->Session->write(
                    'errors.data',
                    $this->request->data
                );
                if (
                    strpos(strtolower($this->referer()), 'vmvehicles') !== false ||
                    strpos(strtolower($this->referer()), 'vm_vehicles') !== false ||
                    strpos(strtolower($this->referer()), 'vmcompanies') !== false ||
                    strpos(strtolower($this->referer()), 'vm_companies') !== false

                ) {
                    return $this->redirect($this->referer() . '#tab_vm_repairs');
                } else if (
                    strpos(strtolower($this->referer()), 'vmdamages') !== false ||
                    strpos(strtolower($this->referer()), 'vm_damages') !== false
                ) {
                    return $this->redirect($this->referer() . '#addNewRepairForm');
                }
            }
        }
    }


    public function delete($vm_repair_id = null)
    {

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $vm_repair = $this->VmRepair->findById($vm_repair_id);
        if ($this->VmRepair->delete($vm_repair_id)) {
            $this->VmChangeLog->saveVmVehicleLog($this->VmRepair, $vm_repair['VmDamage']['vm_vehicle_id'], $this->Session, $this->Auth);
            $this->VmCrossedKm->delete($vm_repair['VmRepair']['vm_crossed_km_id']);
            $this->Session->setFlash('Uspešno ste izbrisali popravku', 'flash_success');
        } else {
            $this->Session->setFlash('Došlo je do greške pri brisanju popravke', 'flash_error');
        }
        if (
            strpos(strtolower($this->referer()), 'vmvehicles') !== false ||
            strpos(strtolower($this->referer()), 'vm_vehicles') !== false ||
            strpos(strtolower($this->referer()), 'vmcompanies') !== false ||
            strpos(strtolower($this->referer()), 'vm_companies') !== false
        ) {
            return $this->redirect($this->referer() . '#tab_vm_repairs');
        }
        $this->redirect(array('action' => 'index'));
    }
}
