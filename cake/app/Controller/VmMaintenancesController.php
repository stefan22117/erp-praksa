<?php
App::uses('AppController', 'Controller');

class VmMaintenancesController  extends AppController
{

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('index', 'save', 'view', 'delete');
        if (
            strtolower($this->request['action']) == 'view' ||
            strtolower($this->request['action']) == 'save' ||
            strtolower($this->request['action']) == 'delete'
        ) {
            $vm_maintenance_id = !empty($this->request['pass']) ? $this->request['pass'][0] : 0;

            if ($vm_maintenance_id) {
                $vm_maintenance = $this->VmMaintenance->findById($vm_maintenance_id);
                if (empty($vm_maintenance)) {
                    $this->Session->setFlash(__('Traženo održavanje nije pronađeno'), 'flash_error');
                    return $this->redirect(array('controller' => 'vmMaintenances', 'action' => 'index'));
                }
            } else if (strtolower($this->request['action']) != 'save') {
                $this->Session->setFlash(__('Niste prosledili id održavanja'), 'flash_error');
                return $this->redirect(array('controller' => 'vmMaintenances', 'action' => 'index'));
            }
        }
    }
    public $components = array('Paginator');
    public $uses = array(
        'VmMaintenance',
        'VmVehicle',
        'VmCrossedKm',
        'HrWorker',
        'VmCompany',
    );


    public function index()
    {
        $this->set('title_for_layout', __('Održavanja - MikroERP'));

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


        //donditions
        $conditions = array();
        $joins = array();

        //donditions




        $options = array(
            'joins' => $joins,
            'conditions' => $conditions,
            'recursive' => 2,
            'limit' => 5
        );

        $this->Paginator->settings = $options;
        $this->set('vm_maintenances', $this->Paginator->paginate());
    }

    public function view($vm_maintenance_id = null)
    {
        $vm_maintenance = $this->VmMaintenance->find(
            'first',
            array(
                'conditions' => array(
                    'VmMaintenance.id' => $vm_maintenance_id
                ),
                'recursive' => 2
            )
        );
        $this->set('vm_maintenance', $vm_maintenance);

        $spent_time = $vm_maintenance['VmMaintenance']['spent_time'];
        $m = floor(($spent_time % 3600) / 60);
        $h = floor(($spent_time % 86400) / 3600);
        $d = floor($spent_time / 86400);

        $spent_time = '';
        $d ? $spent_time = ($d == 1 ?  __(' dan i ') : $d . __(' dana i ')) : null;
        $spent_time .= $h . ':' . $m;


        $this->set('spent_time', $spent_time);
    }

    public function save($vm_maintenance_id = null)
    {
        if ($vm_maintenance_id == null) {
            $this->set('action', 'add');
            $success = __('Uspešno ste dodali održavanje');
            $this->VmMaintenance->create();
            $vm_vehicles = $this->VmVehicle->find(
                'list',
                array(
                    'fields' => array('VmVehicle.id', 'VmVehicle.brand_and_model')
                )
            );
            $this->set('vm_vehicles', $vm_vehicles);
        } else {
            $this->set('action', 'edit');
            $success = __('Uspešno ste izmenili održavanje');
            $this->VmMaintenance->id = $vm_maintenance_id;
            $vm_maintenance = $this->VmMaintenance->findById($vm_maintenance_id);
            $this->VmCrossedKm->id = $vm_maintenance['VmMaintenance']['vm_crossed_km_id'];
            $this->set('vm_maintenance', $vm_maintenance);

            $m = floor(($vm_maintenance['VmMaintenance']['spent_time'] % 3600) / 60);
            $h = floor(($vm_maintenance['VmMaintenance']['spent_time'] % 86400) / 3600);
            $d = floor($vm_maintenance['VmMaintenance']['spent_time'] / 86400);




            $vm_maintenance['VmMaintenance']['spent_time'] = array(
                'day' => $d,
                'hour' => $h,
                'min' => $m,
            );


            if ($this->request->is('get')) {
                $this->request->data = $vm_maintenance;
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


        if ($this->request->is('post') || $this->request->is('put')) {
            $vm_maintenance = $this->request->data;

            $vm_maintenance['VmCrossedKm']['vm_vehicle_id'] = $vm_maintenance['VmMaintenance']['vm_vehicle_id'];
            $vm_maintenance['VmMaintenance']['id'] = $this->VmMaintenance->id;
            $vm_maintenance['VmCrossedKm']['id'] = $this->VmCrossedKm->id;

            $vm_maintenance['VmMaintenance']['spent_time'] =
                $vm_maintenance['VmMaintenance']['spent_time']['day'] * 86400 +
                $vm_maintenance['VmMaintenance']['spent_time']['hour'] * 3600 +
                $vm_maintenance['VmMaintenance']['spent_time']['min'] * 60;

            if ($this->VmMaintenance->saveAll($vm_maintenance)) {

                $this->VmChangeLog->saveVmVehicleLog($this->VmMaintenance, $vm_maintenance['VmMaintenance']['vm_vehicle_id'], $this->Session, $this->Auth);
        
                $this->Session->setFlash($success, 'flash_success');
                if (
                    strpos(strtolower($this->referer()), 'vmvehicles') !== false ||
                    strpos(strtolower($this->referer()), 'vm_vehicles') !== false ||
                    strpos(strtolower($this->referer()), 'vmcompanies') !== false ||
                    strpos(strtolower($this->referer()), 'vm_companies') !== false
                ) {
                    return $this->redirect($this->referer() . '#tab_vm_maintenances');
                } else {
                    return $this->redirect(array('controller' => 'vmMaintenances', 'action' => 'index'));
                }
            } else {

                $this->Session->setFlash('Greška pri dodavanju održavanja', 'flash_error');
                $this->Session->write('errors.VmMaintenance', $this->VmMaintenance->validationErrors);
                $this->Session->write('errors.VmMaintenanceVmCrossedKm', $this->VmCrossedKm->validationErrors);
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
                    return $this->redirect($this->referer() . '#tab_vm_maintenances');
                }
            }
        }
    }

    public function delete($vm_maintenance_id = null)
    {

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $vm_maintenance = $this->VmMaintenance->findById($vm_maintenance_id);
        if ($this->VmMaintenance->delete($vm_maintenance_id)) {
            $this->VmChangeLog->saveVmVehicleLog($this->VmMaintenance, $vm_maintenance['VmMaintenance']['vm_vehicle_id'], $this->Session, $this->Auth);
        

            $this->VmCrossedKm->delete($vm_maintenance['VmMaintenance']['vm_crossed_km_id']);
            $this->Session->setFlash('Uspešno ste izbrisali održavanje', 'flash_success');
        } else {
            $this->Session->setFlash('Došlo je do greške pri brisanju održavanja', 'flash_error');
        }
        if (
            strpos(strtolower($this->referer()), 'vmvehicles') !== false ||
            strpos(strtolower($this->referer()), 'vm_vehicles') !== false ||
            strpos(strtolower($this->referer()), 'vmcompanies') !== false ||
            strpos(strtolower($this->referer()), 'vm_companies') !== false
        ) {
            return $this->redirect($this->referer() . '#tab_vm_maintenances');
        }
        $this->redirect(array('action' => 'index'));
    }
}
