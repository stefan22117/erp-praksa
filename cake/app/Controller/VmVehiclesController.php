<?php
App::uses('AppController', 'Controller');

class VmVehiclesController  extends AppController
{


	public function beforeFilter()
	{
		$this->Auth->allow('index', 'add', 'view', 'edit', 'delete', 'save');

		if (
			strtolower($this->request['action']) == 'view' ||
			strtolower($this->request['action']) == 'save'
		) {
			$vm_vehicle_id = !empty($this->request['pass']) ? $this->request['pass'][0] : 0;

			if ($vm_vehicle_id) {
				$vm_vehicle = $this->VmVehicle->findById($vm_vehicle_id);
				if (empty($vm_vehicle)) {
					$this->Session->setFlash(__('Traženo vozilo nije pronađeno'), 'flash_error');
					return $this->redirect(array('controller' => 'vmVehicles', 'action' => 'index'));
				}
			}
			else if(strtolower($this->request['action']) != 'save')
			{
				$this->Session->setFlash(__('Niste prosledili id vozila'), 'flash_error');
				return $this->redirect(array('controller' => 'vmVehicles', 'action' => 'index'));
			}

		}
	}

	var $name = 'VmVehicles';
	public $components = array('Paginator');
	public $uses = array(
		'VmVehicle',
		'VmRegistration',
		'VmChangeLog',
		'VmCrossedKm',
		'VmFuel',
		'VmRepair',
		'VmMaintenance',
		'VmDamage',
		'VmVehicleFile',
		'HrWorker',
		'VmExternalWorker',
		'VmExternalWorkerVehicle',
		'VmInternalWorkerVehicle',
		'VmCompany'
	);



	public function index()
	{
		$this->set('title_for_layout', __('Vozila - MikroERP'));

		$this->set('hr_workers', $this->HrWorker->find('list', array(
			'fields' => array('HrWorker.id', 'HrWorker.first_name')
		)));





		$conditions = array();
		$joins = array();


		if (isset($this->request->query['keywords']) && $this->request->query['keywords'] != '') {
			$keywords = $this->request->query['keywords'];

			$conditions['OR']['VmVehicle.brand_and_model LIKE'] = '%' . $keywords . '%';
			$conditions['OR']['VmVehicle.reg_number LIKE'] = '%' . $keywords . '%';
			$conditions['OR']['VmVehicle.horse_power LIKE'] = '%' . $keywords . '%';
			$conditions['OR']['VmVehicle.engine_capacity_cm3 LIKE'] = '%' . $keywords . '%';
			$conditions['OR']['VmVehicle.color LIKE'] = '%' . $keywords . '%';
			$conditions['OR']['VmVehicle.chassis_number LIKE'] = '%' . $keywords . '%';
			$conditions['OR']['VmVehicle.engine_number LIKE'] = '%' . $keywords . '%';




			$this->request->data['VmVehicle']['keywords'] = $keywords;
		}

		if (isset($this->request->query['in_use'])) {
			$in_use = $this->request->query['in_use'];
			$conditions['OR']['VmVehicle.in_use ='] = true;

			$this->request->data['VmVehicle']['in_use'] = $in_use;
		}


		if (isset($this->request->query['hr_worker_id'])  && $this->request->query['hr_worker_id'] != '') {
			$hr_worker_id = $this->request->query['hr_worker_id'];

			$joins[] = array(
				'table' => 'vm_internal_worker_vehicles',
				'alias' => 'VmInternalWorkerVehicle',
				'type' => 'inner',
				'conditions' => array(
					'VmVehicle.id = VmInternalWorkerVehicle.vm_vehicle_id',
					'VmInternalWorkerVehicle.hr_worker_id' => $hr_worker_id
				)
			);


			$this->request->data['VmVehicle']['hr_worker_id'] = $hr_worker_id;
		}

		if (isset($this->request->query['registered'])) {
			$registered = $this->request->query['registered'];


			$joins[] = array(
				'table' => 'vm_registrations',
				'alias' => 'VmRegistration',
				'type' => 'inner',
				'conditions' => array(
					'VmVehicle.id = VmRegistration.vm_vehicle_id',
					'VmRegistration.expiration_date >=' => date('Y-m-d')
				)
			);

			$this->request->data['VmVehicle']['registered'] = $registered;
		}



		$options = array(
			'conditions' => $conditions,
			'joins' => $joins,
			'contain' => array('Aco', 'ErpKickstartIcon'), //???
			'order' => 'VmVehicle.created DESC',
			'recursive' => 2,
			'limit' => 5,
			'fields' => 'DISTINCT VmVehicle.*'

		);

		// Set data for view //
		$this->Paginator->settings = $options;
		$this->set('vm_vehicles', $this->Paginator->paginate());
	}


