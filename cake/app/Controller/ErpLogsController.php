<?php
class ErpLogsController extends AppController {
	var $name = 'ErpLogs';

	public $components = array('Session', 'Cookie', 'Ctrl', 'RequestHandler', 'ErpLogManagement', 'Paginator');
    public $helpers=array('Html', 'Form', 'Session', 'Js');

    /**
    	* Parent beforeFilter action that is called before running child controller
    	* @param nothing
    	* @return nothing
    	* @throws nothing
    */  
    public function beforeFilter() {
        parent::beforeFilter();
    }//~!

    /**
    	* Index page - List latest ERP logs
    	* @param nothing
    	* @return nothing
    */
    public function index() {
    	$this->set('title_for_layout', 'MikroERP - Logs');
        $this->Session->delete('keyword');
        $this->Paginator->settings = array(
        	'limit' => 20
        );
        $data = $this->paginate('ErpLog');
        $this->set('logs', $data);
    }//~!
      
    /**
    	* Delete ERP log by ID
    	* @param int $id - ERP log ID
    	* @return nothing
    */ 
    public function delete($id) {      
        if ($this->ErpLog->delete($id)) {
            $this->Session->setFlash('The Log Row has been deleted','default',array(),'success');
            return $this->redirect(array('action' => 'index'));
        }else{
            $this->Session->setFlash('Unable to delete Log Row. Please, try again.','default',array(),'error');
        }
    }//~!

    /**
    	* View specific ERP log by ID provided
    	* @param int $id - ERP log ID
    	* @return nothing
    */ 
    public function view($id) {
        if(!empty($id)){
            $this->set('view', $this->ErpLog->read(NULL, $id));
            if(!empty($this->data)){
                $value = $this->data['ErpLog']['name'];
                $this->set('logs',$this->ErpLog->find('all',array( 'conditions' => array('OR' => array('User.username LIKE' => '%'.$value.'%', 'User.id LIKE' => '%'.$value.'%', 'ErpLog.id LIKE' => '%'.$value.'%','ErpLog.controller LIKE' => '%'.$value.'%', 'ErpLog.action LIKE' => '%'.$value.'%', 'ErpLog.input_data LIKE' => '%'.$value.'%', 'ErpLog.created LIKE' => '%'.$value.'%', 'ErpLog.input_data_source LIKE' => '%'.$value.'%', 'ErpLog.description LIKE' => '%'.$value.'%')))));

                $this->Paginator->settings = array(
                  'limit' => 20,
                  'order' => 'ErpLog.created DESC',
                  'conditions' => array('OR' => array('User.username LIKE' => '%'.$value.'%', 'User.id LIKE' => '%'.$value.'%', 'ErpLog.id LIKE' => '%'.$value.'%','ErpLog.controller LIKE' => '%'.$value.'%', 'ErpLog.action LIKE' => '%'.$value.'%', 'ErpLog.input_data LIKE' => '%'.$value.'%', 'ErpLog.created LIKE' => '%'.$value.'%', 'ErpLog.input_data_source LIKE' => '%'.$value.'%', 'ErpLog.description LIKE' => '%'.$value.'%'))
                );

                $data = $this->paginate('ErpLog');
                $this->set('logs', $data);

                $this->autoRender = false;
                $this->render('index');
            }else{
              $this->Paginator->settings = array(
                'limit' => 20,
                'order' => 'ErpLog.created DESC'
              );
              $data = $this->paginate('ErpLog');
              $this->set('logs', $data);              
            }
        }else{
            return $this->redirect(array('action' => 'index'));
        }
    }//~!

  	public function search(){
    	$this->disableCache();
    	if(!empty($this->data)){
			$value = $this->data['ErpLog']['name'];
			$this->Session->write('keyword', $value);

			$this->Paginator->settings = array(
			'limit' => 20,
			'conditions' => array('OR' => array('User.username LIKE' => '%'.$value.'%', 'User.id LIKE' => '%'.$value.'%', 'ErpLog.id LIKE' => '%'.$value.'%','ErpLog.controller LIKE' => '%'.$value.'%', 'ErpLog.action LIKE' => '%'.$value.'%', 'ErpLog.input_data LIKE' => '%'.$value.'%', 'ErpLog.created LIKE' => '%'.$value.'%', 'ErpLog.input_data_source LIKE' => '%'.$value.'%', 'ErpLog.description LIKE' => '%'.$value.'%'))
			);

			$data = $this->paginate('ErpLog');
			$this->set('logs', $data);

			$this->autoRender = false;
			$this->render('search');
      	}
      	else{
			$value = $this->Session->read('keyword');

			$this->Paginator->settings = array(
				'limit' => 20,
				'conditions' => array('OR' => array('User.username LIKE' => '%'.$value.'%', 'User.id LIKE' => '%'.$value.'%', 'ErpLog.id LIKE' => '%'.$value.'%','ErpLog.controller LIKE' => '%'.$value.'%', 'ErpLog.action LIKE' => '%'.$value.'%', 'ErpLog.input_data LIKE' => '%'.$value.'%', 'ErpLog.created LIKE' => '%'.$value.'%', 'ErpLog.input_data_source LIKE' => '%'.$value.'%', 'ErpLog.description LIKE' => '%'.$value.'%'))
			);

			$data = $this->paginate('ErpLog');
			$this->set('logs', $data);

			$this->autoRender = false;
			$this->render('search');
      	}
  	}//~!
}

?>