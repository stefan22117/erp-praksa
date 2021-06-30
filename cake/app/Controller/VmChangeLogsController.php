<?php
App::uses('AppController', 'Controller');

class VmChangeLogsController  extends AppController
{


    var $name = 'VmChangeLogs';
	public $components = array('Paginator');
	public $uses = array(
		'VmChangeLog',
		'VmVehicle'
	);
    public function index()
    {
        $this->set('title_for_layout', __('Beleške promena - MikroERP'));

		$this->set('vm_vehicles', $this->VmVehicle->find('list', array(
			'fields' => array('VmVehicle.id', 'VmVehicle.brand_and_model')
		)));

        $tables = array(
            'VmVehicle',
            'VmRegistration',
            'VmRepair',
            'VmFuel',
            'VmDamage',
            'VmMaintenance',
            'VmVehicleFile',
            'VmImage',
            'VmRegistrationFile',
        );
		for ($i = 0;$i<count($tables);$i++) {
			$tables[$i] =
				substr($tables[$i], 0, 2) == 'Vm' ?
				substr($tables[$i], 2) :
				$tables[$i];
		}

		$this->set('tables', $tables);
		
		
		$actions = array(
			'added'=>'Add',
			'updated'=>'Update',
			'deleted'=>'Delete'
		);
		$this->set('actions', $actions);

		$conditions = array();
		$joins = array();




		if (isset($this->request->query['vm_vehicle_id'])  && $this->request->query['vm_vehicle_id'] != '') {
			$vm_vehicle_id = $this->request->query['vm_vehicle_id'];

            $conditions[] = array('VmChangeLog.vm_vehicle_id'=>$vm_vehicle_id); 

			$this->request->data['VmChangeLog']['vm_vehicle_id'] = $vm_vehicle_id;
		}

		if (isset($this->request->query['table_id'])  && $this->request->query['table_id'] != '') {
			$table_id = $this->request->query['table_id'];

			$conditions[]= array('VmChangeLog.description LIKE' => $tables[$table_id] .'%');

			$this->request->data['VmChangeLog']['table_id'] = $table_id;
		}

		if (isset($this->request->query['action'])  && $this->request->query['action'] != '') {
			$action = $this->request->query['action'];

            $conditions[]= array('VmChangeLog.description LIKE' => '%' . $action);

			$this->request->data['VmChangeLog']['action'] = $action;
		}

		$options = array(
			'conditions' => $conditions,
			'joins' => $joins,
			'order' => 'VmChangeLog.created DESC',
			'limit' => 5,

		);

		// Set data for view //
		$this->Paginator->settings = $options;
		$this->set('vm_change_logs', $this->Paginator->paginate());
    }

	public function delete($vm_change_log_id = null)
	{

		if(!$this->request->is('post')){
			throw new MethodNotAllowedException();
		}
		if($this->VmChangeLog->delete($vm_change_log_id)){
			$this->Session->setFlash('Uspešno ste izbrisali belešku promena', 'flash_success');
		}
		else{
			$this->Session->setFlash('Došlo je do greške sa bazom podataka', 'flash_error');
		}
		$this->redirect(array('action'=>'index'));
	}
}