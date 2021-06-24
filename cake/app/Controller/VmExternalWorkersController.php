<?php
App::uses('AppController', 'Controller');
class VmExternalWorkersController extends AppController
{


    public function beforeFilter()
    {
        $this->Auth->allow('index', 'add', 'view', 'edit', 'delete', 'save');
        if (
			strtolower($this->request['action']) == 'view' ||
			strtolower($this->request['action']) == 'save'
		) {
			$vm_external_worker_id = !empty($this->request['pass']) ? $this->request['pass'][0] : 0;

			if ($vm_external_worker_id) {
				$vm_external_worker = $this->VmExternalWorker->findById($vm_external_worker_id);
				if (empty($vm_vehicle)) {
					$this->Session->setFlash(__('Traženi eksterni radnik nije pronađen'), 'flash_error');
					return $this->redirect(array('controller' => 'vmExternalWorkers', 'action' => 'index'));
				}
			}
			else if(strtolower($this->request['action']) != 'save')
			{
				$this->Session->setFlash(__('Niste prosledili id eksternog radnika'), 'flash_error');
				return $this->redirect(array('controller' => 'vmExternalWorkers', 'action' => 'index'));
			}

		}
    }

    var $name = 'VmExternalWorkers';
    public $components = array('Paginator');
    public $uses = array(
        'VmExternalWorker',
        'VmVehicle',
        'VmRegistration',
        'VmChangeLog',
        'VmCompany',
        'VmCrossedKm',
        'VmFuel',
        'VmRepair',
        'VmMaintenance',
        'VmDamage',
        'VmVehicleFile',
        'HrWorker'
    );



    public function index()
    {
        $this->set('title_for_layout', __('Eksterni radnici - MikroERP'));

        $vm_vehicles = $this->VmVehicle->find(
            'list',
            array('fields' =>
            array('VmVehicle.id', 'VmVehicle.brand_and_name'))
        );
        $this->set('vm_vehicles', $vm_vehicles);

        $vm_companies = $this->VmCompany->find(
            'list',
            array('fields' =>
            array('VmCompany.id', 'VmCompany.name'))
        );
        $this->set('vm_companies', $vm_companies);

        $conditions = array();
		$joins = array();

        if (isset($this->request->query['keywords']) && $this->request->query['keywords'] != '') {
			$keywords = $this->request->query['keywords'];

			$conditions['OR']['VmExternalWorker.first_name LIKE'] = '%' . $keywords . '%';
			$conditions['OR']['VmExternalWorker.last_name LIKE'] = '%' . $keywords . '%';
			$conditions['OR']['VmExternalWorker.phone_number LIKE'] = '%' . $keywords . '%';
			$conditions['OR']['VmExternalWorker.email LIKE'] = '%' . $keywords . '%';

			$this->request->data['keywords'] = $keywords;
		}

        if (isset($this->request->query['vm_company_id'])  && $this->request->query['vm_company_id'] != '') {
            $vm_company_id = $this->request->query['vm_company_id'];

            $conditions[] = array(
                'VmExternalWorker.vm_company_id =' => $vm_company_id
            );

            $this->request->data['vm_company_id'] = $vm_company_id;
        }



        $options = array(
            'joins' => $joins,
            'conditions' => $conditions,
            'recursive' => 2,
            'limit' => 5
        );



        $this->Paginator->settings = $options;
        $this->set('vm_external_workers', $this->Paginator->paginate());

    }
    public function save($vm_external_worker_id = null)
    {
        if ($vm_external_worker_id == null) {
            $this->set('action', 'add');
            $success = __('Uspešno ste dodali eksternog radnika');
            $this->VmExternalWorker->create();
        } else {
            $this->set('action', 'edit');
            $success = __('Uspešno ste izmenili eksternog radnika');
            $this->VmExternalWorker->id = $vm_external_worker_id;
            $vm_external_worker = $this->VmExternalWorker->findById($vm_external_worker_id);
            $this->set('vm_external_worker', $vm_external_worker);
            // $this->request->data = $vm_external_worker;
        }


        $this->set('vm_companies', $this->VmCompany->find(
            'list',
            array(
                'fields' => array('VmCompany.id', 'VmCompany.name')
            )
        ));

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->VmExternalWorker->save($this->request->data)) {

                $this->Session->setFlash($success, 'flash_success');

                if (
                    strpos(strtolower($this->referer()), 'vmcompanies') !== false ||
                    strpos(strtolower($this->referer()), 'vm_companies') !== false
                ) {
                    return $this->redirect($this->referer() . '#tab_vm_external_workers');
                } else {
                    return $this->redirect(array('controller' => 'vmExternalWorkers', 'action' => 'index'));
                }
            } else {
                $this->Session->setFlash('Došlo je do problema sa bazom', 'flash_error');
                $this->Session->write('errors.VmExternalWorker', $this->VmExternalWorker->validationErrors);
                $this->Session->write(
                    'errors.data',
                    $this->request->data
                );
                if (
                    strpos(strtolower($this->referer()), 'vmcompanies') !== false ||
                    strpos(strtolower($this->referer()), 'vm_companies') !== false
                ) {
                    return $this->redirect($this->referer() . '#tab_vm_external_workers');
                }
            }
        }
    }

    public function view($vm_external_worker_id = null)
    {

        $vm_external_worker = $this->VmExternalWorker->findById($vm_external_worker_id);
        $this->set('vm_external_worker', $vm_external_worker);

    }
}
