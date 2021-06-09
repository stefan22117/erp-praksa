<?php
App::uses('AppController', 'Controller');
class VmregistrationFilesController extends AppController
{
    public function beforeFilter()
    {
        $this->Auth->allow('index', 'add', 'view', 'delete');
    }

    public $uses = [
        'HrWorker',
        'VmVehicle',
        'VmCompany',
        'VmRegistration',
        'VmRegistrationFile'
    ];

    public function index()
    {
        $vm_registration_files = $this->VmRegistrationFile->find('all');

        for ($i = 0; $i<count($vm_registration_files);$i++) {
            $vm_vehicle = $this->VmVehicle->findById($vm_registration_files[$i]['VmRegistration']['vm_vehicle_id']);
            $vm_registration = $this->VmRegistration->findById($vm_registration_files[$i]['VmRegistration']['id']);
            
            $vm_registration_files[$i]['VmVehicle'] = $vm_vehicle['VmVehicle'];
            $vm_registration_files[$i]['VmCompany'] = $vm_registration['VmCompany'];
        }

        $this->set('vm_registration_files', $vm_registration_files);

    }

    public function view($vm_registration_file_id)
    {
        $vm_registration_file =  $this->VmRegistrationFile->findById($vm_registration_file_id);
        $this->set('vm_registration_file', $vm_registration_file);
    }

    public function add($vm_registration_id = null)
    {

        $registration = $this->VmRegistration->findById($vm_registration_id);

        $vm_vehicle_id = $registration['VmVehicle']['id'];

        $file = $this->data['VmRegistrationFile']['file'];
        // $name = strtolower(pathinfo($file['name'])['filename']);
        $ext = strtolower(pathinfo($file['name'])['extension']);


        if ($this->VmRegistrationFile->find(
            'all',
            [
                'conditions' => [
                    'VmRegistrationFile.id' => $vm_registration_id,
                    'VmRegistrationFile.title' => $this->data['VmRegistrationFile']['title']
                ]
            ]
        )) {
            $this->Session->setFlash('postoji fajl koji se ovako zove, za ovu registraciju', 'flash_error');
            $this->redirect(['controller' => 'vmregistrations', 'action' => 'view', $vm_vehicle_id]); //ovde mora id od kola
        }


        $arr_ext = array(/*'jpg', 'jpeg', 'gif', 'png',*/ 'pdf'); //pdf??
        if (!in_array($ext, $arr_ext)) {
            $this->Session->setFlash('Format nije podrzan!', 'flash_error');
            $this->redirect(['controller' => 'vmregistrations', 'action' => 'view', $vm_vehicle_id]);
        }
        $new_name = time() . '.' . $ext;
        move_uploaded_file($file['tmp_name'], '../webroot/img/registration_files/' . $new_name);
        // $this->Session->setFlash('Uspesno ste dodali dokument ' . $this->data['VmRegistrationFile']['title'] . $name, 'flash_success');
        // $this->redirect(['controller' => 'vmregistrations', 'action' => 'view', $vm_vehicle_id]);

        //sacuvaj u db regfiles


        $vm_registration_file = [
            'VmRegistrationFile' => [
                'title' => $this->data['VmRegistrationFile']['title'],
                'path' => 'registration_files/' . $new_name,
                'vm_registration_id' => $vm_registration_id
            ]
        ];




        $this->VmRegistrationFile->create();
        if ($this->VmRegistrationFile->save($vm_registration_file)) {
            $this->Session->setFlash('Uspesno ste dodali dokument ' . $this->data['VmRegistrationFile']['title'] . $this->data['VmRegistrationFile']['title'], 'flash_success');
        } else {
            $this->Session->setFlash('Greska sa bazom', 'flash_error');
        }
        $this->redirect(['controller' => 'vmregistrations', 'action' => 'view', $vm_vehicle_id]);
    }

    public function delete($vm_registration_file_id=null, $vm_vehicle_id=null)
    {

        if(!$this->request->is('post')){
            throw new MethodNotAllowedException();
        }


        if($this->VmRegistration->delete($vm_registration_file_id)){
            $this->Session->setFlash('Uspesno ste obrisali dokument', 'flash_success');
        }
        else{
            
            $this->Session->setFlash('Doslo je do greske pri brisanju dokumenta', 'flash_error');
        }
        
        //da li redirektovati na kola, ili na index za registration docs, koji pitanje da li ce postojati
        if($vm_vehicle_id){
            $this->redirect(['controller'=>'vmvehicles', 'action'=>'view', $vm_vehicle_id]);
        }
        else{
            $this->redirect(['controller'=>'vmregistrationfiles', 'action'=>'index']);
        }
    }
}
