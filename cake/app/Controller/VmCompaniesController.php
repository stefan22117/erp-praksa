<?php
App::uses('AppController', 'Controller');

class VmCompaniesController  extends AppController
{

    public $components = array('Paginator');

    public $uses = array(
        'VmCompany',
        'VmMaintenance',
        'VmCrossedKm',
        'VmVehicle',
        'HrWorker',
        'VmRepair',
        'VmDamage',
        'VmExternalWorker',
        'VmRegistration'
    );

    public function beforeFilter()
    {
        $this->Auth->allow('index', 'view', 'save', 'delete');
        $items = ['1'];
        $this->set('items', $items);
        if (
            strtolower($this->request['action']) == 'view' ||
            strtolower($this->request['action']) == 'save' ||
            strtolower($this->request['action']) == 'delete'
        ) {
            $vm_company_id = !empty($this->request['pass']) ? $this->request['pass'][0] : 0;

            if ($vm_company_id) {
                $vm_company = $this->VmCompany->findById($vm_company_id);
                if (empty($vm_company)) {
                    $this->Session->setFlash(__('Tražena firma nije pronađena'), 'flash_error');
                    return $this->redirect(array('controller' => 'vmCompanies', 'action' => 'index'));
                }
            } else if (strtolower($this->request['action']) != 'save') {
                $this->Session->setFlash(__('Niste prosledili id firme'), 'flash_error');
                return $this->redirect(array('controller' => 'vmCompanies', 'action' => 'index'));
            }
        }
    }


    public function index()
    {
        $this->set('title_for_layout', __('Firme - MikroERP'));
        $conditions = array();
        $joins = array();

        if (isset($this->request->query['keywords']) && $this->request->query['keywords'] != '') {
            $keywords = $this->request->query['keywords'];

            $conditions['OR']['VmCompany.name LIKE'] = '%' . $keywords . '%';
            $conditions['OR']['VmCompany.address LIKE'] = '%' . $keywords . '%';
            $conditions['OR']['VmCompany.city LIKE'] = '%' . $keywords . '%';
            $conditions['OR']['VmCompany.email LIKE'] = '%' . $keywords . '%';
            $conditions['OR']['VmCompany.zip_code LIKE'] = '%' . $keywords . '%';

            $this->request->data['keywords'] = $keywords;
        }
        $options = array(
            'joins' => $joins,
            'conditions' => $conditions,
            'recursive' => 2,
            'limit' => 5
        );



        $this->Paginator->settings = $options;



        $vm_companies = $this->Paginator->paginate();
        $this->set('vm_companies', $vm_companies);
    }


    public function save($vm_company_id = null)
    {
        if ($vm_company_id == null) {
            $this->set('action', 'add');
            $success = __('Uspešno ste dodali firmu');
            $this->VmCompany->create();
        } else {
            $this->set('action', 'edit');
            $success = __('Uspešno ste izmenili firmu');
            $this->VmCompany->id = $vm_company_id;

            $vm_company = $this->VmCompany->findById($vm_company_id);
            $this->set('vm_company', $vm_company);

            //external workers
            $vm_external_workers_selected = $this->VmExternalWorker->find(
                'list',

                array(
                    'conditions' => array('VmExternalWorker.vm_company_id' => $vm_company_id),
                    'fields' => array('VmExternalWorker.id', 'VmExternalWorker.id')
                )
            );
            $this->set('vm_external_workers_selected', $vm_external_workers_selected);



            if ($this->request->is('get')) {
                $this->request->data = $vm_company;
            }
        }

        $vm_external_workers = $this->VmExternalWorker->find(
            'list',
            array(
                'fields' => array('VmExternalWorker.id', 'VmExternalWorker.first_name')
            )
        );
        $this->set('vm_external_workers', $vm_external_workers);





        if ($this->request->is('post') || $this->request->is('put')) {

            $vm_company = $this->request->data;
            $vm_company['VmCompany']['id'] = $this->VmCompany->id;

            if ($vm_company = $this->VmCompany->save($vm_company)) {
                foreach ($this->VmExternalWorker->findAllByVmCompanyId($vm_company['VmCompany']['id']) as $vm_external_worker) {
                    $vm_external_worker['VmExternalWorker']['vm_company_id'] = 0;
                    $this->VmExternalWorker->save($vm_external_worker['VmExternalWorker']);
                }

                if (!empty($vm_company['VmExternalWorker']['id'])) {
                    foreach ($vm_company['VmExternalWorker']['id'] as $vm_external_worker_id) {

                        $vm_external_worker = $this->VmExternalWorker->findById($vm_external_worker_id);

                        if ($vm_external_worker) {
                            $vm_external_worker['VmExternalWorker']['vm_company_id'] = $vm_company['VmCompany']['id'];

                            $this->VmExternalWorker->save($vm_external_worker['VmExternalWorker']);
                        }
                    }
                }
                $this->Session->setFlash($success, 'flash_success');
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Došlo je do greške', 'flash_error');
            }
        }
    }


