<?php
App::uses('AppController', 'Controller');
class VmImagesController extends AppController
{
    public function beforeFilter()
    {
        parent::beforeFilter();
      
        if (
            strtolower($this->request['action']) == 'view' ||
            strtolower($this->request['action']) == 'delete'
        ) {
            $vm_image_id = !empty($this->request['pass']) ? $this->request['pass'][0] : 0;

            if ($vm_image_id) {
                $vm_image = $this->VmImage->findById($vm_image_id);
                if (empty($vm_image)) {
                    $this->Session->setFlash(__('Tražena slika vozila nije pronađena'), 'flash_error');
                    return $this->redirect(array('controller' => 'vmImages', 'action' => 'index'));
                }
            } else {
                $this->Session->setFlash(__('Niste prosledili id slike vozila'), 'flash_error');
                return $this->redirect(array('controller' => 'vmImages', 'action' => 'index'));
            }
        }
    }

    public $components = array('Paginator');

    public $uses = array(
        'VmImage',
        'VmRegistrationFile',
        'VmVehicle',
        'HrWorker',
        'VmCompany',
        'VmRegistration',
    );

    public function index()
    {
        $this->set('title_for_layout', __('Slike vozila - MikroERP'));

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

            $conditions['OR']['VmImage.title LIKE'] = '%' . $keywords . '%';
            $conditions['OR']['VmVehicle.brand_and_model LIKE'] = '%' . $keywords . '%';

            $this->request->data['keywords'] = $keywords;
        }


        if (isset($this->request->query['vm_vehicle_id'])  && $this->request->query['vm_vehicle_id'] != '') {
            $vm_vehicle_id = $this->request->query['vm_vehicle_id'];

            $conditions[] = array(
                'VmImage.vm_vehicle_id =' => $vm_vehicle_id
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
        $this->set('vm_images', $this->Paginator->paginate());
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

            $file = $this->request->data['VmImage']['file'];

            $vm_vehicle_id = $vm_vehicle_id == null ?
                $this->data['VmImage']['vm_vehicle_id']
                : $vm_vehicle_id;

            if (!$file['name']) {
                $new_name = '';
            } else {
                $ext = strtolower(pathinfo($file['name'])['extension']);
                $new_name = $vm_vehicle_id . '-' .  time() . '.' . $ext;
            }


            $vm_image = array(
                'VmImage' => array(
                    'title' => $this->request->data['VmImage']['title'],
                    'path' => 'vehicle_images/' . $new_name,
                    'vm_vehicle_id' => $vm_vehicle_id
                )
            );





            $this->VmImage->create();
            if ($vm_image = $this->VmImage->save($vm_image)) {
                $this->VmChangeLog->saveVmVehicleLog($this->VmImage, $vm_image['VmImage']['vm_vehicle_id'], $this->Session, $this->Auth);
    

                move_uploaded_file($file['tmp_name'], '../webroot/img/vehicle_images/' . $new_name);

                $this->Session->setFlash('Uspesno ste dodali sliku "' . $vm_image['VmImage']['title'] . '".', 'flash_success');
                if (
                    strpos(strtolower($this->referer()), 'galery') !== false 
                ) {
                    return $this->redirect($this->referer());
                }
                $this->redirect(array('action' => 'index'));
            
            } else {
                $this->Session->setFlash('Greska sa bazom', 'flash_error');

                $this->Session->write('errors.VmImage', $this->VmImage->validationErrors);
                $this->Session->write(
                    'errors.data',
                    $this->request->data
                );
                if (
                    strpos(strtolower($this->referer()), 'galery') !== false 
                ) {
                    return $this->redirect($this->referer());
                }
                


            }

            
        }
    }




    public function view($vm_image_id = null)
    {

        $vm_image =  $this->VmImage->findById($vm_image_id);

        if (!file_exists(WWW_ROOT . 'img' . DS . $vm_image['VmImage']['path'])) {
            $this->Session->setFlash('Tražena slika nije pronađena u folderu', 'flash_error');
            return $this->redirect(array('action'=>'index'));
        } else {
            $this->set('vm_image', $vm_image);
        }
    }

    public function delete($vm_image_id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $vm_image = $this->VmImage->findById($vm_image_id);

        if ($this->VmImage->delete($vm_image_id)) {
            $this->VmChangeLog->saveVmVehicleLog($this->VmImage, $vm_image['VmImage']['vm_vehicle_id'], $this->Session, $this->Auth);
    
            if (file_exists(WWW_ROOT . 'img' . DS . $vm_image['VmImage']['path'])) {
                unlink(WWW_ROOT . 'img' . DS . $vm_image['VmImage']['path']);
            }
            $this->Session->setFlash('Uspesno ste izbrisali sliku vozila', 'flash_success');
        } else {
            $this->Session->setFlash('Greska sa bazom podataka', 'flash_error');
        }

        $this->redirect(array('action' => 'index'));
    }




    public function download($vm_image_id = null)
    {
        $vm_image = $this->VmImage->findById($vm_image_id);
        if (!$vm_image) {
            $this->Session->setFlash('Nema izabranog fajla u bazi', 'flash_error');
            return $this->redirect($this->referer());
        }

        $filePath = WWW_ROOT . 'img' . DS . $vm_image['VmImage']['path'];


        $ext = pathinfo($vm_image['VmImage']['path'])['extension'];
        $name = $vm_image['VmImage']['title'] . '.' . $ext;
        if (file_exists($filePath)) {
            $this->response->file($filePath, ['download' => true, 'name' => $name]);
            return $this->response;
        } else {
            $this->Session->setFlash('Nema izabranog fajla u folderu', 'flash_error');
            return $this->redirect($this->referer());
        }
    }

    public function galery($vm_vehicle_id = null)
    {
        $errors = array();
        //getting errors start
        if ($this->Session->read('errors')) {
            $this->request->data = $this->Session->read('errors.data');
        }
        if ($this->Session->read('errors.VmImage')) {
            $errors['Images'] = true;
            $this->VmImage->validationErrors = $this->Session->read('errors.VmImage');
        }

        $this->set('errors', $errors);
        $this->Session->delete('errors');


        //getting errors end




        if ($vm_vehicle_id) {
            $vm_vehicle = $this->VmVehicle->findById($vm_vehicle_id);
            $this->set('vm_vehicle', $vm_vehicle);

            if (!$vm_vehicle) {
                $this->Session->setFlash(__('Nije pronađeno vozilo sa prosleđenim id-jem'), 'flash_error');
                $this->redirect(array('action' => 'index'));
            }
        } else {
            $this->Session->setFlash(__('Niste prosledili id vozila'), 'flash_error');
            $this->redirect(array('action' => 'index'));
        }
    }
}
