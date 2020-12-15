<?php
class MenuElementsController extends AppController{
	var $name='MenuElements';
	public $components = array('Session', 'Acl', 'Cookie', 'RequestHandler', 'Ctrl', 'ErpLogManagement', 'Paginator');
  public $paginate = array('limit' => 20);

  public function beforeFilter() {      
    parent::beforeFilter();
  }//~!

  /**
    * List all menu elements
    * @param nothing
    * @return nothing
    * @throws nothing
  */
  public function index(){
    $this->set('title_for_layout', 'MikroERP - List menu elements');
    $this->set('menus', $this->MenuElement->Menu->find('all', array('conditions' => array('not' => array('Menu.parent_id' => NULL)))));
    $aControllers = $this->Ctrl->get();
    $this->set('controllers', $aControllers);
    $this->set('menuelements', $this->MenuElement->find('all'));
  }//~!

  /**
    * Add menu element
    * @param int $id of menu
    * @return nothing
    * @throws nothing
  */
	public function add($id = NULL) {
    $this->disableCache();
    $this->set('title_for_layout', 'MikroERP - Add menu element');
    $this->set('id', $id);

   	if ($this->request->is('post')) {
    	$this->MenuElement->create();

    	if($this->MenuElement->save($this->request->data)) {
        $user = $this->Session->read('Auth.User');
        $input_data = serialize($this->request->data);
        $this->ErpLogManagement->erplog($user['id'], $this->params['controller'], $this->params['action'], $input_data, 'form', 'The manu element has been added');
     		$this->Session->setFlash('The Menu Element has been saved', 'flash_success');

        // Clearing cache
        Cache::clear(false, 'menus');
        
        //Set defaults for index
        $this->set('menus', $this->MenuElement->Menu->find('all', array('conditions' => array('not' => array('Menu.parent_id' => NULL)))));
        
        /*
        $menus = $this->Menu->find('all', array('fields' => array('MenuElement.id', 'MenuElement.title', 'Group.name'), 'conditions' => array('not' => array('Menu.parent_id' => NULL)), 'recursive' => 1));
        $list_menus = array();
        foreach ($menus as $menu) {
          $list_menus[$menu['MenuElement']['id']] = $menu['MenuElement']['title']." (".$menu['Group']['name'].")";
        }
        $this->set('menus', $list_menus);*/


        $aControllers = $this->Ctrl->get();
        $this->set('controllers', $aControllers);
        $this->set('menuelements', $this->MenuElement->find('all'));

        // Clearing cache
        Cache::clear(false, 'menus');

        //Set index render because it updates user container
        $this->autoRender = false;
        $this->render('index'); 
      }
      else{
     	  $this->Session->setFlash('The Menu Element could not be saved. Please, try again.', 'flash_error');
      }
    }

  	$this->set('menus', $this->MenuElement->Menu->find('list', array('fields' => 'title', 'conditions' => array('not' => array('Menu.parent_id' => NULL)))));

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
    if($this->MenuElement->read(NULL, $id)){
      $this->set('title_for_layout', 'MikroERP - Edit menu element title');

      $aControllers = $this->Ctrl->get();
      $this->set('controllers', $aControllers);
      $this->set('menus', $this->MenuElement->Menu->find('list', array('fields' => 'title')));
      $this->set('action', $this->MenuElement->find('first', array('fields' => 'action', 'conditions' => array('MenuElement.id' => $id))));
      if(empty($this->data))
      {
        $this->data = $this->MenuElement->read(NULL, $id);
      }
      else
      {
        // save if data is correct
        if($this->MenuElement->save($this->data))
        {
          $this->MenuElement->set($this->data);
          $this->MenuElement->validates();
          $user = $this->Session->read('Auth.User');
          $input_data = serialize($this->request->data);
          $this->ErpLogManagement->erplog($user['id'], $this->params['controller'], $this->params['action'], $input_data, 'form', 'The user editing menu element');
          $this->Session->setFlash('The menu element title has been updated', 'flash_success');

          // Clearing cache
          Cache::clear(false, 'menus');
         
          //Set defaults for index
          $this->set('menuelements', $this->MenuElement->find('all'));

          //Set index render because it updates user container
          $this->autoRender = false;
          $this->render('index'); 
        }
        else
        {
          $this->Session->setFlash('The menu element could not be edited. Please, try again.', 'flash_error');
        }
      }
    }else{
      $this->Session->setFlash(__('The menu element does not exist.'), 'flash_error');
      return $this->redirect('/');
    }
  }//~!

  /**
    * Delete menu element
    * @param int $id
    * @return nothing
    * @throws nothing
  */
  public function delete($id = NULL){
    $user = $this->Session->read('Auth.User');
    $input_data = serialize($this->MenuElement->find('all', array('recursive' => -1, 'conditions' => array('MenuElement.id' => $id))));
    $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'parameters', 'The user deleted menu element');
    $this->MenuElement->delete($id);
    // Clearing cache
    Cache::clear(false, 'menus');
    $this->Session->setFlash('The menu element has been deleted', 'flash_success');
    $this->redirect(array('action' => 'index'));
  }//~!

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
    * Search data content
    * @param nothing
    * @return nothing
    * @throws nothing
  */
  public function search(){
    $this->disableCache();
    if(!empty($this->data)){
      $value = $this->data['MenuElement']['name'];
      $this->Session->write('keyword', $value);
      $this->Paginator->settings = array(
        'limit' => 20,
        'order' => 'MenuElement.created DESC',
        'conditions' => array('OR' => array('MenuElement.created LIKE' => '%'.$value.'%', 'MenuElement.id LIKE' => '%'.$value.'%', 'Menu.title LIKE' => '%'.$value.'%' , 'MenuElement.modified LIKE' => '%'.$value.'%', 'MenuElement.title LIKE' => '%'.$value.'%', 'MenuElement.controller LIKE' => '%'.$value.'%', 'MenuElement.action LIKE' => '%'.$value.'%' ))
      );
      $data = $this->paginate('MenuElement');
      $this->set('menuelements', $data);

      $this->autoRender = false;
      $this->render('search');
      }
      else{
        $value = $this->Session->read('keyword');

              $this->Paginator->settings = array(
        'limit' => 20,
        'conditions' => array('OR' => array('MenuElement.created LIKE' => '%'.$value.'%', 'MenuElement.id LIKE' => '%'.$value.'%', 'Menu.title LIKE' => '%'.$value.'%' , 'MenuElement.modified LIKE' => '%'.$value.'%', 'MenuElement.title LIKE' => '%'.$value.'%', 'MenuElement.controller LIKE' => '%'.$value.'%', 'MenuElement.action LIKE' => '%'.$value.'%' ))
      );
      $data = $this->paginate('MenuElement');
      $this->set('menuelements', $data);

      $this->autoRender = false;
      $this->render('search');        
      }
  }//~!
}
?>