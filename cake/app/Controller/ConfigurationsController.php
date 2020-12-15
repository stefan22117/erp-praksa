<?php
App::uses('AppController', 'Controller');
/**
 * Configurations Controller
 *
 * @property Configurations $Configuration
 * @property PaginatorComponent $Paginator
 */
class ConfigurationsController extends AppController {
	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array(
		'Session', 'Acl', 'Cookie', 'Ctrl', 'RequestHandler', 'ErpLogManagement','Paginator', 'Search', 'String'
	);
	/**
	 * index method
	 * @param none
	 * @return void
	 */
	public function index() {
		//Set fullscreen layout
		$this->layout = 'fullscreen';		
		//Set title
		$this->set('title_for_layout', __("Konfiguracija - ERP moduli - MikroERP"));
        //Check for query
        $conditions = array();
        //Check for form submission
        if(!empty($this->request->query)){
        	//Set search form
            $this->request->data['Configuration'] = $this->request->query;
            //Check for search
            if(!empty($this->request->query['keywords'])){
            	//Set keywords
				$keywords = $this->Search->formatSearchString($this->request->query['keywords']);
				//Init search fields
				$search_fields = array(
					'name', 'model', 'tag', 'value', 'foreign_key_model', 'foreign_key_id',
					'multiple_model', 'multiple_field', 'multiple_condition', 'multiple_value'
				);
				//Set search conditions
				foreach ($search_fields as $field) {
					$conditions['OR']['Configuration.'.$field.' LIKE'] = "%".$keywords."%";
				}				
			}
            //Check for input type
            if(!empty($this->request->query['type'])){
				$conditions['Configuration.type'] = $this->request->query['type'];
			}
		}
        //Set settings
		$settings = array();
		$settings['conditions'] = $conditions;
		$settings['recursive'] = -1;
		$this->Paginator->settings = $settings;
        //Get data
		$configurations = $this->Paginator->paginate('Configuration');
		//Process configurations and set foreign key data
		$index = 0;
		//Load codebook connection
		$this->loadModel('CodebookConnection');
		foreach ($configurations as $configuration) {
			//Check if configuration is foreign key type
			if($configuration['Configuration']['type'] == 'foreign_key'){
				//Set model name
				$model_name = $configuration['Configuration']['foreign_key_model'];
				$model_id = $configuration['Configuration']['foreign_key_id'];
				//Get codebook by model name
				$codebook_connection = $this->CodebookConnection->find('first', array(
					'conditions' => array('Codebook.model_name' => $model_name),
					'fields' => array('CodebookConnection.id'),
					'recursive' => 0
				));
				//Set title
				$code = $this->CodebookConnection->getConnectionCode($codebook_connection['CodebookConnection']['id'], $model_id);
				$title = $this->CodebookConnection->getConnectionTitle($codebook_connection['CodebookConnection']['id'], $model_id);
				$configurations[$index]['Configuration']['foreign_title'] = $code.' - '.$title;
			}
			$index++;
		}
		//Set configurations
		$this->set('configurations', $configurations);
		//Set enum fields
		$this->set('types', $this->Configuration->types);
		$this->set('multiple_conditions', $this->Configuration->multiple_conditions);
	}//~!

	/**
	 * save method
	 * @param $id - Configuration.id
	 * @return void
	 */
	public function save($id=null) {
		//Set fullscreen layout
		$this->layout = 'fullscreen';
		//Set title
		$this->set('title_for_layout', __("Snimanje - Konfiguracija - ERP moduli - MikroERP"));
		//Check id
		if(!empty($id)){
			//Get configuration
			$configuration = $this->Configuration->find('first', array(
				'conditions' => array('Configuration.id' => $id),
				'recursive' => -1
			));
			//Check for configuration
			if(empty($configuration)){
	            //Set error message and redirect to index
				$this->Session->setFlash(__("Konfiguracija modula nije validna!"), 'flash_error');
				return $this->redirect(array('action' => 'index'));
			}
			//Set configuration
			$this->set('configuration', $configuration);
		}
		//Init forwarded configs
		$forwarded_configs = array();
		//Check if form is submitted
		if($this->request->is('post') || $this->request->is('put')){
            //Validate configuration
            if($this->Configuration->saveAll($this->request->data['Configuration'])){
				//Get operator
				$user = $this->Session->read('Auth.User');
				//Save action log        		
				$input_data = serialize($this->request->data);
				$this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The Configuration have been saved');
				//Set message and redirect to index
				$this->Session->setFlash(__("Konfiguracija modula je snimljena."), 'flash_success');
				//Redirect
				return $this->redirect($this->request->data['Main']['referer']);
            }else{
                //Set errors
				$error = $this->Configuration->findValidationError($this->validationErrors);
				//Set error message
				$this->Session->setFlash($error, 'flash_error');
			}
		}else{
			//Check for configuration
			if(!empty($configuration)){
				//Set configuration connection and record
				$this->Configuration->setConfigurationConnectionAndRecord($configuration);
				//Set configuration data to form
				$this->request->data['Configuration'][0] = $configuration['Configuration'];
			}
			//Set referer
			$this->set('referer', $this->referer());			
		}
		//Process forwarded configurations query
		if(!empty($this->request->query['Configuration'])){
			//Set forwarded strings from configuration
			$forwarded_configs['Configuration'] = $this->request->query['Configuration'];
			//Set connection and record
			$index = 0;
			foreach ($forwarded_configs['Configuration'] as $forwarded_config) {
				//Init configuration
				$configuration['Configuration'] = $forwarded_config;
				//Set configuration connection and record
				$this->Configuration->setConfigurationConnectionAndRecord($configuration);
				//Assign configuration connection and record
				$forwarded_configs['Configuration'][$index] = $configuration['Configuration'];
				$index++;
			}
		}		
		//Set forwarded configs
		$this->set('forwarded_configs', $forwarded_configs);
		//Set enum fields
		$this->set('types', $this->Configuration->types);
		$this->set('multiple_conditions', $this->Configuration->multiple_conditions);
		//Set codebook connection
		$this->loadModel('CodebookConnection');
		$this->set('codebook_connections', $this->CodebookConnection->getConnectionList());
	}//~!

	/**
	 * delete method
	 * @param $id - Configuration.id
	 * @return void
	 */
	public function delete($id) {
		//Get configuration for deleting
		$configuration = $this->Configuration->find('first', array(
			'conditions' => array('Configuration.id' => $id),
			'recursive' => -1
		));
		//Check for configuration
		if(empty($configuration)){
			//Set error message
			$this->Session->setFlash(__('Konfiguracija modula nije definisana!'), 'flash_error');
		}else{
			//Delete configuration from DB
			if(!$this->Configuration->delete($id)){
				//Set error message
				$this->Session->setFlash(__('Konfiguracija modula ne može biti obrisana!'), 'flash_error');
			}else{
				//Save action log
				$input_data = serialize($configuration);
				$this->ErpLogManagement->erplog($this->user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The Configuration has been deleted');
				//Set success message
				$this->Session->setFlash(__('Konfiguracija modula je obrisana!'), 'flash_success');
			}
		}
		//Redirect to index
		return $this->redirect(array('action' => 'index'));
	}//~!	
}