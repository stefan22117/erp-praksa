<?php
App::uses('AppController', 'Controller');

class VmVehiclesController  extends AppController
{


	public function beforeFilter()
	{
		$this->Auth->allow('index', 'add', 'view', 'edit', 'delete', 'save');
		$colors = [
			'red',
			'green',
			'blue',
			'black',
			'white',
			'yellow',
			'brown',
			'orange',
		];
		$this->set('colors', $colors);
	}

	var $name = 'VmVehicles';
	public $components = ['Paginator'];
	public $uses = [
		'VmVehicle',
		'VmRegistration',
		'VmChangeLog',
		'VmCrossedKm',
		'VmFuel',
		'VmRepair',
		'VmMaintenance',
		'VmDamage',

		'HrWorker'
	];



	public function index()
	{
		$this->set('title_for_layout', __('Vozila - MikroERP'));

		// $hr_workers = array_map(function ($hr_worker) {
		// 	return $hr_worker['HrWorker']['id'] = $hr_worker['HrWorker']['first_name'];
		// }, $this->HrWorker->find('all'));
		// $this->set('hr_workers', $hr_workers);


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
			'fields'=>'DISTINCT VmVehicle.*'

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
		}
		
		
		if ($this->request->is('post') || $this->request->is('put')) {





			$this->request->data['VmVehicle']['reg_number'] = strtoupper($this->request->data['VmVehicle']['reg_number']);
			if ($this->VmVehicle->save($this->request->data)) {

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



		$vm_vehicle = $this->VmVehicle->find('first', array(
			'conditions' => array('VmVehicle.id = ' => $vm_vehicle_id),
			'recursive' => 2
		));

		$vm_crossed_kms = $this->VmCrossedKm->find('all', array(
			'conditions' => array('VmCrossedKm.vm_vehicle_id = ' => $vm_vehicle_id),
			'recursive' => 2
		));

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
		$this->set('vm_max_crossed_km', $vm_max_crossed_km);


		$this->set('vm_crossed_kms', $vm_crossed_kms);
		$this->set('vm_vehicle', $vm_vehicle);
		return;


		$kms = $this->VmCrossedKm->findAllByVmVehicleId($vm_vehicle_id);
		$vm_damages = $this->VmDamage->findAllByVmVehicleId($vm_vehicle_id);
		$fuels = $this->VmFuel->findByVmVehicleId($vm_vehicle_id);





		$vm_repairs_ids = [];
		foreach ($vm_damages as $vm_damage) {
			if (isset($vm_damage['VmRepair']) && count($vm_damage['VmRepair'])) {
				foreach ($vm_damage['VmRepair'] as $vm_repair) {
					$vm_repairs_ids[] = $vm_repair['id'];
				}
			}
		}

		$vm_repairs = $this->VmRepair->find('all', ['conditions' => ['VmRepair.id' => $vm_repairs_ids]]);

		$vm_maintenances = $this->VmMaintenance->findAllByVmVehicleId($vm_vehicle_id);




		$this->set('vm_damages', $vm_damages);


		$this->set('vm_repairs', $vm_repairs);


		$this->set('vm_maintenances', $vm_maintenances);
		$this->set('kms', $kms);
		$this->set('fuels', $fuels);
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

			//bio je neki problem sa active_from, da li na frontendu da imam hidden polje koje generise 
			//dddd-dd-dd? onda ove 4 linije nisu potrebne

			//KAZE DA JE DOSLO DO GRESKE???
			if ($this->VmVehicle->save($this->request->data)) {
				$this->Session->setFlash('Uspesno ste sacuvali vozilo', 'flash_success');
			} else {

				$this->Session->setFlash('Doslo je do greske!', 'flash_error');
			}

			// return $this->redirect(['action'=>'index']);


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
