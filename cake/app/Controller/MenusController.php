<?php
class MenusController extends AppController{
	var $name = 'Menus';
	public $components = array('Session', 'ErpLogManagement', 'RequestHandler', 'Ctrl', 'Paginator');
	public $helpers = array('Js' => array('Jquery'));

	public function beforeFilter() {
	    parent::beforeFilter();
	    //$this->Auth->allow();
	}//~!

	public function index(){
		$this->set('title_for_layout', 'MikroERP - List Menus');
	    $this->set('menus', $this->Menu->find('all'));
	    $this->set('parents', $this->Menu->find('all', array('fields' => array('Menu.id', 'Menu.title'), 'recursive' => -1)));
	}//~!

	/**
		* Add menu element
		* @param nothing
		* @return nothing
		* @throws nothing
	*/
	public function add($id = NULL){
		$this->disableCache();
		$this->set('title_for_layout', 'MikroERP - Add menu');
		$this->set('id', $id);

	    if ($this->request->is('post')) {
	        $this->Menu->create();

	        if ($this->Menu->save($this->request->data)) {
	            $this->Session->setFlash('The group has been saved', 'flash_success');
	            $user = $this->Session->read('Auth.User');
				$input_data = serialize($this->request->data);
				$this->ErpLogManagement->erplog($user['id'], $this->params['controller'], $this->params['action'], $input_data, 'form', 'The menu has been added');

				//Set defaults for index
	    		$this->set('menus', $this->Menu->find('all'));
	    		$this->set('parents', $this->Menu->find('all', array('fields' => array('Menu.id', 'Menu.title'), 'recursive' => -1)));

	    		// Clearing cache
	    		Cache::clear(false, 'menus');

				//Set index render because it updates user container
				$this->autoRender = false;
				$this->render('index');

	        	//return $this->redirect(array('action' => 'index'));
	        }else{
	        	$this->Session->setFlash('The menu could not be saved. Please, try again.', 'flash_error');
	        }	        

	        //return $this->redirect(array('action' => 'index'));
		}
		$this->set('groups', $this->Menu->Group->find('list', array('fields' => 'name')));
		$this->set('users', $this->Menu->User->find('list', array('fields' => 'username')));
		$menu_parents = $this->Menu->find('all', array('fields' => array('Menu.id', 'Menu.title', 'Group.name'), 'conditions' => array('Menu.parent_id' => NULL), 'recursive' => 0));

		$list_menu_parents = array();
		foreach ($menu_parents as $menu_parent) {
			$list_menu_parents[$menu_parent['Menu']['id']] = $menu_parent['Menu']['title']." (".$menu_parent['Group']['name'].")";
		}

		$this->set('menuparents', $list_menu_parents);

		$this->set('menus', $this->Menu->find('all'));

	    $aControllers = $this->Ctrl->get();
	    $this->set('controllers', $aControllers);
	    $this->set('menuelements', $this->MenuElement->find('all'));
	}//~!

	/**
		* Edit menu element
		* @param int $id
		* @return nothing
		* @throws nothing
	*/
	public function edit($id = NULL){
		if($this->Menu->read(NULL, $id)){
			$this->set('title_for_layout', 'MikroERP - Edit menu title');

			$this->set('groups', $this->Menu->Group->find('list', array('fields' => 'name')));
			$this->set('users', $this->Menu->User->find('list', array('fields' => 'username')));
			$menu_parents = $this->Menu->find('all', array('fields' => array('Menu.id', 'Menu.title', 'Group.name'), 'conditions' => array('Menu.parent_id' => NULL), 'recursive' => 0));
			$list_menu_parents = array();
			foreach ($menu_parents as $menu_parent) {
				$list_menu_parents[$menu_parent['Menu']['id']] = $menu_parent['Menu']['title']." (".$menu_parent['Group']['name'].")";
			}
			$this->set('menuparents', $list_menu_parents);

		    $aControllers = $this->Ctrl->get();
		    $this->set('controllers', $aControllers);
		    $this->set('menuelements', $this->MenuElement->find('all'));
		    $this->set('action', $this->Menu->find('first', array('fields' => 'action', 'conditions' => array('Menu.id' => $id))));

			if(empty($this->data))
			{
				$this->data = $this->Menu->read(NULL, $id);
			}
			else
			{
	            // abort if cancel button was pressed      
	        	if (isset($this->params['data']['Cancel'])) {
	                $this->Session->setFlash('Changes were not saved. User cancelled.', 'flash_error');
	                $this->redirect(array('action' => 'index'));
	            }
	            // save if data is correct
				elseif($this->Menu->save($this->data))
				{
					$this->Session->setFlash('The menu title has been updated', 'flash_success');
					$user = $this->Session->read('Auth.User');
					$input_data = serialize($this->request->data);
					$this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The menu has been edited');

					// Clearing cache
	    			Cache::clear(false, 'menus');
					
					$this->set('menus', $this->Menu->find('all'));
	   				$this->set('parents', $this->Menu->find('all', array('fields' => array('Menu.id', 'Menu.title'), 'recursive' => -1)));
					
					$this->autoRender = false;
					$this->render('index');
				}
			}
		}else{
			$this->Session->setFlash(__('The menu does not exist.'), 'flash_error');
			return $this->redirect('/');
		}
	}//~!

