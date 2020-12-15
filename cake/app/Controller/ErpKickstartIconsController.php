<?php
App::uses('AppController', 'Controller');
/**
 * ErpKickstartIcons Controller
 *
 * @property ErpKickstartIcon $ErpKickstartIcon
 * @property PaginatorComponent $Paginator
 */
class ErpKickstartIconsController extends AppController {

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
		$this->set('title_for_layout', __('MikroERP - Podešavanja - Ikonice'));

        //Set defaults
        $conditions = array();

        //Check for query
        if(!empty($this->request->query)){
            $this->request->data['ErpKickstartIcon'] = $this->request->query;

            //Check for search fields
            if(!empty($this->request->query['keywords'])){
            	$conditions['OR'][] = array('ErpKickstartIcon.icon_name LIKE' => '%'.$this->request->query['keywords'].'%');
            	$conditions['OR'][] = array('ErpKickstartIcon.icon_class LIKE' => '%'.$this->request->query['keywords'].'%');
            }
        }

        //Set data
        $settings = array();
        $settings['limit'] = 50;
        $settings['conditions'] = $conditions;
        $settings['order'] = array('ErpKickstartIcon.created' => 'DESC');
        $this->Paginator->settings = $settings;

        //Set results
		$this->ErpKickstartIcon->recursive = -1;
		$this->set('icons', $this->Paginator->paginate('ErpKickstartIcon'));
	}//~!

	/**
	 * save method
	 *
	 * @return void
	 */
	public function save($id=null) {
		$this->set('title_for_layout', __('MikroERP - Podešavanja - Ikonice - Snimanje'));

		if(!empty($id)){
			//Check if exists
			$icon = $this->ErpKickstartIcon->find('first', array('conditions' => array('ErpKickstartIcon.id' => $id), 'recursive' => -1));
			if(empty($icon)){
				$this->Session->setFlash(__("Ikonica nije validna"), 'flash_error');
				return $this->redirect(array('action' => 'index'));
			}
		}

		//Check for form submission
		if ($this->request->is('post') || $this->request->is('put')) {
			//Check if codebook is new
			if(!empty($id)){
				$this->request->data['ErpKickstartIcon']['id'] = $id;
			}

			//Save to DB			
			if($this->ErpKickstartIcon->save($this->request->data)){
				//Get user
				$user = $this->Session->read('Auth.User');				

				//Save action log        		
				$input_data = serialize($this->request->data);
	            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The ErpKickstartIcon has been saved');

	            //Set message and redirect to opened page
				$this->Session->setFlash(__('Ikonica je snimljena.'), 'flash_success');
        		return $this->redirect(array('action' => 'index'));
			} else {
				//Get error
                $errors = $this->ErpKickstartIcon->validationErrors;
                $error_msg = __('Ikonica ne može biti snimljena! Greška: ').array_shift($errors)[0];

				//Show message
				$this->Session->setFlash($error_msg, 'flash_error');
			}
		}else{
			if(!empty($icon)){
				$this->request->data['ErpKickstartIcon'] = $icon['ErpKickstartIcon'];
			}
		}
	}//~!
}