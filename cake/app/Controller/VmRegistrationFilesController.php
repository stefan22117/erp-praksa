<?php
App::uses('AppController', 'Controller');
class VmRegistrationFilesController extends AppController
{
    public function beforeFilter()
    {
        parent::beforeFilter();
       
        if (
            strtolower($this->request['action']) == 'view' ||
            strtolower($this->request['action']) == 'delete'
        ) {
            $vm_registration_file_id = !empty($this->request['pass']) ? $this->request['pass'][0] : 0;

            if ($vm_registration_file_id) {
                $vm_registration_file = $this->VmRegistrationFile->findById($vm_registration_file_id);
                if (empty($vm_registration_file)) {
                    $this->Session->setFlash(__('Traženi registracioni fajl nije pronađen'), 'flash_error');
                    return $this->redirect(array('controller' => 'vmRegistrationFiles', 'action' => 'index'));
                }
            } else {
                $this->Session->setFlash(__('Niste prosledili id registracionog fajla'), 'flash_error');
                return $this->redirect(array('controller' => 'vmVehicleFiles', 'action' => 'index'));
            }
        }
    }

    public $components = array('Paginator');

    public $uses = array(
        'VmRegistrationFile',
        'HrWorker',
        'VmVehicle',
        'VmCompany',
        'VmRegistration'
    );


    public function index()
    {
        $this->set('title_for_layout', __('Registracioni fajlovi - MikroERP'));

        $vm_vehicles = $this->VmVehicle->find(
            'list',
            array('fields' =>
            array('VmVehicle.id', 'VmVehicle.brand_and_model'))
        );
        $this->set('vm_vehicles', $vm_vehicles);

        $conditions = array();
        $joins = array();


        if (isset($this->request->query['keywords']) && $this->request->query['keywords'] != '') {
            $keywords = $this->request->query['keywords'];

            $conditions['OR']['VmRegistrationFile.title LIKE'] = '%' . $keywords . '%';

            $this->request->data['keywords'] = $keywords;
        }


        if (isset($this->request->query['vm_vehicle_id'])  && $this->request->query['vm_vehicle_id'] != '') {
            $vm_vehicle_id = $this->request->query['vm_vehicle_id'];

            $conditions[] = array(
                'VmRegistrationFile.vm_registration_id = VmRegistration.id',
                'VmRegistration.vm_vehicle_id =' => $vm_vehicle_id
            );

            $this->request->data['vm_vehicle_id'] = $vm_vehicle_id;
        }

        $options = array(
            'joins' => $joins,
            'conditions' => $conditions,
            'recursive' => 2,
            'limit' => 5
        );

        $this->Paginator->settings = $options;

        $this->set('vm_registration_files', $this->Paginator->paginate());
    }

    public function view($vm_registration_file_id = null)
    {
        $vm_registration_file =  $this->VmRegistrationFile->findById($vm_registration_file_id);

        if (!file_exists(WWW_ROOT . 'img' . DS . $vm_registration_file['VmRegistrationFile']['path'])) {
            $this->Session->setFlash('Traženi registracioni fajl nije pronađen u folderu', 'flash_error');
            return $this->redirect(array('action'=>'index'));
        } else {
            $this->set('vm_registration_file', $vm_registration_file);
        }


    }

