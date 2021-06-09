<?php
App::uses('AppController', 'Controller');

class VmCompaniesController  extends AppController
{


    public $uses = [
        'VmCompany',
        'VmMaintenance',
        'VmRepair',
        'VmDamage'
    ];

    public function beforeFilter()
    {
        $this->Auth->allow('index', 'add', 'view', 'edit');
    }


    public function index()
    {



        $vm_companies = $this->VmCompany->find('all');
        $this->set('vm_companies', $vm_companies);
    }

    public function view($vm_company_id)
    {

        $vm_company = $this->VmCompany->findById($vm_company_id);
        $this->set('vm_company', $vm_company);

        $vm_maintenances = $this->VmMaintenance->findAllByVmCompanyId($vm_company_id);
        $this->set('vm_maintenances', $vm_maintenances);





        $vm_repairs = $this->VmRepair->findAllByVmCompanyId($vm_company_id);


        for ($i = 0; $i<count($vm_repairs);$i++) {
            $vm_damage = $this->VmDamage->findById($vm_repairs[$i]['VmRepair']['vm_damage_id']);
            $vm_repairs[$i]['VmVehicle'] = $vm_damage['VmVehicle'];
        }


        // var_dump($vm_repairs);
        // die();

        $this->set('vm_repairs', $vm_repairs);
    }
}
