<?php
App::uses('AppController', 'Controller');

class VmRepairsController  extends AppController
{


    public $uses = [
        'VmRepair',
        'VmDamage',
        'HrWorker',
        'VmCrossedKm',
        'VmCompany',
        'VmVehicle',

    ];

    public function beforeFilter()
    {
        $this->Auth->allow('index', 'add', 'view', 'edit', 'repair', 'viewByVmDamageId');
    }


    public function index()
    {

        $vm_repairs = $this->VmRepair->find('all');
        $this->set('vm_repairs', $vm_repairs);
    }

    public function view($vm_repair_id = null)
    {

        $vm_repairs = $this->VmMaintenance->findAllById($vm_repair_id);//da bi bila lista, kao u viewByDamageId gde moze biti vise popravki za 1 kvar0
        
        $vm_damage = $this->VmDamage->findById($vm_repairs[0]['VmRepair']['vm_damage_id']);

        $vm_vehicle = $this->Vmvehicle->findById($vm_damage['VmDamage']['vm_vehicle_id']);
        
        
        
        
        $this->set('vm_repairs', $vm_repairs);
        $this->set('vm_vehicle', $vm_vehicle);
    }

    public function viewByVmDamageId($vm_damage_id = null)
    {
        $vm_damage = $this->VmDamage->findById($vm_damage_id);
        $vm_vehicle = $this->VmVehicle->findById($vm_damage['VmDamage']['vm_vehicle_id']);
        
        $vm_repairs = $this->VmRepair->findAllByVmDamageId($vm_damage_id);
        

        $this->set('vm_repairs', $vm_repairs);
        $this->set('vm_vehicle', $vm_vehicle);
        
        $this->render('view');
    }

    public function add($vm_vehicle_id = null)
    {

        $hr_workers = [];
        foreach ($this->HrWorker->find('all') as $hr_worker) {
            $hr_workers[$hr_worker['HrWorker']['id']] = $hr_worker['HrWorker']['first_name'];
        }
        $this->set('hr_workers', $hr_workers);

        $vm_damages = [];
        foreach ($this->VmDamage->find('all') as $vm_damage) {
            $vm_damages[$vm_damage['VmDamage']['id']] =
            $vm_damage['VmVehicle']['brand_and_model'] . ' - '
            . $vm_damage['VmDamage']['description']; //ovde videti da se stave ... ako je ise od 10 tipa..
        }

        $this->set('vm_damages', $vm_damages);
        
        $vm_companies = [];
        foreach ($this->VmCompany->find('all') as $vm_company) {
            $vm_companies[$vm_company['VmCompany']['id']] = $vm_company['VmCompany']['name'];
        }
        $this->set('vm_companies', $vm_companies);


    }


    public function repair($vm_damage_id = null)
    {

        $vm_damage = $this->VmDamage->findById($vm_damage_id);
        $this->set('vm_damage', $vm_damage);


        $hr_workers = $this->HrWorker->find('all');
        $this->set('hr_workers', $hr_workers);



        $hr_workers = [];
        foreach ($this->HrWorker->find('all') as $hr_worker) {
            $hr_workers[$hr_worker['HrWorker']['id']] = $hr_worker['HrWorker']['first_name'];
        }
        $this->set('hr_workers', $hr_workers);

        $vm_companies = [];
        foreach ($this->VmCompany->find('all') as $vm_company) {
            $vm_companies[$vm_company['VmCompany']['id']] = $vm_company['VmCompany']['name'];
        }
        $this->set('vm_companies', $vm_companies);;

        if ($this->request->is('post')) {
            $this->set('data', $this->data);


            $vm_crossed_km = ['VmCrossedKm' => [
                'total_kilometers' => $this->request->data['VmRepair']['total_kilometers'],
                'hr_worker_id' => $this->request->data['VmRepair']['hr_worker_id'],
                'vm_vehicle_id' =>  $vm_damage['VmVehicle']['id']
            ]];

            if ($vm_crossed_km = $this->VmCrossedKm->save($vm_crossed_km)) {


                $vm_repair = [
                    'VmRepair' => [
                        'amount' => $this->request->data['VmRepair']['amount'],
                        'spent_time' => $this->request->data['VmRepair']['spent_time'],
                        'description' => $this->request->data['VmRepair']['description'],
                        'vm_damage_id' => $vm_damage['VmDamage']['id'],
                        'vm_company_id' => $this->request->data['VmRepair']['vm_company_id'],
                        'vm_crossed_km_id' => $vm_crossed_km['VmCrossedKm']['id']

                    ]
                ];
                if ($this->VmRepair->save($vm_repair)) {
                    $this->Session->setFlash('Uspesno ste popravili vozilo', 'flash_success');
                    $this->redirect(['controller' => 'vmvehicles', 'action' => 'view', $vm_damage['VmVehicle']['id']]);
                }
            }
        }
    }
}
