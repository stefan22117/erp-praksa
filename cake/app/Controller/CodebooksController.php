<?php
App::uses('AppController', 'Controller');
/**
 * Codebooks Controller
 *
 * @property Codebook $Codebook
 * @property PaginatorComponent $Paginator
 */
class CodebooksController extends AppController {

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
		$this->set('title_for_layout', 'MikroERP - Pregled šifarnika');

        //Check for query
        $conditions = array();
        if(!empty($this->request->query)){
            $this->request->data['Codebook'] = $this->request->query;

            //Check for search fields
            if(!empty($this->request->query['keywords'])){
                $conditions['OR'][] = array('Codebook.name LIKE' => '%'.$this->request->query['keywords'].'%');
                $conditions['OR'][] = array('Codebook.table_name LIKE' => '%'.$this->request->query['keywords'].'%');
                $conditions['OR'][] = array('Codebook.model_name LIKE' => '%'.$this->request->query['keywords'].'%');
                $conditions['OR'][] = array('Codebook.controller_name LIKE' => '%'.$this->request->query['keywords'].'%');
                $conditions['OR'][] = array('Codebook.action_name LIKE' => '%'.$this->request->query['keywords'].'%');
            }
        }

        //Set data
        $settings = array();
        $settings['conditions'] = $conditions;        
        $this->Paginator->settings = $settings;

		$this->Codebook->recursive = 0;
		$this->set('codebooks', $this->Paginator->paginate('Codebook'));
	}//~!

	/**
	 * method for codebook changes overview
	 *
	 * @return void
	 */
	public function changes($controller_name, $codebook_record_id) {
		$this->set('title_for_layout', 'MikroERP - Pregled istorije promena šifarnika');

		//Get codebook
		$codebook = $this->Codebook->find('first', array('conditions' => array('Codebook.controller_name' => $controller_name), 'recursive' => -1));
		if(empty($codebook)){
			$this->Session->setFlash("Šifarnik nije validan", 'flash_error');
			return $this->redirect(array('action' => 'index'));
		}

		$this->set('codebook', $codebook);
		$this->set('codebook_record_id', $codebook_record_id);
        
		//Get name
		$model_name = $codebook['Codebook']['model_name'];
		$this->loadModel($model_name);
		$field_name = $this->$model_name->name_field;

		$model_code = $this->$model_name->read(array($model_name.'.'.$field_name), $codebook_record_id);
		if(empty($model_code)){
			$this->Session->setFlash("Zapis u šifarniku nije validan", 'flash_error');
			return $this->redirect(array('action' => 'index'));
		}
		$this->set('model_name', $model_code[$model_name][$field_name]);

        //Set defaults
        $conditions = array('CodebookChange.codebook_id' => $codebook['Codebook']['id'], 'CodebookChange.record_id' => $codebook_record_id);
        $ordering = array('CodebookChange.created' => 'DESC');

        //Check for query
        if(!empty($this->request->query)){
            $this->request->data['Codebook'] = $this->request->query;

            //Check for search fields
            if(!empty($this->request->query['keywords'])){
                $conditions['OR'][] = array('User.first_name LIKE' => '%'.$this->request->query['keywords'].'%');
                $conditions['OR'][] = array('User.last_name LIKE' => '%'.$this->request->query['keywords'].'%');
                $conditions['OR'][] = array('CodebookChange.field_changed LIKE' => '%'.$this->request->query['keywords'].'%');
                $conditions['OR'][] = array('CodebookChange.field_previous_data LIKE' => '%'.$this->request->query['keywords'].'%');
            }

            //Check for selected date
            if(!empty($this->request->query['date'])){
                $conditions['AND']['CodebookChange.created >='] = date('Y-m-d 00:00:00', strtotime($this->request->query['date']));
                $conditions['AND']['CodebookChange.created <='] = date('Y-m-d 23:59:59', strtotime($this->request->query['date']));
            }

            //Check for selected field
            if(!empty($this->request->query['key_changed'])){
                $conditions['AND']['CodebookChange.key_changed'] = $this->request->query['key_changed'];
            }

            //Check for sorting           
            $sorting_by = 'CodebookChange.created';
			if(!empty($this->request->query['sorting'])){
				switch ($this->request->query['sorting']) {
					case 'date':
						$sorting_by = 'CodebookChange.created';
						break;
					case 'user_id':
						$sorting_by = 'CodebookChange.user_id';
						break;
					case 'key_changed':
						$sorting_by = 'CodebookChange.key_changed';						
						break;
					case 'code_count_change':
						$sorting_by = 'CodebookChange.codebook_change_count';												
						break;						
					case 'code_count_change':
						$sorting_by = 'CodebookChange.field_change_count';												
						break;
					default:
						$sorting_by = 'CodebookChange.created';
						break;
				}
			}

            $sorting_direction = 'DESC';
            if(!empty($this->request->query['sorting_direction'])){
                if($this->request->query['sorting_direction'] == 'asc')
                	$sorting_direction = 'ASC';
            }			

			$ordering = array($sorting_by => $sorting_direction);
        }

        $settings = array();
        $settings['conditions'] = $conditions;
        $settings['order'] = $ordering;

        //Set data
        $this->Paginator->settings = $settings;

		//Get codebook changes
		$this->Codebook->CodebookChange->recursive = 0;
		$change = $this->Paginator->paginate('CodebookChange');
		$this->set('changes', $change);

		//Get codebook fields
		$model_name = $codebook['Codebook']['model_name'];
		$this->loadModel($codebook['Codebook']['model_name']);		
		$this->set('field_names', $this->$model_name->field_names);
	}//~!

	/**
	 * add method
	 *
	 * @return void
	 */
	public function save($id=null) {
		$this->set('title_for_layout', 'MikroERP - Pregled šifarnika - Snimanje');

		if(!empty($id)){
			$codebook = $this->Codebook->find('first', array('conditions' => array('Codebook.id' => $id), 'recursive' => -1));
			if(empty($codebook)){
				$this->Session->setFlash("Šifarnik nije validan", 'flash_error');
				return $this->redirect(array('action' => 'index'));
			}
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			//Check if codebook is new
			if(!empty($id)){
				$this->request->data['Codebook']['id'] = $id;
			}

			//Save to DB
			if($this->Codebook->save($this->request->data)) {
        		//Save action log
        		$user = $this->Session->read('Auth.User');
				$input_data = serialize($this->request->data);
	            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The codebook has been saved');

	            //Set message and redirect to index page
				$this->Session->setFlash(__('Šifarnik je snimljen.'), 'flash_success');
        		return $this->redirect(array('action' => 'index'));
			} else {							
				$this->Session->setFlash(__('Šifarnik ne moze biti snimljen! Pokusajte ponovo.'), 'flash_error');
			}
		}else{
			if(!empty($codebook)){
				$this->request->data['Codebook'] = $codebook['Codebook'];
			}
		}

		//Set codebook document types
		$this->Codebook->CodebookDocumentType->virtualFields = array('title' => "CONCAT(CodebookDocumentType.code, ' - ', CodebookDocumentType.name)");
		$codebook_document_types = $this->Codebook->CodebookDocumentType->find('list', array('fields' => array('CodebookDocumentType.id', 'CodebookDocumentType.title'), 'recursive' => -1));
		$this->set('codebook_document_types', $codebook_document_types);
	}//~!
}	