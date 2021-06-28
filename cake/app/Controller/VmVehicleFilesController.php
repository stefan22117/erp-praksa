<?php
App::uses('AppController', 'Controller');
class VmVehicleFilesController extends AppController
{
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('index', 'add', 'view', 'delete', 'download');

        if (
            strtolower($this->request['action']) == 'view' ||
            strtolower($this->request['action']) == 'delete'
        ) {
            $vm_vehicle_file_id = !empty($this->request['pass']) ? $this->request['pass'][0] : 0;

            if ($vm_vehicle_file_id) {
                $vm_vehicle_file = $this->VmVehicleFile->findById($vm_vehicle_file_id);
                if (empty($vm_vehicle_file)) {
                    $this->Session->setFlash(__('Traženi fajl vozila nije pronađen'), 'flash_error');
                    return $this->redirect(array('controller' => 'vmVehicleFiles', 'action' => 'index'));
                }
            } else {
                $this->Session->setFlash(__('Niste prosledili id fajla vozila'), 'flash_error');
                return $this->redirect(array('controller' => 'vmVehicleFiles', 'action' => 'index'));
            }
        }
    }

    public $components = array('Paginator');

    public $uses = array(
        'VmVehicleFile',
        'HrWorker',
        'VmVehicle',
        'VmCompany',
        'VmRegistration',
        'VmRegistrationFile'
    );

    public function index()
    {
        $this->set('title_for_layout', __('Fajlovi vozila - MikroERP'));

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

            $conditions['OR']['VmVehicleFile.title LIKE'] = '%' . $keywords . '%';

            $this->request->data['keywords'] = $keywords;
        }


        if (isset($this->request->query['vm_vehicle_id'])  && $this->request->query['vm_vehicle_id'] != '') {
            $vm_vehicle_id = $this->request->query['vm_vehicle_id'];

            $conditions[] = array(
                'VmVehicleFile.vm_vehicle_id =' => $vm_vehicle_id
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
        $this->set('vm_vehicle_files', $this->Paginator->paginate());
    }

    public function add($vm_vehicle_id = null)
    {

        if ($vm_vehicle_id == null) {
            $vm_vehicles = $this->VmVehicle->find(
                'list',
                array('fields' =>
                array('VmVehicle.id', 'VmVehicle.brand_and_model'))
            );
            $this->set('vm_vehicles', $vm_vehicles);
        }

        if ($this->request->is('post')) {

            $file = $this->request->data['VmVehicleFile']['file'];
            
            $vm_vehicle_id = $vm_vehicle_id == null ?
                $this->data['VmVehicleFile']['vm_vehicle_id']
                : $vm_vehicle_id;

            if (!$file['name']) {
                $new_name = '';
            } else {
                $ext = strtolower(pathinfo($file['name'])['extension']);
                $new_name = $vm_vehicle_id . '-' .  time() . '.' . $ext;
            }


            $vm_vehicle_file = array(
                'VmVehicleFile' => array(
                    'title' => $this->request->data['VmVehicleFile']['title'],
                    'path' => 'vehicle_files/' . $new_name,
                    'vm_vehicle_id' => $vm_vehicle_id
                )
            );


            $this->VmVehicleFile->create();
            if ($vm_vehicle_file = $this->VmVehicleFile->save($vm_vehicle_file)) {
                $this->VmChangeLog->saveVmVehicleLog($this->VmVehicleFile, $vm_vehicle_file['VmVehicleFile']['vm_vehicle_id'], $this->Session, $this->Auth);
    
                move_uploaded_file($file['tmp_name'], '../webroot/img/vehicle_files/' . $new_name);
                $this->Session->setFlash('Uspesno ste dodali dokument "' . $vm_vehicle_file['VmVehicleFile']['title'] . '".', 'flash_success');
                if (
                    strpos(strtolower($this->referer()), 'vmvehicles') !== false ||
                    strpos(strtolower($this->referer()), 'vm_vehicles') !== false
                ) {
                    return $this->redirect($this->referer() . '#tab_vm_vehicle_files');
                } else {
                    return $this->redirect(array('controller' => 'vmVehicleFiles', 'action' => 'index'));
                }
            } else {
                $this->Session->setFlash('Greska sa bazom', 'flash_error');
                $this->Session->write('errors.VmVehicleFile', $this->VmVehicleFile->validationErrors);
                $this->Session->write(
                    'errors.data',
                    $this->request->data
                );
                if (
                    strpos(strtolower($this->referer()), 'vmvehicles') !== false ||
                    strpos(strtolower($this->referer()), 'vm_vehicles') !== false
                ) {
                    return $this->redirect($this->referer() . '#tab_vm_vehicle_files');
                }
            }
        }
    }




    public function view($vm_vehicle_file_id = null)
    {
        $vm_vehicle_file =  $this->VmVehicleFile->findById($vm_vehicle_file_id);
        if (!file_exists(WWW_ROOT . 'img' . DS . $vm_vehicle_file['VmVehicleFile']['path'])) {
            $this->Session->setFlash('Traženi fajl nije pronađen u folderu', 'flash_error');
            return $this->redirect(array('action'=>'index'));
        } else {
            $this->set('vm_vehicle_file', $vm_vehicle_file);
        }
    }

    public function delete($vm_vehicle_file_id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $vm_vehicle_file = $this->VmVehicleFile->findById($vm_vehicle_file_id);

        if ($this->VmVehicleFile->delete($vm_vehicle_file_id)) {
            $this->VmChangeLog->saveVmVehicleLog($this->VmVehicleFile, $vm_vehicle_file['VmVehicleFile']['vm_vehicle_id'], $this->Session, $this->Auth);
    
            if (file_exists(WWW_ROOT . 'img' . DS . $vm_vehicle_file['VmVehicleFile']['path'])) {
                unlink(WWW_ROOT . 'img' . DS . $vm_vehicle_file['VmVehicleFile']['path']);
            }
            $this->Session->setFlash('Uspesno ste izbrisali fajl vozila', 'flash_success');
        } else {
            $this->Session->setFlash('Greska sa bazom podataka', 'flash_error');
        }

        $this->redirect(array('action' => 'index'));
    }




    public function download($vm_vehicle_file_id = null)
    {
        $vm_vehicle_file = $this->VmVehicleFile->findById($vm_vehicle_file_id);
        if (!$vm_vehicle_file) {
            $this->Session->setFlash('Nema izabranog fajla u bazi', 'flash_error');
            return $this->redirect($this->referer());
        }

        $filePath = WWW_ROOT . 'img' . DS . $vm_vehicle_file['VmVehicleFile']['path'];


        $ext = pathinfo($vm_vehicle_file['VmVehicleFile']['path'])['extension'];
        $name = $vm_vehicle_file['VmVehicleFile']['title'] . '.' . $ext;
        if (file_exists($filePath)) {
            $this->response->file($filePath, ['download' => true, 'name' => $name]);
            return $this->response;
        } else {
            $this->Session->setFlash('Nema izabranog fajla u folderu', 'flash_error');
            return $this->redirect($this->referer());
        }
    }
}
