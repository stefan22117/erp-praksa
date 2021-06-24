<?php
App::uses('AppController', 'Controller');
class VmregistrationsController extends AppController
{
    public function beforeFilter()
    {
        $this->Auth->allow('index', 'add', 'view', 'edit', 'save', 'delete');
        if (
			strtolower($this->request['action']) == 'view' ||
			strtolower($this->request['action']) == 'save'
		) {
			$vm_registration_id = !empty($this->request['pass']) ? $this->request['pass'][0] : 0;

			if ($vm_registration_id) {
				$vm_registration = $this->VmVehicle->findById($vm_registration_id);
				if (empty($vm_registration)) {
					$this->Session->setFlash(__('Tražena registracija nije pronađena'), 'flash_error');
					return $this->redirect(array('controller' => 'vmRegistrations', 'action' => 'index'));
				}
			}
			else if(strtolower($this->request['action']) != 'save')
			{
				$this->Session->setFlash(__('Niste prosledili id registracije'), 'flash_error');
				return $this->redirect(array('controller' => 'vmRegistrations', 'action' => 'index'));
			}

		}
    }


    public $components = array('Paginator');

    public $uses = [
        'VmRegistration',
        'HrWorker',
        'VmVehicle',
        'VmCompany',
        'VmRegistrationFile'
    ];

    public function index()
    {

        $this->set('title_for_layout', __('Registracije - MikroERP'));

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


        $conditions = array();
        $joins = array();

        if (isset($this->request->query['vm_vehicle_id'])  && $this->request->query['vm_vehicle_id'] != '') {
            $vm_vehicle_id = $this->request->query['vm_vehicle_id'];

            $conditions[] = array(
                'VmRegistration.vm_vehicle_id =' => $vm_vehicle_id
            );

            $this->request->data['vm_vehicle_id'] = $vm_vehicle_id;
        }


        if (isset($this->request->query['hr_worker_id'])  && $this->request->query['hr_worker_id'] != '') {
            $hr_worker_id = $this->request->query['hr_worker_id'];

            $conditions[] = array(
                'VmRegistration.hr_worker_id =' => $hr_worker_id
            );


            $this->request->data['hr_worker_id'] = $hr_worker_id;
        }

        if (isset($this->request->query['vm_company_id'])  && $this->request->query['vm_company_id'] != '') {
            $vm_company_id = $this->request->query['vm_company_id'];

            $conditions[] = array(
                'VmRegistration.vm_company_id =' => $vm_company_id
            );


            $this->request->data['vm_company_id'] = $vm_company_id;
        }
        if (isset($this->request->query['valid'])) {
            $valid = $this->request->query['valid'];


            $conditions[] = array(
                'VmRegistration.expiration_date >=' => date('Y-m-d')
            );


            $this->request->data['valid'] = $valid;
        }




        $options = array(
            'conditions' => $conditions,
            'joins' => $joins,
            'order' => 'VmRegistration.created DESC',
            'recursive' => 2,
            'limit' => 5,
            'fields' => 'DISTINCT VmRegistration.*'
        );








        $this->Paginator->settings = $options;
        $this->set('vm_registrations', $this->Paginator->paginate());
    }

