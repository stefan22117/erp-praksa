<?php
class GroupsController extends AppController {
	var $name = 'Groups';
	public $components = array('Session', 'Acl', 'Cookie', 'Ctrl', 'RequestHandler', 'ErpLogManagement', 'Paginator');
	public $paginate = array('limit' => 20);

	public function beforeFilter() {
		parent::beforeFilter();
	}//~!

	/**
		* List all groups from database
		* @param nothing
		* @return nothing
		* @throws nothing
	*/
	public function index() {
		$this->set('title_for_layout', 'MikroERP - List groups');
		$this->Paginator->settings = $this->paginate;
	    $this->set('groups', $this->paginate('Group'));
	}//~!

	/**
		* Add group to database
		* @param nothing
		* @return nothing
		* @throws nothing
	*/
	public function add() {
		$this->set('title_for_layout', 'MikroERP - Add new group');
		$this->disableCache();
		//Get groups
		$this->set('groups', $this->Group->find('list', array('fields' => 'name')));

	    if ($this->request->is('post')) {
	        $this->Group->create();

	        if ($this->Group->save($this->request->data)) {
	        	$this->Session->setFlash('The group has been saved.', 'flash_success');

        		$user = $this->Session->read('Auth.User');
				$input_data = serialize($this->request->data);
	            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The group has been added');
	            $this->Paginator->settings = $this->paginate;
	   			$this->set('groups', $this->paginate('Group'));

				//Set index render because it updates user container
				$this->autoRender = false;
				$this->render('index');
	            }else{
		            $this->Session->setFlash('The group could not be saved. Please, try again.', 'flash_error');
	        	}
			}
	}//~!

	/**
		* Edit group
		* @param int $id of group
		* @return nothing
		* @throws nothing
	*/
	public function edit($id = NULL){
		if(!empty($id))
			$this->Group->id = $id;
		else
			$this->Group->id = $this->request->data['Group']['id'];

		if($this->Group->read(NULL, $id)){
			$this->set('title_for_layout', 'MikroERP - Edit group name');
			if(empty($this->data))
			{
				$this->data = $this->Group->read(NULL, $id);
				$this->set('id', $id);
			}
			else
			{
	            // save if data is correct
				if($this->Group->save($this->data))
				{
					$user = $this->Session->read('Auth.User');
					$input_data = serialize($this->data);
					$this->ErpLogManagement->erplog($user['id'], $this->params['controller'], $this->params['action'], $input_data, 'form', 'User edited group');
					$this->Session->setFlash('The group name has been updated', 'flash_success');

					$this->Paginator->settings = $this->paginate;
		   			$this->set('groups', $this->paginate('Group'));

					//Set index render because it updates user container
					$this->autoRender = false;
					$this->render('index');
				}
			}
		}else{
			$this->Session->setFlash(__('The group does not exist.'), 'flash_error');

			$this->Paginator->settings = $this->paginate;
   			$this->set('groups', $this->paginate('Group'));
			$this->autoRender = false;
			$this->render('index');
		}
	}//~!

	/**
	 * View group members
	 * @param int $id of group
	 * @throws nothing
	 * @return none
	*/
	public function view($id = NULL){
		$this->__exist($id);

		$users = $this->Group->User->find('all', array('recursive' => -1, 'conditions' => array('User.group_id' => $id)));
		$this->set('group_name', $this->Group->field('name', array('id' => $id)));
		$this->set('users', $users);
	}//~!

	/**
		* Delete group
		* @param int $id of group
		* @return nothing
		* @throws NotFoundException if request is not ajax
	*/
	public function delete($id = NULL) {
		if ($this->request->is('ajax')) {
		    $this->disableCache();
			$user = $this->Session->read('Auth.User');
		    $input_data = serialize($this->Group->find('all', array('recursive' => -1, 'conditions' => array('Group.id' => $id))));
		    //Delete user
			if($this->Group->delete($id)){
				$this->Session->setFlash(__('The group has been deleted'), 'flash_success');
				
				$this->ErpLogManagement->erplog($user['id'], $this->params['controller'], $this->params['action'], $input_data, 'parameters', 'The group has been deleted');
			}else $this->Session->setFlash('The group could not be deleted. Please, try again.', 'flash_error');

			//Get list of groups
		    $this->Paginator->settings = $this->paginate;
	   		$this->set('groups', $this->paginate('Group'));

			//Set index render because it updates user container
			$this->autoRender = false;
			$this->render('index');
		}else{
			throw new NotFoundException('Page not found! Please, try different location.');
		}
	}//~!

	/**
		* Set Permissions for Groups of Users
		* @param int #group_id of group
		* @return nothing
		* @throws nothing
	*/
  	public function grouppermissions($group_id = NULL) {
  		$input_data = '';
		$this->set('groups', $this->Group->find('all'));
		$this->set('title_for_layout', 'MikroERP - Edit group permissions');


		$group = $this->Group->read(NULL, $group_id);
       	if(!empty($group)){       	
       		$this->set('group', $group);
			$aControllers = $this->Ctrl->get();
       		$this->set('controllers', $aControllers);

       		if($this->request->is('post')) {
				$sel_controller = $this->data['Group']['controller'];
				$current_controller = $this->Ctrl->getControllerByName($sel_controller);
				$controller = str_replace('Controller', '', $current_controller['name']);

				foreach($current_controller['methods'] as $method){
					$group = $this->Group;
					$permission = 'controllers/' . $controller . '/' . $method;
					$group->id = $group_id;

					if(isset($this->params['data'][$method])){
						$this->Acl->allow($group, $permission);
						$input_data .= 'allow:' . $permission . '; ';
					}
					else{
						$this->Acl->deny($group, $permission);
						$input_data .= 'deny:' . $permission . '; ';
					}
				}

				// Clear cache //
				Cache::clear(false, 'menus');

				$input_data .= 'group_id:' . $group_id;
				$user = $this->Session->read('Auth.User');
				$this->ErpLogManagement->erplog($user['id'], $this->params['controller'], $this->params['action'], $input_data, 'parameters', 'The group permissions has been changed');

				$this->Session->setFlash('The permissions for this controller has been changed', 'flash_success');
       		}
       		$this->set('result', true);
       	}
	}//~!

