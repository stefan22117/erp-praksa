<?php
App::uses('AppController', 'Controller');
/**
 * CodebookConnections Controller
 *
 * @property CodebookConnection $CodebookConnection
 * @property PaginatorComponent $Paginator
 */
class CodebookConnectionsController extends AppController {

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
		$this->set('title_for_layout', 'MikroERP - Pregled šifarnika veze dokumenata');

        //Check for query
        $conditions = array();
        if(!empty($this->request->query)){
            $this->request->data['CodebookConnection'] = $this->request->query;

            //Check for search fields
            if(!empty($this->request->query['keywords'])){
                $conditions['OR'][] = array('CodebookConnection.name LIKE' => '%'.$this->request->query['keywords'].'%');
                $conditions['OR'][] = array('CodebookConnection.list_method LIKE' => '%'.$this->request->query['keywords'].'%');
                $conditions['OR'][] = array('Codebook.name LIKE' => '%'.$this->request->query['keywords'].'%');
            }
        }

        //Set data
        $settings = array();
        $settings['conditions'] = $conditions;
        $this->Paginator->settings = $settings;

		$this->CodebookConnection->recursive = 0;
		$this->set('connections', $this->Paginator->paginate('CodebookConnection'));
	}//~!

	/**
	 * add method
	 *
	 * @return void
	 */
	public function save($id=null) {
		$this->set('title_for_layout', 'MikroERP - Pregled šifarnika veze dokumenata - Snimanje');

		if(!empty($id)){
			$connection = $this->CodebookConnection->find('first', array('conditions' => array('CodebookConnection.id' => $id), 'recursive' => -1));
			if(empty($connection)){
				$this->Session->setFlash("Veza dokumenta nije validna", 'flash_error');
				return $this->redirect(array('action' => 'index'));
			}
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			//Check if codebook is new
			if(!empty($id)){
				$this->request->data['CodebookConnection']['id'] = $id;
			}

			//Save to DB
			if ($this->CodebookConnection->save($this->request->data)) {
        		//Save action log
        		$user = $this->Session->read('Auth.User');
				$input_data = serialize($this->request->data);
	            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The codebook connection has been saved');

	            //Set message and redirect to index page
				$this->Session->setFlash(__('Šifarnik veze dokumenata je snimljena.'), 'flash_success');
        		return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Šifarnik veze dokumenata ne može biti snimljen! Pokusajte ponovo.'), 'flash_error');
			}
		}else{
			if(!empty($connection)){
				$this->request->data['CodebookConnection'] = $connection['CodebookConnection'];
			}
		}	

		//Codebooks
		$this->set('codebooks', $this->CodebookConnection->Codebook->find('list'));
	}//~!

	/**
	 * method for getting data
	 *
	 * @return void
	 */
	public function getConnectionData($id=null) {
		if ($this->request->is('ajax')) {
			$this->disableCache();

			//Init variables
			$result = array();

			//Load terms
			$term = $_REQUEST['term'];
			$account_year = null;

			//Load connection id
			if(!empty($_REQUEST['codebook_connection_id'])){
				$id = $_REQUEST['codebook_connection_id'];
			}
			
			//Load account year
			if(!empty($_REQUEST['account_year'])){
				$account_year = $_REQUEST['account_year'];
			}

			//Get result
			$params = array(
				'keywords' => $term,
				'account_year' => $account_year
			);
			$result = $this->CodebookConnection->listConnectionData($id, $params);

            //Set result
			$this->set('result', $result);
			$this->set('_serialize', 'result');
		}else{
			throw new NotFoundException(__('Stranica ne postoji!'));
		}				
	}//~!	

	/**
	 * Method for searching codebook connections over ajax
	 *
	 * @return void
	 */
	public function searchCodebookConnections() {
		if ($this->request->is('ajax')) {
			$this->disableCache();

			//Init variables
			$result = array();

			//Load terms
			$term = $_REQUEST['term'];
			
	        //Init conditions
	        $conditions = array();

            //Search for keywords
            if(!empty($term)){
                $conditions['OR'][] = array('CodebookConnection.name LIKE' => '%'.$term.'%');
                $conditions['OR'][] = array('CodebookConnection.code LIKE' => '%'.$term.'%');
            }

            //Get results
            $this->CodebookConnection->virtualFields = array('title' => "CONCAT(CodebookConnection.code, ' - ', CodebookConnection.name)");
            $result = $this->CodebookConnection->find('list', array('conditions' => $conditions, 'fields' => array('CodebookConnection.id', 'CodebookConnection.title'), 'recursive' => -1));
            $this->CodebookConnection->virtualFields = array();

            //Set results
			$this->set('result', $result);
			$this->set('_serialize', 'result');
		}else{
			throw new NotFoundException(__('Stranica ne postoji!'));
		}
	}//~!	

	/**
	 * Method for searching codebook connection datas over ajax
	 *
	 * @return void
	 */
	public function getConnectionDataBySearch() {
		if ($this->request->is('ajax')) {
			$this->disableCache();

			//Init variables
			$result = array();

			//Load terms					
			$term = $this->Search->formatSearchString($_REQUEST['term']).'%';
			
	        //Init conditions
	        $conditions = array();

            //Search for keywords
            if(!empty($term)){
                $conditions['OR'][] = array('CodebookConnectionData.data_title LIKE' => '%'.$term.'%');
                $conditions['OR'][] = array('CodebookConnectionData.data_code LIKE' => '%'.$term.'%');
            }

            //Get results
            $this->CodebookConnection->CodebookConnectionData->virtualFields = array(
            	'title' => "CONCAT(CodebookConnection.name, ' - ', CodebookConnectionData.data_code, ' - ', CodebookConnectionData.data_title)"
            );
            $result = $this->CodebookConnection->CodebookConnectionData->find('all', array('conditions' => $conditions, 'fields' => array('CodebookConnectionData.id', 'CodebookConnectionData.title'), 'recursive' => 0));
            $this->CodebookConnection->CodebookConnectionData->virtualFields = array();

            //Set results
			$this->set('result', $result);
			$this->set('_serialize', 'result');
		}else{
			throw new NotFoundException(__('Stranica ne postoji!'));
		}
	}//~!	
}	