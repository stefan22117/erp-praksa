<?php
class UsersController extends AppController {
	var $name = 'Users';
	public $components = array('Session', 'Acl', 'Cookie', 'Ctrl', 'RequestHandler', 'ErpLogManagement', 'Paginator');
	public $paginate = array('limit' => 20);

	public function beforeFilter() {	    
	    parent::beforeFilter();
	    $this->Auth->allow('login', 'logout', 'changeLanguage', 'changePassword', 'getUsersBySearch', 'isLoggedIn');
	}//~!

	/**
	 * Check if user is logged in
	 * @author Boris Urosevic - boris.urosevic@mikroe.com
	 * @since 09.02.2018
	*/
	public function isLoggedIn(){
		$this->set('result', !empty( $this->user['id'] ));
		$this->set('_serialize', 'result');
	}

	/**
		* List all users
		* @param nothing
		* @return nothing
		* @throws nothing
	*/
	public function index() {
		$this->disableCache();
		$this->set('title_for_layout', 'MikroERP - List users');

		$this->Paginator->settings = array(
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'groups',
					'alias' => 'Group',
					'conditions' => array('Group.id = User.group_id')
				),
				array(
					'table' => 'cake_sessions',
					'alias' => 'cake_session',
					'type' => 'LEFT',
					'conditions' => array(
						'cake_session.user_id = User.id'
					)
				)
			),
			'fields' => 'User.*, cake_session.id, Group.id, Group.name',
			'group' => array('User.id'),
			'limit' => 20,
			'order' => 'User.created DESC',
		);
		$data = $this->Paginator->paginate('User');
	    $this->set('users', $data);
		$this->set('show_users', true);
	}//~!

	/**
		* Add user to database
		* @param nothing
		* @return nothing
		* @throws NotFoundException if request is not ajax
	*/
	public function add() {
		if ($this->request->is('ajax')) {
		    $this->disableCache();
			//Get groups
			$this->set('groups', $this->User->Group->find('list', array('fields' => 'name')));

		    if ($this->request->is('post')) {
		    	$this->request->data['User']['worker_id'] = NULL;
		        $this->User->create();

				if ($this->User->save($this->request->data)) {
		            $this->Session->setFlash('The user has been saved', 'flash_success');

		            $user = $this->Session->read('Auth.User');
					$first_name = $this->request->data['User']['first_name'];
					$last_name = $this->request->data['User']['last_name'];
					$email = $this->request->data['User']['email'];
					$username = $this->request->data['User']['username'];
					$password = $this->request->data['User']['password'];
					$hashedPassword = Security::hash($password, NULL, true);
					$email = $this->request->data['User']['email'];
					$group_id = $this->request->data['User']['group_id'];
					$input_data = 'first_name: ' . $first_name . '; last_name: ' . $last_name . '; username: ' . $username . '; email: ' . $email . '; password: ' . $hashedPassword . '; group_id: ' . $group_id;
					
					$this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The user has been added');

					$this->Paginator->settings = $this->paginate;
				    $this->set('users', $this->paginate('User'));
					//Set index render because it updates user container
					$this->autoRender = false;
					$this->render('index');
		        }else{
		        	$errors = $this->User->validationErrors;
		        	$this->Session->setFlash(__('The user could not be saved. Please, try again.'.array_shift($errors)[0]), 'flash_error');	
		        }
			}
			}else{
				throw new NotFoundException('Stranica ne postoji.');
			}		
	}//~!

	/**
		* Edit users data
		* @param int $id
		* @return nothing
		* @throws NotFoundException if request is not ajax
	*/
	public function edit($id = NULL) {
		//Set title
		$this->set('title_for_layout', 'Izmena - Korisnici - MikroERP');

		if(!empty($id))
			$this->User->id = $id;
		else
			$this->User->id = $this->request->data['User']['id'];

		if($this->User->exists($id)){
			$this->disableCache();
			//Get groups
			$this->set('groups', $this->User->Group->find('list', array('fields' => 'name')));
			$saving = null;
			if ($this->request->is('post')) {			
				//Get previous info for postprocessing
				$old_info = $this->User->find('first', array('conditions' => array('User.id' => $id), 'recursive' => -1));

				if(empty($this->request->data['User']['password'])){
					$save_fields = array(
						'first_name', 'last_name', 'username', 'email', 
						'group_id','modified','avatar_link', 'signature_link', 'active'
					);
					$saving = $this->User->save($this->request->data, true, $save_fields);
				}else{
					if($this->User->save($this->request->data)){
						$saving = $this->request->data;
					}else{
						$this->Session->setFlash(__('The user could not be saved. Please, try again.'), 'flash_error');	
					}
				}
				
				//Check if there is old avatar
				if(!empty($old_info['User']['avatar_link']) && ($this->request->data['User']['avatar_link'] != $old_info['User']['avatar_link'])){
					unlink(WWW_ROOT.'img'.DS.$old_info['User']['avatar_link']);
				}

				//Check if there is old signature
				if(!empty($old_info['User']['signature_link']) && ($this->request->data['User']['signature_link'] != $old_info['User']['signature_link'])){
					unlink(WWW_ROOT.'img'.DS.$old_info['User']['signature_link']);
				}

				//Post saving process
				if($saving) {
					// Clear cache //
					Cache::clear(false, 'menus');
					$this->Session->setFlash('The user has been edited', 'flash_success');
					$user = $this->Session->read('Auth.User');
					$first_name = $this->request->data['User']['first_name'];
					$last_name = $this->request->data['User']['last_name'];
					$email = $this->request->data['User']['email'];
					if(!empty($username))
						$username = $this->request->data['User']['username'];
					else
						$username = '';
					$password = $this->request->data['User']['password'];
					$hashedPassword = Security::hash($password, NULL, true);
					$email = $this->request->data['User']['email'];
					if(!empty($group_id))
						$group_id = $this->request->data['User']['group_id'];
					else
						$group_id = '';

					$input_data = 'first_name: ' . $first_name . '; last_name: ' . $last_name . '; username: ' . $username . '; email: ' . $email . '; password: ' . $hashedPassword . '; email: ' . '; group_id: ' . $group_id;

					$this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The user has been edited');

					if($user['group_id'] == 1){
						
						$this->Paginator->settings = $this->paginate;
					    $this->set('users', $this->paginate('User'));
						//Set index render because it updates user container
						$this->autoRender = false;
						$this->render('index');
						
					}
					else{
						$this->autoRender = false;
						$this->render('/pages/home');
					}
				}else{
					$this->Session->setFlash('The user could not be edited. Please, try again.', 'flash_error');
					//return $this->redirect(array('action' => 'edit',$id));
				}
			}else{
				//Set user data
				$this->request->data = $this->User->find('first', array('recursive' => -1, 'conditions' => array('User.id' => $id)));
			}

			//Variable for form checking
			$this->set('show_edit', true);

			if($this->request->is('ajax')){
				$this->set('is_ajax', true);
			}else{
				$this->set('is_ajax', false);
			}
			//}else{
			//throw new NotFoundException('Page not found! Please, try different location.');
			//}
		}
		else{
			$this->Session->setFlash('The user does not exist.', 'flash_error');
			return $this->redirect(array('/'));
		}
	}//~!

	/**
	 * Change password
	*/
	public function changePassword(){
		$user_id = $this->user['id'];
		$old_password = Security::hash($_REQUEST['old_password'], NULL, true);
		$new_password = $_REQUEST['new_password'];
		$repeat_new_password = $_REQUEST['repeat_new_password'];

		try{
			$check_old_password = $this->User->field('password', array('User.id' => $user_id));
			if($check_old_password != $old_password){
				throw new Exception(__('Stara lozinka nije ispravna'));
			}

			if($new_password != $repeat_new_password){
				throw new Exception(__('Lozinke se ne poklapaju'));
			}

			$user['User']['id'] = $user_id;
			$user['User']['password'] = $new_password;
			if($this->User->save($user)){
				$result['success'] = true;
				$result['message'] = __('Lozinka je uspešno izmenjena');

				$input_data = 'password: ' .  Security::hash($_REQUEST['new_password'], NULL, true);
				$this->ErpLogManagement->erplog($this->user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'User changed his password');

			}else{
				$errors = $this->User->validationErrors;
				throw new Exception($errors['password'][0]);
			}
		}catch(Exception $e) {
			$result['success'] = false;
	        $result['message'] = $e->getMessage();
	    } 

		$this->set('result', $result);
		$this->set('_serialize', 'result');

	}//~!

	/**
		* Delete user
		* @param int $id
		* @return nothing
		* @throws NotFoundException if request is not ajax
	*/
	public function delete($id = NULL) {
		if ($this->request->is('ajax')) {
		    $this->disableCache();
			$user = $this->Session->read('Auth.User');
			$user_data = $this->User->find('first', array('recursive' => -1, 'conditions' => array('User.id' => $id)));
		    $input_data = serialize($user_data);
		    //Delete user
			if($this->User->delete($id)){
				//Delete avatar file
				if(file_exists(WWW_ROOT.'img'.DS.$user_data['User']['avatar_link'])){
					unlink(WWW_ROOT.'img'.DS.$user_data['User']['avatar_link']);
				}

				$this->Session->setFlash(__('The user has been deleted'), 'flash_success');				
				$this->ErpLogManagement->erplog($user['id'], $this->params['controller'], $this->params['action'], $input_data, 'parameters', 'The user has been deleted');
			}else $this->Session->setFlash('The user could not be deleted. Please, try again.', 'flash_error');

			//Get list of users
		    $this->User->recursive = 0;
		    $this->Paginator->settings = $this->paginate;
		    $this->set('users', $this->paginate('User'));

			//Set index render because it updates user container
			$this->autoRender = false;
			$this->render('index');			        		
		}else{
			throw new NotFoundException('Page not found! Please, try different location.');
		}
	}//~!

	/**
		* Login user
		* @param nothing
		* @return redirect to home page
		* @throws nothing
	*/
	public function login() {
		$this->set('title_for_layout', 'MikroERP - User login');

		if ( $this->Session->read('Auth.User') ){
			if ( $this->request->is('ajax') ){
				$this->set('result', true);
				$this->set('_serialize', 'result');
			}else{
				return $this->redirect('/');
			}
		}else{
			$result['success'] = true;
			$result['message'] = '';

			if($this->request->is('post')) {
				$active = $this->User->field('active', array('username' => $this->request->data['User']['username']));
				if(!$active){
					if ( $this->request->is('ajax') ){
						$result['success'] = false;
						$result['message'] = __('Korisničko ime nije aktivno.');
						$this->set('result', $result);
						$this->set('_serialize', 'result');
					}else{
						$this->Session->setFlash(__('Korisničko ime nije aktivno.'), 'flash_error');
						return $this->redirect('/');
					}
				}

				if ( $this->Auth->login() ) {
					$this->request->data['Post']['id'] = $this->Auth->user('id');
					$user = $this->Session->read('Auth.User');
					$username = $this->request->data['User']['username'];
					$password = $this->request->data['User']['password'];
					$hashedPassword = Security::hash($password, NULL, true);
					$input_data = 'username: ' . $username . '; password: ' . $hashedPassword . '; ip_address: '.$this->RequestHandler->getClientIp();
					$this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The user has been logged in');
				}else{
					$result['success'] = false;
					$result['message'] = __('Nepostojeće korisničko ime i/ili lozinka. Pokušajte ponovo.');

					$this->request->data['User']['id_address'] = $this->RequestHandler->getClientIp();
					$this->request->data['User']['password'] = Security::hash($this->request->data['User']['password'], NULL, true);
					$input_data = serialize($this->request->data);
					$this->ErpLogManagement->erplog(0,  $this->params['controller'], $this->params['action'], $input_data, 'form', 'Invalid username or password');
				}
			}
		}
		if ( $this->request->is('ajax') ){
			$this->set('result', $result);
			$this->set('_serialize', 'result');
		}else{
			if ( !$result['success'] ){
				$this->Session->setFlash( $result['message'], 'flash_error' );
			}
			return $this->redirect('/');
		}
	}//~!

	/**
		* Logout user
		* @param nothing
		* @return redirect to home page
		* @throws nothing
	*/
	public function logout() {
		$this->ErpLogManagement->erplog($this->user['id'],  $this->params['controller'], $this->params['action'], '', 'parameters', 'User logged out');
		$this->Auth->logout();
		$this->Session->setFlash('Izlogovali ste se!', 'flash_success');
	    return $this->redirect('login');
	}//~!

	/**
		* Set Permissions for users
		* @param int $id of user
		* @return nothing
		* @throws nothing
	*/
	public function userpermissions($user_id = NULL){
		$this->disableCache();
		$this->set('title_for_layout', 'MikroERP - Edit user permissions');

		//$user = $this->User->read(NULL, $user_id);
		$user = $this->User->find('first', array('recursive' => -1, 'conditions' => array('User.id' => $user_id)));

       	if(!empty($user)){
       		$this->set('user', $user);
			$aControllers = $this->Ctrl->get();
       		$this->set('controllers', $aControllers);

       		if($this->request->is('post')) {
				$sel_controller = $this->data['User']['controller'];
				$current_controller = $this->Ctrl->getControllerByName($sel_controller);
				$controller = str_replace('Controller', '', $current_controller['name']);

				foreach($current_controller['methods'] as $method){
					$user = $this->User;
					$permission = 'controllers/' . $controller . '/' . $method;
					$user->id = $user_id;

					if(isset($this->params['data'][$method]))
						$this->Acl->allow($user, $permission);
					else
						$this->Acl->deny($user, $permission);
				}

				// Clear cache //
				Cache::clear(false, 'menus');
				
				$this->Session->setFlash('The permissions for this controller has been changed', 'flash_success');
       		}
       		$this->set('result', true);
       	}
	}//~!

	/**
		* Check permissions for selected user
		* @param nothing
		* @return nothing
		* @throws NotFoundException if request is not ajax
	*/
	public function getByControllerAndUser() {
		$result = array();
		if ($this->request->is('ajax')) {
			$this->disableCache();

			$controller_name = $_REQUEST['controller'];
			$user_id = $_REQUEST['user_id'];

			if(!empty($controller_name))
			{
				$current_controller = $this->Ctrl->getControllerByName($controller_name);

	   			$method_count = 0;
	   			$controller = str_replace('Controller', '', $current_controller['name']);
	   			foreach($current_controller['methods'] as $method){
	      			$permission = 'controllers/' . $controller . '/' . $method;
	   				$user = $this->User;
	      			$user->id = $user_id;

	      			$group = $this->User->Group;
	      			$group->id = $this->User->field('group_id', array('User.id' => $user->id));

	      			$result[$method_count]['method'] = $method;
	      			$result[$method_count]['group']['allowed'] = $this->Acl->check($group, $permission);
	      			$result[$method_count]['user']['allowed'] = $this->Acl->check($user, $permission);
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
		* Search users
		* @param nothing
		* @return nothing
		* @throws nothing
	*/
	public function search(){
		$this->disableCache();
		if(!empty($this->data)){
			$value = $this->data['User']['name'];
			$this->Session->write('keyword', $value);
			$this->Paginator->settings = array(
				'recursive' => -1,
				'joins' => array(
					array(
						'table' => 'groups',
						'alias' => 'Group',
						'conditions' => array('Group.id = User.group_id')
					),
					array(
						'table' => 'cake_sessions',
						'alias' => 'cake_session',
						'type' => 'LEFT',
						'conditions' => array(
							'cake_session.user_id = User.id'
						)
					)
				),
				'fields' => '*',
				'limit' => 20,
				'group' => array('User.id'),
			    'conditions' => array('OR' => array('User.username LIKE' => '%'.$value.'%', 'User.id LIKE' => '%'.$value.'%', 'User.first_name LIKE' => '%'.$value.'%','User.last_name LIKE' => '%'.$value.'%', 'User.username LIKE' => '%'.$value.'%', 'User.password LIKE' => '%'.$value.'%', 'User.created LIKE' => '%'.$value.'%', 'User.modified LIKE' => '%'.$value.'%', 'User.group_id LIKE' => '%'.$value.'%', 'Group.name LIKE' => '%'.$value.'%'))
			);
			$data = $this->paginate('User');
			$this->set('users', $data);
			
			

			$this->autoRender = false;
			$this->render('search');
		}
		else{
			$value = $this->Session->read('keyword');

			$this->Paginator->settings = array(
				'recursive' => -1,
				'joins' => array(
					array(
						'table' => 'groups',
						'alias' => 'Group',
						'conditions' => array('Group.id = User.group_id')
					)
				),
				'fields' => '*',
				'limit' => 20,
				'conditions' => array('OR' => array('User.username LIKE' => '%'.$value.'%', 'User.id LIKE' => '%'.$value.'%', 'User.first_name LIKE' => '%'.$value.'%','User.last_name LIKE' => '%'.$value.'%', 'User.username LIKE' => '%'.$value.'%', 'User.password LIKE' => '%'.$value.'%', 'User.created LIKE' => '%'.$value.'%', 'User.modified LIKE' => '%'.$value.'%', 'User.group_id LIKE' => '%'.$value.'%', 'Group.name LIKE' => '%'.$value.'%'))
			);
			$data = $this->paginate('User');
			$this->set('users', $data);
			
			

			$this->autoRender = false;
			$this->render('search');
		}
	}//~!

	/**
	 * Check if file is uploaded
	 * @param nothing
	 * @return nothing
	 * @throws nothing
	 */
	function __isUploadedFile($val){
        if ((isset($val['error']) && $val['error'] == 0) ||
            (!empty($val['tmp_name']) && $val['tmp_name'] != 'none')) {
            return is_uploaded_file($val['tmp_name']);
        } else {
            return false;
        } 
	}//~!

	/**
	 * upload method
	 *
	 * @return void
	 */

	function upload() {
		//Backup current config
		$config = Configure::read('debug');

		//Disable notices and errors
		Configure::write('debug', 0);

	    $json_msg['error'] = "";

	    //Check if user is updating
	    if(!empty($this->request->data['User']['id'])) $user_id = $this->request->data['User']['id'];
	    else $user_id = 0;
		
	    try {	  
			//Check if avatar or attachment are set
			if(!$this->__isUploadedFile($this->request->data['User']['avatar']) && !$this->__isUploadedFile($this->request->data['User']['signature'])){
				throw new Exception('File upload not valid! Please, try again.');
			}
			//Set type and attachment
			$attachment = '';
			$type = '';
			if($this->__isUploadedFile($this->request->data['User']['avatar'])){
				$attachment = $this->request->data['User']['avatar'];
				$type = 'avatar';
			}
			if($this->__isUploadedFile($this->request->data['User']['signature'])){
				$attachment = $this->request->data['User']['signature'];
				$type = 'signature';
			}

	        $info = pathinfo($attachment['name']);

			$filename = strtolower(preg_replace('/[^a-z0-9]/ui', '', $info['filename'])).'_'.time();			

			//Set path
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				//This is a server using Windows
				$path = 'users'.DS.$type.'s'.DS;
				if(!file_exists(WWW_ROOT.'img'.DS.$path)){
					mkdir(WWW_ROOT.'img'.DS.$path, 0777, true);
				}		    
			} else {
				//echo 'This is a server not using Windows!';
				$file_owner = get_current_user();
				$path = 'users'.DS.$type.'s';
				if(!file_exists(WWW_ROOT.'img'.DS.$path)){
					mkdir(WWW_ROOT.'img'.DS.$path, 0755, true);
				}		    		 
				$path .= DS;
			} 

			$file_path =  $path.$filename.'.'.$info['extension'];

			//Check for file existance
			if(file_exists(WWW_ROOT.'img'.DS.$file_path)){
				//Check if it belongs to user
				if($this->request->data['User'][$type.'_link'] != $file_path){
					//Check for other user avatar
					if($this->User->find('count', array('conditions' => array('User.'.$type.'_link' => $file_path))) > 0){
						throw new Exception('Fajl sa istim imenom već postoji!');	
					}
				}			
			}			

	        $allowed =  array('jpg', 'bmp', 'png');
	        if(!in_array(strtolower($info['extension']), $allowed)) {
	            throw new Exception('Ovaj tip fajla nije dozvoljen!');
	        }

	        $moved = move_uploaded_file($attachment['tmp_name'], WWW_ROOT.'img'.DS.$file_path);

	        if(!$moved){
	          throw new Exception('Fajl ne može biti postavljen! Pokušajte ponovo.');
	        }

	        //Set data
	        $json_msg['filename'] = $filename;
	        $json_msg['filepath'] = $file_path;
	    }catch(Exception $e) {
	        $json_msg['error'] = $e->getMessage();
		}
		
	    //Set message and render
      	$this->set('result', $json_msg);
      	$this->set('_serialize', 'result');	  

      	//Restore previous configuration
      	Configure::write('debug', $config);
	}//~!


	/*
	* search users
	* ajax
	*/
	public function getUsersBySearch(){
		if ($this->request->is('ajax')) {
			$this->disableCache();

			$result = array();

			$term = $_REQUEST['term'];
			if(!empty($_REQUEST['exclude'])) $exclude = $_REQUEST['exclude'];

			$conditions = array(
				"OR" => array(
					"User.first_name LIKE" => "%".$term."%", 
					"User.last_name LIKE" => "%".$term."%"
					),
				'User.active' => 1
			);

			if (isset($_REQUEST['users_without_account']) && isset($_REQUEST['cur_user_id'])){
				$conditions[] = array(
					"OR" => array(
						"User.worker_id is null",
						"User.id" => $_REQUEST['cur_user_id']
					)
				);
			}

			if(!empty($exclude)){
				$conditions['NOT'] = array("User.id" => $exclude);
			}

            $settings = array(
                'conditions' => $conditions,                
                'recursive' => -1,
                'fields' => array("User.name", "User.id"),
                'limit' => 20,
                'order' => array('User.first_name' => 'ASC')
            );

            
            $result = $this->User->find('all', $settings);              

			//$result = $_REQUEST;

			$this->set('result', $result);
			$this->set('_serialize', 'result');
		}else{
			$this->Session->setFlash('Izabrali ste nepostojeću stranicu!', 'flash_error');
			return $this->redirect('/');
		}
	}//~!

	/*
	* search users
	* ajax
	*/
	public function getAllUsersBySearch(){
		if ($this->request->is('ajax')) {
			$this->disableCache();

			$result = array();

			$term = $_REQUEST['term'];
			if(!empty($_REQUEST['exclude'])) $exclude = $_REQUEST['exclude'];

			$conditions = array(
				"OR" => array(
					"User.first_name LIKE" => "%".$term."%", 
					"User.last_name LIKE" => "%".$term."%",
					"concat(User.first_name, ' ', User.last_name) LIKE" => "%".$term."%"
					)
				);
			
			

			if(!empty($exclude)){
				$conditions['NOT'] = array("User.id" => $exclude);
			}

			$joins = array(
	        	array(
	        		'table' => 'quotes',
	        		'alias' => 'Quote',
	        		'type' => 'INNER',
	        		'conditions' => array(
	        			'Quote.user_id = User.id'
        			)
        		)
        	);

            $settings = array(
                'conditions' => $conditions,                
                'recursive' => -1,
                'joins' => $joins,
                'fields' => array("User.name", "DISTINCT User.id"),
                'limit' => 20,
                'order' => array('User.active' => 'DESC', 'User.first_name' => 'ASC')
            );

            
            $result = $this->User->find('all', $settings);              

			//$result = $_REQUEST;

			$this->set('result', $result);
			$this->set('_serialize', 'result');
		}else{
			$this->Session->setFlash('Izabrali ste nepostojeću stranicu!', 'flash_error');
			return $this->redirect('/');
		}
	}//~!

	/**
	* set salary for user
	* @param int $id = user_id
	*/
	public function salary($id = null){
		try{
			$check = $this->User->checkUserID($id);
			if(isset($check['error'])){
				$this->Session->setFlash($check['error'], 'flash_error');
				return $this->redirect('/');
			}
			$this->set('user', $check['user']);
			$this->set('currencies', $this->User->Currency->find('list', array('recursive' => -1, 'fields' => 'Currency.id, Currency.currency')));
			$this->set('title_for_layout','MikroERP - Zarada zaposlenog');
			if($this->request->is('post') || $this->request->is('put')){
				$data = $this->request->data;
				//var_dump($data); exit();
				$this->User->id = $data['User']['id'];
				if(!$this->User->save($data)) throw new Exception('Došlo je do greške. Molimo Vas, pokušajte ponovo.');
				$input_data = serialize($data);
				$user_id = $this->user['id'];
				$this->ErpLogManagement->erplog($user_id, $this->params['controller'], $this->params['action'], $input_data, 'parameters', 'User added salary.');
				$this->Session->setFlash('Zarada je uspešno dodeljena zaposlenom.', 'flash_success');
				return $this->redirect(array('controller' => 'Users', 'action' => 'salary_list'));
			}else{	
				$this->request->data = $check['user'];
			}
		}catch(Exception $e){
			$this->Session->setFlash($e->getMessage(), 'flash_error');
		}
	}//~!


	/**
	* salary list for accountants only
	*/
	public function salary_list(){
		$this->set('title_for_layout','MikroERP - Zarade zaposlenih');

        $joins = array(
        	array(
        		'table' => 'currencies',
        		'alias' => 'Currency',
        		'type' => 'LEFT',
        		'conditions' => array(
        			'Currency.id = User.currency_id'
        			)
        		)
        	);
        
        $this->Paginator->settings = array(
        	'recursive' => -1,
        	'fields' => 'User.name, User.salary, User.id, Currency.currency, Currency.iso',
        	'joins' => $joins,
        	'limit' => 20,
        	'order' => 'User.last_name ASC'
        	);
        $users = $this->Paginator->paginate('User');
        $this->set('users', $users);
	}//~!

	/**
	* salary list for accountants only
	*/
	public function salary_list_search(){
		$this->set('title_for_layout','MikroERP - Zarade zaposlenih - Pretraga');
		if(!empty($this->request->data)){
	      	$value = trim($this->data['User']['keyWord']);
	      	$this->Session->write('keywordUser', $value);
	    }else{
	    	$value = $this->Session->read('keywordUser');
	    }

	    $conditions['OR'] = array(
	    	'User.first_name LIKE' => '%'.$value.'%',
	    	'User.last_name LIKE' => '%'.$value.'%',
	    	'User.salary LIKE' => '%'.$value.'%'
	    	);

        $joins = array(
        	array(
        		'table' => 'currencies',
        		'alias' => 'Currency',
        		'type' => 'LEFT',
        		'conditions' => array(
        			'Currency.id = User.currency_id'
        			)
        		)
        	);
        
        $this->Paginator->settings = array(
        	'recursive' => -1,
        	'fields' => 'User.name, User.salary, User.id, Currency.currency, Currency.iso',
        	'joins' => $joins,
        	'conditions' => $conditions,
        	'limit' => 20,
        	'order' => 'User.last_name ASC'
        	);
        $users = $this->Paginator->paginate('User');
        $this->set('users', $users);
	}//~!

	/*
	* get user info with ajax
	* Author: Davor
	* Date: 2014-12-09
	* Used in: Inventories/add
	*/
	public function getInfo(){
		if($this->request->is('ajax')){
			$this->disableCache();
			$id = $_REQUEST['user_id'];
			$data = $this->User->find('first', array(
				'recursive' => -1,
				'fields' => 'User.name, User.id',
				'conditions' => array(
					'User.id' => $id
					)
				));
			$result = array();
			if(empty($data)){
				$result['error'] = 'Korisnik ne postoji!';
			}else{
				$result['success']['user_id'] = $data['User']['id'];
				$result['success']['user_name'] = $data['User']['name'];
			}

			$this->set('result', $result);
			$this->set('_serialize', 'result');
		}else{
			$this->Session->setFlash('Izabrali ste nepostojeću stranicu!', 'flash_error');
			return $this->redirect('/');
		}
	}//~!

	/**
	 * show list of logged users
	 * @author Boris Urosevic
	 * @version 1.01
	*/
	public function logged_in_users(){
		$this->loadModel('cake_sessions');
		$users = $this->cake_sessions->getActiveUsers();
		foreach ($users as $key => $user) {
			$users[$key]['avatar_link'] = $this->User->field('avatar_link', array('User.id' => $key));
		}
		usort($users, function($b, $a) {
		    return $a['expires'] - $b['expires'];
		});
		$this->set('users', $users);
	}//~!

	/**
	 * Force user logout
	 *
	 * @param int $userId
	 * @return void
	 */
	public function force_logout($userId) {
		//Load cake sessions
		$this->loadModel('cake_sessions');
		//Force user logout
		$result = $this->cake_sessions->forceLogout($userId);
		//Check result
		if($result['success']){
			//Save action log
			$input_data = serialize($result);
			$this->ErpLogManagement->erplog($this->user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The user has been forcefully logged out');
			//Set message and redirect to index page
			$this->Session->setFlash($result['message'], 'flash_success');
		}else{
			//Set error message
			$this->Session->setFlash($result['message'], 'flash_error');
		}
		//Redirect to index page
		return $this->redirect('/Users/index');
	}//~!

	/**
	 * Update user id in sessions
	 *
	 * @param none
	 * @return void
	 */
	public function update_sessions_user_id() {
		//Load cake sessions
		$this->loadModel('cake_sessions');
		//Update user id in session
		$this->cake_sessions->updateUserIds();
		//Set message
		$this->Session->setFlash("user_id updated in session", 'flash_success');
		//Redirect to index page
		return $this->redirect('/Users/index');
	}//~!

	/**
	 * Change language
	 * @author Boris Urosevic
	 * @param string - language
	*/
	public function changeLanguage($language = 'srb'){
		$this->User->id = $this->user['id'];
		$this->User->saveField('language', $language);

		$input_data = serialize($language);
		$this->ErpLogManagement->erplog($this->user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'parameters', 'The user change language to '.$language);

		$this->Session->write('Auth.User.language', $language);
		return $this->redirect('/');
	}//~!

	/**
	 * List users who allowed for some action
	 * @author Boris Urosevic - boris.urosevic@mikroe.com
	 * @version created:08.02.2016 | modified:08.02.2016
	*/
	public function whoHaveAccess(){
		$aControllers = $this->Ctrl->get();

		foreach($aControllers as $controller => $methods){
			$arrController[$controller] = $controller; 
		}
		$this->set('controllers', $arrController);

		$haveAccesses = array();
		$controller = ''; $action = '';
		if(!empty($this->request->query['controller']) && !empty($this->request->query['action'])){
			
			$controller = str_replace('Controller','', $this->request->query['controller']);
			$action = $this->request->query['action'];
			
			$joins = array(
				array(
					'table' => 'groups',
					'alias' => 'Group',
					'conditions' => array('User.group_id = Group.id')
					)
				);
			$users = $this->User->find('all', array('recursive' => -1, 'joins' => $joins, 'fields' => 'User.id, User.first_name, User.last_name, User.username, Group.id, Group.name', 'conditions' => array('User.active' => 1)));
			foreach($users as $user){
				if($this->Acl->check(array('model' => 'User', 'foreign_key' => $user['User']['id']), $controller.'/'.$action)){
					array_push($haveAccesses, $user);
				}
			}
		}

		$this->set('controller', $controller);
		$this->set('action', $action);
		$this->set('haveAccesses', $haveAccesses);
	}//~!

	/**
	 * Ajax function
	 * Get actions from selectet controller
	 * @author Boris Urosevic - boris.urosevic@mikroe.com
	 * @version created:08.02.2016 | modified:08.02.2016
	*/
	public function getControllerActions(){
		$controller = $_REQUEST['controller'];

		$aControllers = $this->Ctrl->get();
		
		$actions = $aControllers[$controller];

		$this->set('result', $actions);
		$this->set('_serialize', 'result');
	}//~!

	/**
	 * User's permissions
	 *
	 * @param int $userId
	 * @return void
	 */
	public function permissions($userId) {
		$data = $this->User->getPermissions($userId);
		foreach($data as $key => $value) {
			$this->set($key, $value);
		}
	}//~!

}

?>