	/**
		* Delete menu and his elements
		* @param int $id
		* @return nothing
		* @throws nothing
	*/
	public function delete($id = NULL) {
		$this->disableCache();
		$user = $this->Session->read('Auth.User');
	    $input_data = serialize($this->Menu->find('all', array('recursive' => -1, 'conditions' => array('Menu.id' => $id))));
	    $this->ErpLogManagement->erplog($user['id'], $this->params['controller'], $this->params['action'], $input_data, 'parameters', 'The manu has been deleted');

	    //Delete user
		if($this->Menu->delete($id)){
			$this->Session->setFlash(__('The menu has been deleted'), 'flash_success');

			// Clearing cache
			Cache::clear(false, 'menus');
			
			$this->ErpLogManagement->erplog($user['id'], $this->params['controller'], $this->params['action'], $input_data, 'parameters', 'The menu has been deleted');
		}else $this->Session->setFlash('The menu could not be deleted. Please, try again.', 'flash_error');		        		        

		//Get list of users
	    $this->Menu->recursive = 0;
	    $this->set('menus', $this->paginate());

		//Set index render because it updates user container
		$this->autoRender = false;
		$this->render('index');	
	}//~!

	public function menu(){
		$this->set('title_for_layout', 'MikroERP - Menu');
	}

  	/**
    	* Get methods by selected controller
    	* @param nothing
    	* @return nothing
    	* @throws new NotFoundException if request is no ajax
  	*/
 	public function getByController() {
	    $result = array();
	    if ($this->request->is('ajax')) {
	      	$this->disableCache();
	      	$controllers = $this->Ctrl->get();
	      	foreach ($controllers as $controller => $methods) {
		        if($controller == $_REQUEST['controller']){
	          		$result = $methods;
	          		break;
	        	}
	      	}
 	        $this->set('result', $result);
	        $this->set('_serialize', 'result');
	    }else{
	      throw new NotFoundException('Page not found');
	    }
  	}//~!

  	/**
		* Search data
		* @param nothing
		* @return nothing
		* @throws nothing
  	*/
  	public function search(){
		$this->disableCache();
		if(!empty($this->data)){
	      	$value = $this->data['Menu']['name'];
	      	$this->Session->write('keyword', $value);
	      	$this->Paginator->settings = array(
		        'limit' => 20,
		        'conditions' => array('OR' => array('Menu.created LIKE' => '%'.$value.'%', 'Menu.id LIKE' => '%'.$value.'%', 'Menu.title LIKE' => '%'.$value.'%' , 'Menu.modified LIKE' => '%'.$value.'%', 'Menu.controller LIKE' => '%'.$value.'%', 'Menu.action LIKE' => '%'.$value.'%', 'Group.name LIKE' => '%'.$value.'%'  ))
	      	);
	        $data = $this->paginate('Menu');
	        $this->set('menus', $data);
	        
	        $this->autoRender = false;
	        $this->render('search');

	    }
	    else{
	      	$value = $this->Session->read('keyword');
	      	$this->Paginator->settings = array(
		        'limit' => 20,
		        'order' => 'Menu.created DESC',
		        'conditions' => array('OR' => array('Menu.created LIKE' => '%'.$value.'%', 'Menu.id LIKE' => '%'.$value.'%', 'Menu.title LIKE' => '%'.$value.'%' , 'Menu.modified LIKE' => '%'.$value.'%', 'Menu.controller LIKE' => '%'.$value.'%', 'Menu.action LIKE' => '%'.$value.'%', 'Group.name LIKE' => '%'.$value.'%'  ))
	      	);
	        $data = $this->paginate('Menu');
	        $this->set('menus', $data);
	        
	        $this->autoRender = false;
	        $this->render('search');
	      
	    }
	}//~!

}