    public function save($vm_registration_id = null)
    {

        if ($vm_registration_id == null) {
            $this->set('action', 'add');
            $success = __('Uspešno ste dodali registraciju');
            $this->VmRegistration->create();
            $vm_vehicles = $this->VmVehicle->find(
                'list',
                array(
                    'fields' => array('VmVehicle.id', 'VmVehicle.brand_and_model')
                )
            );
            $this->set('vm_vehicles', $vm_vehicles);
        } else {
            $this->set('action', 'edit');
            $success = __('Uspešno ste izmenili registraciju');
            $this->VmRegistration->id = $vm_registration_id;
            $vm_registration = $this->VmRegistration->findById($vm_registration_id);
            $this->set('vm_registration', $vm_registration);

            $m = floor(($vm_registration['VmRegistration']['spent_time'] % 3600) / 60);
            $h = floor(($vm_registration['VmRegistration']['spent_time'] % 86400) / 3600);
            $d = floor($vm_registration['VmRegistration']['spent_time'] / 86400);



            $vm_registration['VmRegistration']['spent_time'] = array(
                'day' => $d,
                'hour' => $h,
                'min' => $m,
            );


            if($this->request->is('get')){
                $this->request->data = $vm_registration;
            }
        }

        $hr_workers = $this->HrWorker->find('list', array(
            'fields' => array('HrWorker.id', 'HrWorker.first_name')
        ));
        $this->set('hr_workers', $hr_workers);
        $vm_companies = $this->VmCompany->find('list', array(
            'fields' => array('VmCompany.id', 'VmCompany.name')
        ));
        $this->set('vm_companies', $vm_companies);

        if ($this->request->is('post') || $this->request->is('put')) {


            $vm_registration = $this->request->data;
            $vm_registration['VmRegistration']['spent_time'] =
                $vm_registration['VmRegistration']['spent_time']['day'] * 86400 +
                $vm_registration['VmRegistration']['spent_time']['hour'] * 3600 +
                $vm_registration['VmRegistration']['spent_time']['min'] * 60;

            if ($this->VmRegistration->save($vm_registration)) {
                $this->Session->setFlash($success, 'flash_success');
                if (
                    strpos(strtolower($this->referer()), 'vmvehicles') !== false ||
                    strpos(strtolower($this->referer()), 'vm_vehicles') !== false
                ) {
                    return $this->redirect($this->referer() . '#tab_vm_registrations');
                } else {
                    return $this->redirect(array('controller' => 'vmRegistrations', 'action' => 'index'));
                }
            } else {

                $this->Session->setFlash('Greška pri dodavanju registracije', 'flash_error');
                $this->Session->write('errors.VmRegistration', $this->VmRegistration->validationErrors);
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
                    return $this->redirect($this->referer() . '#tab_vm_registrations');
                }
            }
        }
    }

    public function add()

    {
        $vehicle_id = $this->request->data['VmRegistration']['vm_vehicle_id'];

        if ($vehicle_id && $vm_vehicle = $this->VmVehicle->findById($vehicle_id)) {

            $this->set('vm_vehicle', $vm_vehicle);
        } else {
            if ($vehicle_id && !$vm_vehicle) {
                $this->Session->setFlash('Nije pronadjeno vozilo', 'flash_error');
            }

            $this->set('vm_vehicles', $this->VmVehicle->find('list', array(
                'fields' => array('VmVehicle.id', 'VmVehicle.brand_and_model')
            )));
        }

        $this->set('hr_workers', $this->HrWorker->find('list', array(
            'fields' => array('HrWorker.id', 'HrWorker.first_name')
        )));
        $this->set('vm_companies', $this->VmCompany->find('list', array(
            'fields' => array('VmCompany.id', 'VmCompany.name')
        )));
        if ($this->request->is('post')) {

            $vm_registration = $this->request->data;
            $vm_registration['VmRegistration']['spent_time'] =
                $vm_registration['VmRegistration']['spent_time']['day'] * 86400 +
                $vm_registration['VmRegistration']['spent_time']['hour'] * 3600 +
                $vm_registration['VmRegistration']['spent_time']['min'] * 60;


            $this->VmRegistration->create();
            if ($this->VmRegistration->save($vm_registration)) {
                $this->Session->setFlash('Uspesno ste dodali registraciju', 'flash_success');
            } else {

                $this->Session->setFlash('Greška pri dodavanju registracije', 'flash_error');
                $this->Session->write('errors.VmRegistration', $this->VmRegistration->validationErrors);
                $this->Session->write(
                    'errors.data',
                    $this->request->data
                );
            }
            $this->redirect($this->referer() . '#tabr1');
        }
    }


    public function edit($vm_registration_id = null)
    {

        if ($vm_registration_id == null) {
            $this->Session->setFlash('Niste prosledili id registracije', 'flash_error');
            return $this->redirect(array('action' => 'add'));
        }
        if (!($vm_registration = $this->VmRegistration->findById($vm_registration_id))) {
            $this->Session->setFlash(__('Nema registracije sa ID-jem: ') . $vm_registration_id, 'flash_error');
            return $this->redirect(array('action' => 'add'));
        }



        $this->set('vm_registration', $vm_registration);

        $m = floor(($vm_registration['VmRegistration']['spent_time'] % 3600) / 60);
        $h = floor(($vm_registration['VmRegistration']['spent_time'] % 86400) / 3600);
        $d = floor($vm_registration['VmRegistration']['spent_time'] / 86400);



        $vm_registration['VmRegistration']['spent_time'] = array(
            'day' => $d,
            'hour' => $h,
            'min' => $m,
        );




        $vm_vehicles = [];
        foreach ($this->VmVehicle->find('all') as $vm_vehicle) {

            $vm_vehicles[$vm_vehicle['VmVehicle']['id']] = $vm_vehicle['VmVehicle']['brand_and_model'];
        }
        $this->set('vm_vehicles', $vm_vehicles);

        $hr_workers = [];
        foreach ($this->HrWorker->find('all') as $hr_worker) {

            $hr_workers[$hr_worker['HrWorker']['id']] = $hr_worker['HrWorker']['first_name'];
        }
        $this->set('hr_workers', $hr_workers);
        $vm_companies = [];
        foreach ($this->VmCompany->find('all') as $vm_company) {
            $vm_companies[$vm_company['VmCompany']['id']] = $vm_company['VmCompany']['name'];
        }
        $this->set('vm_companies', $vm_companies);
        if ($this->request->is('post') || $this->request->is('put')) {

            $vm_registration = $this->request->data;

            $vm_registration['VmRegistration']['spent_time'] =
                $vm_registration['VmRegistration']['spent_time']['day'] * 86400 +
                $vm_registration['VmRegistration']['spent_time']['hour'] * 3600 +
                $vm_registration['VmRegistration']['spent_time']['min'] * 60;

            $this->VmRegistration->id = $vm_registration_id;


            if ($this->VmRegistration->save($vm_registration)) {
                $this->Session->setFlash('Uspesno ste dodali registraciju', 'flash_success');
                return $this->redirect(array('controller' => 'vmVehicles', 'action' => 'view', $vm_registration['VmRegistration']['vm_vehicle_id']));
            } else {
                $this->Session->setFlash('Greska pri dodavanju registracije', 'flash_error');
            }
        } else {
            $this->request->data = $vm_registration;
        }
    }




    public function view($vm_registration_id = null)
    {

        $errors = array();

        if ($this->Session->read('errors')) {
            $this->request->data = $this->Session->read('errors.data');
        }


        if ($this->Session->read('errors.VmRegistrationFile')) {
            $errors['RegistrationFiles'] = true;
            $this->VmRegistrationFile->validationErrors = $this->Session->read('errors.VmRegistrationFile');
        }

        $this->set('errors', $errors);
        $this->Session->delete('errors');










        $vm_registration = $this->VmRegistration->findById($vm_registration_id);

        $vm_registration_files = $this->VmRegistrationFile->findAllByVmRegistrationId($vm_registration_id);

        $vm_vehicle = $this->VmVehicle->findById($vm_registration['VmVehicle']['id']);
        $vm_registration = $this->VmRegistration->findById($vm_registration['VmRegistration']['id']);

        for ($i = 0; $i < count($vm_registration['VmRegistrationFile']); $i++) {
            $vm_registration_files[$i]['VmVehicle'] = $vm_vehicle['VmVehicle'];
            $vm_registration_files[$i]['VmCompany'] = $vm_registration['VmCompany'];
        }

        $spent_time = $vm_registration['VmRegistration']['spent_time'];
        $m = floor(($spent_time % 3600) / 60);
        $h = floor(($spent_time % 86400) / 3600);
        $d = floor($spent_time / 86400);

        $spent_time = '';
        $d ? $spent_time = ($d == 1 ?  __(' dan i ') : $d . __(' dana i ')) : null;
        $spent_time .= $h . ':' . $m;
        $this->set('spent_time', $spent_time);
        $this->set('vm_registration_files', $vm_registration_files);
        $this->set('vm_registration', $vm_registration);
    }

    public function delete($vm_registration_id = null)
    {

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        if ($this->VmRegistration->delete($vm_registration_id)) {
            $this->Session->setFlash('Uspešno ste obrisali registraciju', 'flash_success');
        } else {
            $this->Session->setFlash('Došlo je do greške pri brisanju registracije', 'flash_error');
        }
        $this->redirect($this->referer());
    }
}
