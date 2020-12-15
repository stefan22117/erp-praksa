<?php

App::uses('AppController', 'Controller');

/**
 * McuFamilies Controller
 *
 * @property McuFamily $McuFamily
 * @property PaginatorComponent $Paginator
 */
class MenuItemsController extends AppController {

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('ErpLogManagement', 'Paginator', 'Search');

	/**
	 * index method
	 *
	 * Get data for view with pagination and search filters
	 *
	 * @throws nothing
	 * @param none
	 * @return void
	 */
	public function index() {

		// Title for layout //
		$this->set('title_for_layout', __('Meni - MikroERP'));

		// Set conditions for query //
		$conditions = array(
			'MenuItem.parent_id' => null,
			'MenuItem.deleted' => 0
		);

		// Filter for submenus //
		if(isset($this->request->named['menuItemId'])) {
			$conditions['MenuItem.parent_id'] = $this->request->named['menuItemId'];
		}

		// Get search value from query string //
		if (isset($this->request->query['keywords'])) {
			$keywords = $this->request->query['keywords'];
			unset($conditions['MenuItem.parent_id']);
			$conditions['MenuItem.name LIKE'] = $this->Search->formatSearchString($keywords);
			$this->request->data['MenuItem']['keywords'] = $keywords;
		}

		// Pagination query options //
		$options = array(
			'conditions' => $conditions,
			'contain' => array('Aco', 'ErpKickstartIcon'),
			'order' => 'MenuItem.name ASC',
			'recursive' => -1,
			'limit' => 10
		);

		// Set data for view //
		$this->Paginator->settings = $options;
		$this->set('menuItems', $this->Paginator->paginate());

	}

	/**
	 * save method
	 *
	 * Add new record or edit record by id
	 * 
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function save($id = null) {

		// Title for layout //
		$this->set('title_for_layout', __('Snimanje - Menu - MikroERP'));

		// Set associated data for dropdown list //
		$this->set('parents', $this->MenuItem->find('list', array('conditions' => array('MenuItem.deleted' => 0))));

		$this->set('erpKickstartIcons', $this->MenuItem->ErpKickstartIcon->find('list', array(
			'fields' => array('ErpKickstartIcon.id', 'ErpKickstartIcon.title')
		)));

		$this->set('acoParents', $this->MenuItem->Aco->find('list', array(
			'conditions' => array(
				'Aco.parent_id' => 1
			),
			'fields' => array('Aco.id', 'Aco.alias')
		)));

		if($id == null) {
			// Set action name for view //
			$this->set('action', 'add');
			// Create ttolchain model //
			$this->MenuItem->create();
		} else {
			// Set action name for view //
			$this->set('action', 'edit');
			// Check if record exist //
			if (!$this->MenuItem->exists($id)) {
				throw new NotFoundException(__('Stavka u meniju sa ovim id-jem ne postoji'));
			}
		}

		if ($this->request->is('post') || $this->request->is('put')) {

			unset($this->request->data['MenuItem']['aco_parent_id']);

			// Save data //
			if ($this->MenuItem->save($this->request->data)) {

				// Clear cache //
				Cache::clear(false, 'menus');

				// Get user //
				$user = $this->user;

				// Save action log //
				$input_data = serialize($this->request->data);
	            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The MenuItem has been saved');

	            // Set message and redirect to index page //
				$this->Session->setFlash(__('Stavka u meniju je uspesno sacuvana.'), 'flash_success');
				return $this->redirect(array('action' => 'index'));
			}

			else {
	            // Set message //
				$this->Session->setFlash(__('Stavka u meniju ne moze biti sacuvana.'), 'flash_error');
			}


		} else {

			if($id != null) {
				// Set data for view //
				$this->request->data = $this->MenuItem->find('first', array(
					'conditions' => array(
						'MenuItem.id' => $id
					),
					'contain' => array('Parent', 'Aco'),
					'recursive' => -1
				));

				$this->request->data['MenuItem']['aco_parent_id'] = $this->request->data['Aco']['parent_id'];

				$this->set('acos', $this->MenuItem->Aco->find('list', array(
					'conditions' => array(
						'Aco.parent_id' => $this->request->data['Aco']['parent_id']
					),
					'fields' => array('Aco.id', 'Aco.alias')
				)));
			}
		}
	}

	/**
	 * delete method
	 * 
	 * Delete record by id
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function delete($id = null) {
		
		if ($this->request->is(array('post', 'delete'))) {

			// Save data //
			if ($result = $this->MenuItem->deleteMenuItem($id)) {

				// Clear cache //
				Cache::clear(false, 'menus');

				// Get user //
				$user = $this->user;

				// Save action log //
				$input_data = serialize($result);
	            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'parameters', 'The MenuItem has been deleted');

	            // Set message //
				$this->Session->setFlash(__('Stavka u meniju je izbrisana.'), 'flash_success');
			}

			else {
		        // Set message //
				$this->Session->setFlash(__('Stavka u meniju ne moze biti izbrisana.'), 'flash_error');
			}

			// Redirect to index page //
			return $this->redirect(array('action' => 'index'));

		}

	}

	public function selectActions() {

		if($this->request->is('ajax')) {

			// Get id from request //
			$acoId = $this->request->data['id'];

			// Find all toolchains for selected vendor //
			$this->set('result', $this->Acl->Aco->find('list', array(
				'conditions' => array(
					'Aco.parent_id' => $acoId
				),
				'fields' => array('Aco.id', 'Aco.alias')
			)));

			// Set response //
			$this->set('_serialize', 'result');
			
		} else {

			$this->Session->setFlash(__('Nepostojeca stranica.'), 'flash_error');
			$this->redirect(array('action' => 'index'));

		}
	}

}