	public function save($vm_vehicle_id = null)
	{

		if ($vm_vehicle_id == null) {
			$this->set('action', 'add');
			$success = __('Uspešno ste dodali vozilo');
			$this->VmVehicle->create();
		} else {
			$this->set('action', 'edit');
			$success = __('Uspešno ste izmenili vozilo');
			$this->VmVehicle->id = $vm_vehicle_id;

			$vm_vehicle = $this->VmVehicle->findById($vm_vehicle_id);
			$this->set('vm_vehicle', $vm_vehicle);


			//hr workers
			$vm_hr_worker_vehicles = $this->VmInternalWorkerVehicle->findAllByVmVehicleId(
				$vm_vehicle_id,
				array('fields' => 'VmInternalWorkerVehicle.hr_worker_id')
			);
			$vm_hr_array = array();
			foreach ($vm_hr_worker_vehicles as $vm_hr_worker_vehicle) {
				$vm_hr_array[] = $vm_hr_worker_vehicle['VmInternalWorkerVehicle']['hr_worker_id'];
			}
			$this->set('vm_hr_array', $vm_hr_array);
			//hr workers


			//external workers
			$vm_external_worker_vehicles = $this->VmExternalWorkerVehicle->findAllByVmVehicleId(
				$vm_vehicle_id,
				array('fields' => 'VmExternalWorkerVehicle.vm_external_worker_id')
			);
			$vm_ext_array = array();
			foreach ($vm_external_worker_vehicles as $vm_external_worker_vehicle) {
				$vm_ext_array[] = $vm_external_worker_vehicle['VmExternalWorkerVehicle']['vm_external_worker_id'];
			}
			$this->set('vm_ext_array', $vm_ext_array);
			//external workers


		}

		$this->set('hr_workers', $this->HrWorker->find(
			'list',
			array(
				'fields' => array('HrWorker.id', 'HrWorker.first_name')
			)
		));



		$this->set('vm_external_workers', $this->VmExternalWorker->find(
			'list',
			array(
				'fields' => array('VmExternalWorker.id', 'VmExternalWorker.first_name')
			)
		));







		if ($this->request->is('post') || $this->request->is('put')) {


			$this->request->data['VmVehicle']['hr_worker_id'] =
				$this->request->data['VmInternalWorkerVehicle']['hr_worker_id'];
			$this->request->data['VmVehicle']['vm_external_worker_id'] =
				$this->request->data['VmExternalWorkerVehicle']['vm_external_worker_id'];
			$this->request->data['VmVehicle']['reg_number'] = strtoupper($this->request->data['VmVehicle']['reg_number']);
			if ($vm_vehicle = $this->VmVehicle->save($this->request->data)) {


				//niz




				$this->VmInternalWorkerVehicle->deleteAll(array('vm_vehicle_id' => $vm_vehicle['VmVehicle']['id']));
				if (!empty($this->request->data['VmInternalWorkerVehicle']['hr_worker_id'])) {
					foreach ($this->request->data['VmInternalWorkerVehicle']['hr_worker_id'] as $hr_id) {
						$hr_worker_vehicle = array(
							'VmInternalWorkerVehicle' =>
							array(
								'hr_worker_id' => $hr_id,
								'vm_vehicle_id' => $vm_vehicle['VmVehicle']['id']
							)
						);
						$this->VmInternalWorkerVehicle->create();
						$this->VmInternalWorkerVehicle->save($hr_worker_vehicle);
					}
				}

				$this->VmExternalWorkerVehicle->deleteAll(array('vm_vehicle_id' => $vm_vehicle['VmVehicle']['id']));
				if (!empty($this->request->data['VmExternalWorkerVehicle']['vm_external_worker_id'])) {
					foreach ($this->request->data['VmExternalWorkerVehicle']['vm_external_worker_id'] as $ext_id) {
						$vm_external_worker_vehicle = array(
							'VmExternalWorkerVehicle' =>
							array(
								'vm_external_worker_id' => $ext_id,
								'vm_vehicle_id' => $vm_vehicle['VmVehicle']['id']
							)
						);
						$this->VmExternalWorkerVehicle->create();
						$this->VmExternalWorkerVehicle->save($vm_external_worker_vehicle);
					}
				}




				//niz







				$this->Session->setFlash($success, 'flash_success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Došlo je do problema sa bazom', 'flash_error');
			}
		} else {
			if ($vm_vehicle_id != null) {
				$vm_vehicle = $this->VmVehicle->findById($vm_vehicle_id);
				if ($vm_vehicle) {
					$this->request->data = $vm_vehicle;
				} else {
					$this->set('action', 'add');
					$this->Session->setFlash('Izabrali ste nepostojeće vozilo', 'flash_error');
				}
			}
		}
	}


