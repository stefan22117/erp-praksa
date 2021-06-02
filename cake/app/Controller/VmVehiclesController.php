<?php
App::uses('AppController', 'Controller');

class VmVehiclesController  extends AppController
{


	public function beforeFilter()
	{
		$this->Auth->allow('index', 'add', 'view');
	}

	var $name = 'VmVehicles';

	public function index(){

		$vehicles = $this->VmVehicle->find('all');
		$this->set('vehicles', $vehicles);

	}

	public function view($id = null){


		$this->loadModel('VmVehicle');
		$vehicle = $this->VmVehicle->findById($id);
		$this->set('vehicle', $vehicle);
		
		
		$this->loadModel('VmCrossedKm');
		$this->loadModel('VmFuel');
		$kms = $this->VmCrossedKm->findAllByVmVehicleId($id);
		$fuels = $this->VmFuel->findByVmVehicleId($id);
		// $kms = $this->VmVehicle->findById($id);
		$this->set('kms', $kms);
		$this->set('fuels', $fuels);
		
	}

}