    public function add($vm_registration_id = null)
    {

        if ($vm_registration_id == null) {
            $vm_vehicles = $this->VmVehicle->find(
                'list',
                array(
                    'fields' =>
                    array('VmVehicle.id', 'VmVehicle.brand_and_model')
                )
            );
            $this->set('vm_vehicles', $vm_vehicles);

            $vm_registrations = $this->VmRegistration->find(
                'list',
                array(
                    'fields' =>
                    array('VmRegistration.id', 'VmRegistration.registration'),
                    'order' => 'VmRegistration.registration DESC'
                )
            );
            $this->set('vm_registrations', $vm_registrations);
        }
        
        if ($this->request->is('post')) {
            
            $vm_registration_id = $vm_registration_id == null ?
                $this->data['VmRegistrationFile']['vm_registration_id']
                : $vm_registration_id;


            $file = $this->request->data['VmRegistrationFile']['file'];

            if (!$file['name']) {
                $new_name = '';
            } else {
                $ext = strtolower(pathinfo($file['name'])['extension']);
                $new_name = $vm_registration_id . '-' .  time() . '.' . $ext;
            }


            $vm_registration_file = [
                'VmRegistrationFile' => [
                    'title' => $this->request->data['VmRegistrationFile']['title'],
                    'path' => 'registration_files/' . $new_name,
                    'vm_registration_id' => $vm_registration_id
                ]
            ];

            $this->VmRegistrationFile->create();
            if ($vm_registration_file = $this->VmRegistrationFile->save($vm_registration_file)) {
                $vm_registration_file = $this->VmRegistrationFile->findById($vm_registration_file['VmRegistrationFile']['id']);
                $this->VmChangeLog->saveVmVehicleLog($this->VmRegistrationFile, $vm_registration_file['VmRegistration']['vm_vehicle_id'], $this->Session, $this->Auth);
       
                move_uploaded_file($file['tmp_name'], '../webroot/img/registration_files/' . $new_name);
                $this->Session->setFlash('Uspesno ste dodali registracioni dokument "' . $vm_registration_file['VmRegistrationFile']['title'] . '".', 'flash_success');
                if (
                    strpos(strtolower($this->referer()), 'vmregistrations') !== false ||
                    strpos(strtolower($this->referer()), 'vm_registrations') !== false
                ) {
                    return $this->redirect($this->referer() . '#tab_vm_registration_files');
                }
                $this->redirect(array('action' => 'index'));
           
            } else {
                $this->Session->setFlash('Greska sa bazom', 'flash_error');
                $this->Session->write('errors.VmRegistrationFile', $this->VmRegistrationFile->validationErrors);

                $this->Session->write(
                    'errors.data',
                    $this->request->data
                );

                if (
                    strpos(strtolower($this->referer()), 'vmregistrations') !== false ||
                    strpos(strtolower($this->referer()), 'vm_registrations') !== false
                ) {
                    return $this->redirect($this->referer() . '#tab_vm_registration_files');
                }
            }

            
        }
    }
    public function delete($vm_registration_file_id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $vm_registration_file = $this->VmRegistrationFile->findById($vm_registration_file_id);

        if ($this->VmRegistrationFile->delete($vm_registration_file_id)) {
            $this->VmChangeLog->saveVmVehicleLog($this->VmRegistrationFile, $vm_registration_file['VmRegistration']['vm_vehicle_id'], $this->Session, $this->Auth);
       
            if (file_exists(WWW_ROOT . 'img' . DS . $vm_registration_file['VmRegistrationFile']['path'])) {
                unlink(WWW_ROOT . 'img' . DS . $vm_registration_file['VmRegistrationFile']['path']);
            }
            $this->Session->setFlash('Uspesno ste izbrisali registracioni fajl', 'flash_success');
        } else {
            $this->Session->setFlash('Greska sa bazom podataka', 'flash_error');
        }

        $this->redirect(array('action' => 'index'));
    }

    public function download($vm_registration_file_id = null)
    {
        $registration_file = $this->VmRegistrationFile->findById($vm_registration_file_id);
        if (!$registration_file) {
            $this->Session->setFlash('Nema izabranog fajla u bazi', 'flash_error');
            return $this->redirect($this->referer());
        }

        $filePath = WWW_ROOT . 'img' . DS . $registration_file['VmRegistrationFile']['path'];


        $ext = pathinfo($registration_file['VmRegistrationFile']['path'])['extension'];
        $name = $registration_file['VmRegistrationFile']['title'] . '.' . $ext;
        if (file_exists($filePath)) {
            $this->response->file($filePath, ['download' => true, 'name' => $name]);
            return $this->response;
        } else {
            $this->Session->setFlash('Nema izabranog fajla u folderu', 'flash_error');
            return $this->redirect($this->referer());
        }
    }

    public function reloadRegistrations(){
        if ($this->request->is('ajax')) {
            $vm_vehicle_id = $this->request->data['vm_vehicle_id'];
            $conditions = array();
            if ($vm_vehicle_id != null) {
                $conditions = array('VmRegistration.vm_vehicle_id' => $vm_vehicle_id);
            }


            $vm_registrations = $this->VmRegistration->find(
                'list',
                array(
                    'conditions' => $conditions,
                    'fields' => array('VmRegistration.id', 'VmRegistration.registration')
                )
            );

            $this->set('vm_registrations', $vm_registrations);
            $this->set('_serialize', 'vm_registrations');
        } 
    }
}
