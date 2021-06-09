<?php
App::uses('AppController', 'Controller');

class VmMaintenancesController  extends AppController
{

    public function beforeFilter()
	{
		$this->Auth->allow('index', 'add', 'view', 'edit');
	}


    public function index(){

        $vm_maintenances = $this->VmMaintenance->find('all');
        $this->set('vm_maintenances', $vm_maintenances);
    }

    public function view($vm_maintenance_id){

        $vm_maintenance = $this->VmMaintenance->findById($vm_maintenance_id);
        $this->set('vm_maintenances', $vm_maintenance);

    }

    public function add($vm_vehicle_id)
    {
        
    }

}