	public function view($vm_vehicle_id = null)
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
		if ($this->Session->read('errors.VmVehicleFile')) {
			$errors['Files'] = true;
			$this->VmVehicleFile->validationErrors = $this->Session->read('errors.VmVehicleFile');
		}


		if ($this->Session->read('errors.VmFuel')) {
			$errors['Fuels'] = true;
			$this->VmFuel->validationErrors = $this->Session->read('errors.VmFuel');
			$this->VmCrossedKm->validationErrors = $this->Session->read('errors.VmFuelVmCrossedKm');
		}

		if ($this->Session->read('errors.VmDamage')) {
			$errors['Damages'] = true;
			$this->VmDamage->validationErrors = $this->Session->read('errors.VmDamage');
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




		$this->set('errors', $errors);
		$this->Session->delete('errors');


		//getting errors end































		//vehicle
		$vm_vehicle = $this->VmVehicle->find('first', array(
			'conditions' => array('VmVehicle.id = ' => $vm_vehicle_id),
			'recursive' => 2
		));
		$this->set('vm_vehicle', $vm_vehicle);


		//registrations
		$vm_registrations = $this->VmRegistration->find('all', array(
			'conditions' => array('VmRegistration.vm_vehicle_id = ' => $vm_vehicle_id),
			'recursive' => 2
		));
		$this->set('vm_registrations', $vm_registrations);


		//vehicle files
		$vm_vehicle_files = $this->VmVehicleFile->find('all', array(
			'conditions' => array('VmVehicleFile.vm_vehicle_id = ' => $vm_vehicle_id),
			'recursive' => 2
		));
		$this->set('vm_vehicle_files', $vm_vehicle_files);

		//fuels
		$vm_fuels = $this->VmFuel->find('all', array(
			'conditions' => array('VmFuel.vm_vehicle_id = ' => $vm_vehicle_id),
			'recursive' => 2
		));
		$this->set('vm_fuels', $vm_fuels);

		//damages
		$vm_damages = $this->VmDamage->find('all', array(
			'conditions' => array('VmDamage.vm_vehicle_id = ' => $vm_vehicle_id),
			'recursive' => 2
		));
		$this->set('vm_damages', $vm_damages);

		//repairs
		$vm_repairs = $this->VmRepair->find('all', array(
			'recursive' => 2
		));

		$temp = [];
		foreach ($vm_repairs as $vm_repair) {
			if ($vm_repair['VmDamage']['vm_vehicle_id'] == $vm_vehicle_id) {
				$temp[] = $vm_repair;
			}
		}
		$vm_repairs = $temp;
		$this->set('vm_repairs', $vm_repairs);

		$vm_damages_for_repairs = $this->VmDamage->find('list', array(
			'conditions' => array('VmDamage.vm_vehicle_id = ' => $vm_vehicle_id),
			'fields' => array('VmDamage.id', 'VmDamage.description')
		));
		$this->set('vm_damages_for_repairs', $vm_damages_for_repairs);


		//maintenances
		$vm_maintenances = $this->VmMaintenance->find('all', array(
			'conditions' => array('VmMaintenance.vm_vehicle_id = ' => $vm_vehicle_id),
			'recursive' => 2
		));
		$this->set('vm_maintenances', $vm_maintenances);




		$vm_max_crossed_km = $this->VmCrossedKm->findAllByVmVehicleId(
			$vm_vehicle_id,
			'total_kilometers',
			'VmCrossedKm.total_kilometers DESC',
			1,
			0,
			-1
		);
		if ($vm_max_crossed_km) {
			$vm_max_crossed_km = $vm_max_crossed_km[0]['VmCrossedKm']['total_kilometers'];
		} else {
			$vm_max_crossed_km = 0;
		}
		$this->set('vm_max_crossed_km', $vm_max_crossed_km);

		$vm_max_registration_date = $this->VmRegistration->findAllByVmVehicleId(
			$vm_vehicle_id,
			'expiration_date',
			'VmRegistration.expiration_date DESC',
			10,
			0,
			-1
		);
		if ($vm_max_registration_date) {
			$vm_max_registration_date = $vm_max_registration_date[0]['VmRegistration']['expiration_date'];
			if (date("Y-m-d") >= $vm_max_registration_date) {
				echo __('(Istekla)');
			} else {
			}
		} else {
			$vm_max_registration_date = __('Nije registrovan');
		}
		$this->set('vm_max_registration_date', $vm_max_registration_date);

		$hr_workers = $this->HrWorker->find('list', array(
			'fields' => array('HrWorker.id', 'HrWorker.first_name')
		));
		$this->set('hr_workers', $hr_workers);

		$vm_companies = $this->VmCompany->find('list', array(
			'fields' => array('VmCompany.id', 'VmCompany.name')
		));
		$this->set('vm_companies', $vm_companies);
	}

