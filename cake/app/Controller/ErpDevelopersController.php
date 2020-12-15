<?php
class ErpDevelopersController extends AppController {

	public $components = array('ErpLogManagement', 'Paginator', 'Search');

	/**
	 * index
	 *
	 * @throws nothing
	 * @param none
	 * @return void
	 */
	public function index() {
		$this->set('title_for_layout', __('ERP developeri - MikroERP'));
		$settings =  array(
			'fields' => array('ErpDeveloper.*', 'User.*'),
			'joins' => array(
				array(
					'table' => 'users',
					'alias' => 'User',
					'conditions' => 'ErpDeveloper.user_id = User.id'
				)
			),
			'conditions' => array('ErpDeveloper.deleted' => 0)
		);
		if(isset($this->request->query['keywords'])) {
			$keywords = $this->request->query['keywords'];
			if(!empty($keywords)) {
				$settings['conditions']['OR'][] = array('User.first_name LIKE' => '%'.$keywords.'%');
				$settings['conditions']['OR'][] = array('User.last_name LIKE' => '%'.$keywords.'%');
				$this->request->data['ErpUnit']['keywords'] = $keywords;
			}
			$this->request->data['ErpDeveloper']['keywords'] = $keywords;
		}
		$this->Paginator->settings = $settings;
		$erpDevelopers = $this->Paginator->paginate();
		$this->set('erpDevelopers', $erpDevelopers);
	}//~!

	/**
	 * save
	 *
	 * @throws nothing
	 * @param none
	 * @return void
	 */
	public function save($id = null) {
		$this->set('title_for_layout', __('ERP developeri - MikroERP'));
		$this->ErpDeveloper->User->virtualFields = array('full_name' => 'CONCAT(first_name," ",last_name)');
		$users = $this->ErpDeveloper->User->find('list', array(
			'fields' => array('id', 'full_name'),
			'recursive' => -1
		));
		unset($this->ErpDeveloper->User->virtualFields);
		$this->set('users', $users);
		if ($this->request->is(array('put', 'post'))) {
			if(!$this->ErpDeveloper->save($this->request->data)) {
				$this->Session->setFlash(array_shift($this->ErpDeveloper->validationErrors)[0], 'flash_error');
			} else {
				$this->Session->setFlash('Developer je uspesno sačuvan', 'flash_success');
				$this->redirect(array('controller' => 'ErpDevelopers', 'action' => 'index'));
			}
		} else {
			if(!empty($id)) {
				$this->request->data = $this->ErpDeveloper->find('first', array(
					'conditions' => array(
						'ErpDeveloper.id' => $id
					)
				));
			}
		}
 	}//~!

	/**
	 * delete
	 *
	 * @throws nothing
	 * @param none
	 * @return void
	 */
 	public function delete($id) {
		if(!empty($id)) {
			if(!$this->ErpDeveloper->deleteErpDeveloper($id)) {
				$this->Session->setFlash('Developer ne može biti izbrisan', 'flash_error');
			} else {
				$this->Session->setFlash('Developer je uspešno izbrisan', 'flash_success');
			}
			$this->redirect(array('controller' => 'ErpDevelopers', 'action' => 'index'));
		}
 	}//~!

}
