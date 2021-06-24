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
        $this->Auth->allow('index', 'add', 'view', 'edit');


        if (
			strtolower($this->request['action']) == 'view' ||
			strtolower($this->request['action']) == 'save'
		) {
			$vm_company_id = !empty($this->request['pass']) ? $this->request['pass'][0] : 0;

			if ($vm_company_id) {
				$vm_company = $this->VmCompany->findById($vm_company_id);
				if (empty($vm_company)) {
					$this->Session->setFlash(__('Tražena firma nije pronađena'), 'flash_error');
					return $this->redirect(array('controller' => 'vmCompanies', 'action' => 'index'));
				}
			}
			else if(strtolower($this->request['action']) != 'save')
			{
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
}