	public function add()
	{

		if ($this->request->is('post')) {

			$this->request->data['VmVehicle']['active_from'] = $this->data['VmVehicle']['active_from']['year'] . '-' .
				$this->data['VmVehicle']['active_from']['month'] . '-' . $this->data['VmVehicle']['active_from']['day'];

			$this->request->data['VmVehicle']['active_to'] = $this->data['VmVehicle']['active_to']['year'] . '-' .
				$this->data['VmVehicle']['active_to']['month'] . '-' . $this->data['VmVehicle']['active_to']['day'];


			if ($this->VmVehicle->save($this->request->data)) {
				$this->Session->setFlash('Uspesno ste dodali vozilo', 'flash_success');
				$this->redirect(['action' => 'index']);
			}
		}
	}

	public function edit($vm_vehicle_id = null)
	{
		$this->VmVehicle->id = $vm_vehicle_id;

		$vm_vehicle = $this->VmVehicle->findById($vm_vehicle_id);




		if ($this->request->is(['post', 'put'])) {

			$this->request->data['VmVehicle']['active_from'] = $this->data['VmVehicle']['active_from']['year'] . '-' .
				$this->data['VmVehicle']['active_from']['month'] . '-' . $this->data['VmVehicle']['active_from']['day'];

			$this->request->data['VmVehicle']['active_to'] = $this->data['VmVehicle']['active_to']['year'] . '-' .
				$this->data['VmVehicle']['active_to']['month'] . '-' . $this->data['VmVehicle']['active_to']['day'];



			if ($this->VmVehicle->save($this->request->data)) {
				$this->Session->setFlash('Uspesno ste sacuvali vozilo', 'flash_success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Doslo je do greske!', 'flash_error');
				return $this->redirect($this->referer());
			}
		}




		if (!$this->request->data) {
			$this->request->data = $vm_vehicle;
		}
	}

	public function delete($vm_vehicle_id = null)
	{

		if ($this->request->is('post')) {
			if ($this->VmVehicle->delete($vm_vehicle_id)) {
				$this->Session->setFlash('Uspešno ste izbrisali vozilo', 'flash_success');
			} else {
				$this->Session->setFlash('Došlo je do greške pri brisanju vozila', 'flash_error');
			}
			$this->redirect(array('action' => 'index'));
		} else {
			throw new MethodNotAllowedException();
		}
	}
}