    public function view($vm_company_id)
    {
        $errors = array();
        //getting errors start
        if ($this->Session->read('errors')) {
            $this->request->data = $this->Session->read('errors.data');
        }
        if ($this->Session->read('errors.VmRegistration')) {
            $errors['Registrations'] = true;
            $this->VmRegistration->validationErrors = $this->Session->read('errors.VmRegistration');
        }


        if ($this->Session->read('errors.VmRepair')) {
            $errors['Repairs'] = true;
            $this->VmRepair->validationErrors = $this->Session->read('errors.VmRepair');
            $this->VmCrossedKm->validationErrors = $this->Session->read('errors.VmRepairVmCrossedKm');
        }
        if ($this->Session->read('errors.VmMaintenance')) {
            $errors['Maintenances'] = true;
            $this->VmMaintenance->validationErrors = $this->Session->read('errors.VmMaintenance');
            $this->VmCrossedKm->validationErrors = $this->Session->read('errors.VmMaintenanceVmCrossedKm');
        }
        if ($this->Session->read('errors.VmExternalWorker')) {
            $errors['ExternalWorkers'] = true;
            $this->VmExternalWorker->validationErrors = $this->Session->read('errors.VmExternalWorker');
        }




        $this->set('errors', $errors);
        $this->Session->delete('errors');


        //getting errors end




















        $vm_company = $this->VmCompany->find('first', array(
            'conditions' => array('VmCompany.id = ' => $vm_company_id),
            'recursive' => 2
        ));
        $this->set('vm_company', $vm_company);

        //workers
        $hr_workers = $this->HrWorker->find('list', array(
            'fields' => array('HrWorker.id', 'HrWorker.first_name')
        ));
        $this->set('hr_workers', $hr_workers);

        //vehicles
        $vm_vehicles = $this->VmVehicle->find('list', array(
            'fields' => array('VmVehicle.id', 'VmVehicle.brand_and_model')
        ));
        $this->set('vm_vehicles', $vm_vehicles);




        //registrations
        $vm_registrations = $this->VmRegistration->find('all', array(
            'conditions' => array('VmRegistration.vm_company_id = ' => $vm_company_id),
            'recursive' => 2
        ));
        $this->set('vm_registrations', $vm_registrations);



        //maintenances
        $vm_maintenances = $this->VmMaintenance->find('all', array(
            'conditions' => array('VmMaintenance.vm_company_id = ' => $vm_company_id),
            'recursive' => 2
        ));
        $this->set('vm_maintenances', $vm_maintenances);

        //repairs
        $vm_repairs = $this->VmRepair->find('all', array(
            'conditions' => array('VmRepair.vm_company_id = ' => $vm_company_id),
            'recursive' => 2
        ));
        $this->set('vm_repairs', $vm_repairs);


        //damages
        $vm_damages = $this->VmDamage->find('list', array(
            'fields' => array('VmDamage.id', 'VmDamage.description')
        ));
        $this->set('vm_damages', $vm_damages);


        //external_workers
        $vm_external_workers = $this->VmExternalWorker->find('all', array(
            'conditions' => array('VmExternalWorker.vm_company_id = ' => $vm_company_id),
            'recursive' => 2
        ));
        $this->set('vm_external_workers', $vm_external_workers);
    }

















    public function delete($vm_company_id = null)
    {

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        if ($this->VmCompany->delete($vm_company_id)) {

            $vm_c_m = $this->VmMaintenance->findAllByVmCompanyId($vm_company_id);
            $arr = [];

            foreach ($vm_c_m as $i) {
                $arr[] = $i['VmMaintenance']['vm_crossed_km_id'];
            }
            $this->VmCrossedKm->deleteAll(
                array(
                    'VmCrossedKm.id' => $arr
                )
            );

            $vm_c_r = $this->VmRepair->findAllByVmCompanyId($vm_company_id);
            $arr = [];

            foreach ($vm_c_r as $i) {
                $arr[] = $i['VmRepair']['vm_crossed_km_id'];
            }
            $this->VmCrossedKm->deleteAll(
                array(
                    'VmCrossedKm.id' => $arr
                )
            );

            $this->VmMaintenance->deleteAll(
                array('VmMaintenance.vm_company_id' => $vm_company_id)
            );

            $this->VmRepair->deleteAll(
                array('VmRepair.vm_company_id' => $vm_company_id)
            );

            $this->VmExternalWorker->deleteAll(
                array('VmExternalWorker.vm_company_id' => $vm_company_id)
            );
            $this->Session->setFlash('Uspešno ste izbrisali firmu', 'flash_success');
        } else {
            $this->Session->setFlash('Došlo je do greške pri brisanju firme', 'flash_error');
        }
        $this->redirect(array('action' => 'index'));
    }
}
