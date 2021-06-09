<?php
App::uses('AppController', 'Controller');
class VmregistrationsController extends AppController
{
    public function beforeFilter()
    {
        $this->Auth->allow('index', 'add', 'view');
    }

    public $uses = [
        'HrWorker',
        'VmVehicle',
        'VmCompany',
        'VmRegistration',
        'VmRegistrationFile'
    ];

    public function index($vm_vehicle_id = null)
    {
        $vm_registrations = [];
        if ($vm_vehicle_id) {
            $vm_registrations = $this->VmRegistration->find('all', ['conditions' => [
                'VmRegistration.vm_vehicle_id' => $vm_vehicle_id
            ]]);
        } else {
            $vm_registrations = $this->VmRegistration->find('all');
        }


        $this->set('vm_registrations', $vm_registrations);
    }

    public function add($vehicle_id = null)
    {
        $vehicle = $this->VmVehicle->findById($vehicle_id);
        $this->set('vehicle', $vehicle);

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

        if ($this->request->is('post')) {
            $this->set('podatak', $this->data['VmRegistration']);
            $this->render('index');
        }
    }






    public function view($vm_registration_id)
    {
        $vm_registration = $this->VmRegistration->findById($vm_registration_id);

        $vm_registration_files = $this->VmRegistrationFile->findAllByVmRegistrationId($vm_registration_id);

        $vm_vehicle = $this->VmVehicle->findById($vm_registration['VmVehicle']['id']);
        $vm_registration = $this->VmRegistration->findById($vm_registration['VmRegistration']['id']);
        
        for ($i = 0; $i<count($vm_registration['VmRegistrationFile']);$i++) {
            $vm_registration_files[$i]['VmVehicle'] = $vm_vehicle['VmVehicle'];
            $vm_registration_files[$i]['VmCompany'] = $vm_registration['VmCompany'];
        }

        
        
        $this->set('vm_registration_files', $vm_registration_files);
        $this->set('vm_registration', $vm_registration);
    }
}
