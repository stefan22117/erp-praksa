<?php
App::uses('AppController', 'Controller');

class VmDamagesController  extends AppController
{
    public function beforeFilter()
    {
        $this->Auth->allow('index', 'add', 'view', 'edit');
    }


    public function index()
    {

        $vm_damages = $this->VmDamage->find('all');
        $this->set('vm_damages', $vm_damages);
    }

    public function view($vm_damage_id = null)
    {

        $vm_damage = $this->VmDamage->findById($vm_damage_id);
        $this->set('vm_damage', $vm_damage);
    }

    public function viewByVmVehicleId($vm_vehicle_id)
    {

    }

    public function add($vm_vehicle_id = null)
    {

        if ($this->request->is('post')) {
            $this->request->data['VmDamage']['date'] = $this->data['VmDamage']['date']['year'] . '-' .
                $this->data['VmDamage']['date']['month'] . '-' . $this->data['VmDamage']['date']['day'];


            $vm_damage = ['VmDamage' => [
                'responsible' => $this->request->data['VmDamage']['responsible'],
                'description' => $this->request->data['VmDamage']['description'],
                'date' => $this->request->data['VmDamage']['date'],
                'vm_vehicle_id' => $vm_vehicle_id
            ]];

            if($this->VmDamage->save($vm_damage)){
                $this->Session->setFlash('Uspesno ste ubelezili stetu', 'flash_success');
                
            }
            else{
                $this->Session->setFlash('Doslo je do greske pri dodavanju stete', 'flash_error');
                
            }
            $this->redirect(['controller'=>'vmvehicles', 'action'=>'view', $vm_vehicle_id]);
        }
    }
}
