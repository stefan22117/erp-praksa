<?php
App::uses('AppController', 'Controller');
/**
 * CodebookDocumentTypes Controller
 *
 * @property CodebookDocumentType $CodebookDocumentType
 * @property PaginatorComponent $Paginator
 */
class CodebookDocumentTypesController extends AppController {

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Session', 'Acl', 'Cookie', 'Ctrl', 'RequestHandler', 'ErpLogManagement','Paginator', 'Search', 'String');

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index() {
		$this->set('title_for_layout', __('MikroERP - Šifarnici - Vrste dokumenata'));

        //Set defaults
        $conditions = array('CodebookDocumentType.active' => 1);

        //Check for query
        if(!empty($this->request->query)){
            $this->request->data['CodebookDocumentType'] = $this->request->query;

            //Check for search fields
            if(!empty($this->request->query['keywords'])){
            	$conditions['OR'][] = array('CodebookDocumentType.code LIKE' => '%'.$this->request->query['keywords'].'%');
            	$conditions['OR'][] = array('CodebookDocumentType.name LIKE' => '%'.$this->request->query['keywords'].'%');
            }

            //Check for search fields
            if(!empty($this->request->query['show_inactive'])){
            	unset($conditions['CodebookDocumentType.active']);
            }
        }

        //Set data
        $settings = array();
        $settings['limit'] = 50;
        $settings['conditions'] = $conditions;
        $settings['order'] = array('CodebookDocumentType.created' => 'DESC');
        $this->Paginator->settings = $settings;

        //Set results
		$this->CodebookDocumentType->recursive = -1;
		$this->set('types', $this->Paginator->paginate('CodebookDocumentType'));
	}//~!

	/**
	 * save method
	 *
	 * @return void
	 */
	public function save() {
		//Set page title
		$this->set('title_for_layout', __('MikroERP - Šifarnici - Vrste dokumenata - Kreiranje'));

		//Check for form submission
		if ($this->request->is('post') || $this->request->is('put')) {
			//Check if type is new
			$this->request->data['CodebookDocumentType']['active'] = 1;

			//Save to DB
			$this->CodebookDocumentType->create();
			if($this->CodebookDocumentType->save($this->request->data)){
				//Get user
				$user = $this->Session->read('Auth.User');

				//Save action log        		
				$input_data = serialize($this->request->data);
	            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The CodebookDocumentType has been saved');

	            //Set message and redirect to opened page
				$this->Session->setFlash(__('Vrsta dokumenata je kreirana.'), 'flash_success');
        		return $this->redirect(array('action' => 'index'));
			} else {
				//Get error
                $errors = $this->CodebookDocumentType->validationErrors;
                $error_msg = __('Vrsta dokumenata ne može biti kreirana! Greška: ').array_shift($errors)[0];

				//Show message
				$this->Session->setFlash($error_msg, 'flash_error');
			}
		}
	}//~!

	/**
	 * Deactivate method
	 * @param $id - CodebookDocumentType.id
	 * @return void
	 */
	public function deactivate($id) {
		//Check if exists
		$type = $this->CodebookDocumentType->find('first', array('conditions' => array('CodebookDocumentType.id' => $id), 'recursive' => -1));
		if(empty($type)){
			$this->Session->setFlash(__("Vrsta dokumenata nije validna"), 'flash_error');
			return $this->redirect(array('action' => 'index'));
		}

		//Check if type already deactivated
		if(empty($type['CodebookDocumentType']['active'])){
			$this->Session->setFlash(__("Vrsta dokumenata je već deaktivirana"), 'flash_error');
			return $this->redirect(array('action' => 'index'));
		}

		//Deactivate document type
		$type['CodebookDocumentType']['active'] = 0;

		//Save to DB
		if($this->CodebookDocumentType->save($type)){
			//Get user
			$user = $this->Session->read('Auth.User');

			//Save action log        		
			$input_data = serialize($type);
            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The CodebookDocumentType has been deactivated');

            //Set message and redirect to opened page
			$this->Session->setFlash(__('Vrsta dokumenata je deaktivirana.'), 'flash_success');    		
		} else {
			//Get error
            $errors = $this->CodebookDocumentType->validationErrors;
            $error_msg = __('Vrsta dokumenata ne može biti deaktivirana! Greška: ').array_shift($errors)[0];

			//Show message
			$this->Session->setFlash($error_msg, 'flash_error');
		}
		return $this->redirect(array('action' => 'index'));
	}//~!
}