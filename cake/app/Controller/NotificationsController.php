<?php
class NotificationsController extends AppController{
	var $name = 'Notifications';

	public $components = array('ErpLogManagement', 'Search', 'Paginator');

	/**
		* index page
		* every user see his own notifications
	*/
	public function index(){
		// count unread notifications
		$unreadNotificationsNumber = $this->Notification->find('count', array(
			'recursive' => -1,
			'conditions' => array(
				'Notification.user_id' => $this->user['id'],
				'Notification.read' => 0
				)
			));
		$page_title = __('Notifikacije');    
		$this->set('page_title', $page_title);
		$this->set('title_for_layout', '('.$unreadNotificationsNumber.')'.$page_title.' - MikroERP');            

		// get notifications
		$this->Paginator->settings = array(
			'recursive' => -1,
			'limit' => 20,
			'conditions' => array(
				'Notification.user_id' => $this->user['id']
				),
			'order' => 'Notification.id DESC'	 
			);

		$notifications = $this->Paginator->paginate('Notification');

		// loop through notifications and unserialize issue field data
		foreach ($notifications as $key => $value) {
			$issuer = unserialize($value['Notification']['issuer']); 
			if (isset($issuer['document_id'])) $notifications[$key]['Notification']['document_id'] = $issuer['document_id'];
			if (isset($issuer['document_code'])) $notifications[$key]['Notification']['document_code'] = $issuer['document_code'];
			if (isset($issuer['document_title'])) $notifications[$key]['Notification']['document_title'] = $issuer['document_title'];
			if (isset($issuer['link_controller'])) $notifications[$key]['Notification']['link_controller'] = $issuer['link_controller'];
			if (isset($issuer['link_action'])) $notifications[$key]['Notification']['link_action'] = $issuer['link_action'];
		}

		$this->set('notifications', $notifications);
	}//~!
 
	/**
		* set read status notification
	*/
	public function readNotification(){
		if ($this->request->is('ajax')){
			$this->disableCache();
			$this->Notification->id = $_REQUEST['id'];
			if ($this->Notification->saveField('read', 1)){
				$result['success'] = 'Notification read.';
				$this->ErpLogManagement->erplog($this->user['id'], $this->params['controller'], $this->params['action'], serialize('notification_id: '.$_REQUEST['id']), 'parameters', 'Notification read');
			}else{
				$resukt['error'] = 'UNDEFINED ERROR 10000 101 1011';
			}

			$this->set('result', $result);
			$this->set('_serialize', 'result');
		}else{
			$this->Session->setFlash('UNDEFINED ERROR 10000 101 1011', 'flash_error');
			return $this->redirect('/');
		}
	}//~!

	/**
	 * Read all notifications for user
	 *
	 * @author Vladislav Hristodulo <vladislav.hristodulo@mikroe.com>
	 * @since 24.06.2019
	 * @throws Exception
	 * @param int $userId
	 * @return boolean
	 */
	public function readAllNotifications($userId) {
		if ($this->request->is('ajax')){
			$this->disableCache();
			$this->autoRender = false;

			$unreadNotifications = $this->Notification->getNotifications($userId);
			foreach ($unreadNotifications as $unreadNotification) {
				$this->Notification->id = $unreadNotification['Notification']['id'];
				$this->Notification->saveField('read', '1');
			}
			$result = array();
			$this->set('result', $result);
			$this->set('_serialize', 'result');
		} else {
			$this->Session->setFlash('Nepostojeća stranica', 'flash_error');
			return $this->redirect('');
		}
	}//~!
}
?>