	/**
		* Check permissions for selected group
		* @param nothing
		* @return nothing
		* @throws NotFoundException if request is not ajax
	*/
	public function getByControllerAndGroup() {
		$result = array();
		if ($this->request->is('ajax')) {
			$this->disableCache();

			$controller_name = $_REQUEST['controller'];
			$group_id = $_REQUEST['group_id'];

			if(!empty($controller_name))
			{
				$current_controller = $this->Ctrl->getControllerByName($controller_name);

	   			$method_count = 0;
	   			$controller = str_replace('Controller', '', $current_controller['name']);
	   			foreach($current_controller['methods'] as $method){
	   				$group = $this->Group;
	      			$permission = 'controllers/' . $controller . '/' . $method;
	      			$group->id = $group_id;

	      			$result[$method_count]['method'] = $method;
	      			$result[$method_count]['allowed'] = $this->Acl->check($group, $permission);
	      			$method_count++;
	   			}			

				$this->set('result', $result);
				$this->set('_serialize', 'result');
			}
			else
			{
				$this->set('result', 0);
				$this->set('_serialize', 'result');
			}
		}else{
			throw new NotFoundException('Page not found');
		}
	}//~!


	/**
		* Get permissions for selected group
		* @param int $group_id
		* @return nothing
		* @throws NotFoundException if request not ajax or group_id is not set
	*/
	public function getPermissions($group_id = 0) {    
	    if (/*$this->request->is('ajax') && */ !empty($group_id)) {
	    	$result = array();
	        $this->disableCache();
	        $controllers = $this->Ctrl->get();
	        $controller_count = 0;
	        foreach ($controllers as $controller => $methods) {
	        	$result[$controller_count]['controller_name'] = $controller;
	        	$method_count = 0;
				foreach($methods as $method) {
					$result[$controller_count]['methods'][$method_count]['method'] = $method;				

					$group = $this->Group;
					$controller = str_replace('Controller', '', $controller);
					$permission = 'controllers/' . $controller . '/' . $method;
					$group->id = $group_id;

					$result[$controller_count]['methods'][$method_count]['allowed'] = $this->Acl->check($group, $permission);

					$method_count++;
				}          
				$controller_count++;
	        }
	        
	        $this->set('result', $result);
	        $this->render('getPermissions', 'ajax'); 
	    }
	    else{
	      throw new NotFoundException('Page not found');
	    }
	}//~!

  	/**
    	* Get methods by selected controller
    	* @param nothing
    	* @return nothing
    	* @throws new NotFoundException if request is not ajax
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
		* Search groups
		* @param nothing
		* @return nothing
		* @throws nothing
  	*/
  	public function search(){
		$this->disableCache();
		if(!empty($this->data)){
	      	$value = $this->data['Group']['nameSearch'];
			$this->Session->write('keyword', $value);
	      	$this->Paginator->settings = array(
		        'limit' => 20,
		        'conditions' => array('OR' => array('Group.created LIKE' => '%'.$value.'%', 'Group.id LIKE' => '%'.$value.'%', 'Group.name LIKE' => '%'.$value.'%' , 'Group.modified LIKE' => '%'.$value.'%' ))
	      	);
	        $this->set('groups', $this->paginate('Group'));
	        
	        $this->autoRender = false;
	        $this->render('search');
	    }
	    else{
	    	$value = $this->Session->read('keyword');

	    	$this->Paginator->settings = array(
		        'limit' => 20,
		        'order' => 'Group.created DESC',
		        'conditions' => array('OR' => array('Group.created LIKE' => '%'.$value.'%', 'Group.id LIKE' => '%'.$value.'%', 'Group.name LIKE' => '%'.$value.'%' , 'Group.modified LIKE' => '%'.$value.'%' ))
	      	);
	        $this->set('groups', $this->paginate('Group'));
	        
	        $this->autoRender = false;
	        $this->render('search');
	    }
	}//~!

	/**
	 * Check if group exist
	 * @param int $id of group
	 * @return redirect to index page if not exist
	 * @throws nothing
	*/
	public function __exist($id = null){
		if (!$this->Group->exists($id)){
			$this->Session->setFlash('Nepostojeća grupa', 'flash_error');
			return $this->redirect(array('controller' => 'Groups', 'action' => 'index'));
		}
	}//~!

	/**
	 * Group's permissions
	 *
	 * @param int $groupId
	 * @return void
	 */
	public function permissions($groupId) {
		$data = $this->Group->getPermissions($groupId);
		foreach($data as $key => $value) {
			$this->set($key, $value);
		}
	}//~!
}
?>