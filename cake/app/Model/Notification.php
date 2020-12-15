<?php
class Notification extends AppModel{
	var $name = 'Notification';

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
			)
		);

	public $validate = array(
		'title' => array(
			'rule' => 'notEmpty',
			'message' => 'Niste uneli naslov!',
			'required' => true,
			'allowEmpty' => false
			),
		'controller_name' => array(
			'rule' => array('checkControllerName'),
			'message' => 'Kontroler nije validan!',
			'required' => true,
			'allowEmpty' => false
			),
		'action_name' => array(
			'rule' => array('checkActionName'),
			'message' => 'Akcija nije validna!',
			'required' => true,
			'allowEmpty' => false
			),
		'user_id' => array(
			'rule' => array('checkUserId'),
			'message' => 'Zaposleni nije validan!',
			'required' => true,

			),
		'read' => array(
			'rule' => array('checkReadField'),
			'message' => 'Vrednost polja READ nije validna.',
			'required' => true,
			'allowEmpty' => false
			)
		);

	/**
		* check controller name
		* @author Davor Petkovic
		* @version 2016-11-14
		*
		* @param array $check
		* @throws none
		* @return boolean
	*/
	public function checkControllerName($check){
		return in_array($check['controller_name'].'Controller', App::objects('controller'));
	}//~!

	/**
		* check action name
		* @author Davor Petkovic
		* @version 2016-11-14
		* 
		* @param array $check
		* @throws none
		* @return boolean
	*/
	public function checkActionName($check){
		//return in_array($check['action_name'], get_class_methods($this->data['Notification']['controller_name'].'Controller'));
		return true;
	}//~!

	/**
		* check user id
		* @author Davor Petkovic
		* @version 2016-11-14
		* 
		* @param array $check
		* @throws none
		* @return boolean
	*/
	public function checkUserId($check){
		$user = $this->User->find('count', array('conditions' => array('User.id' => $check['user_id'], 'User.active' => 1)));
		if (!$user) return false;
		else return true;
	}//~!
		
	/**
		* check read field
		* @author Davor Petkovic
		* @version 2016-11-14
		* 
		* @param array $check
		* @throws none
		* @return boolean
	*/
	public function checkReadField($check){
		return in_array($check['read'], array(0,1));
	}//~!

	/**
		* create notification
		* @author Davor Petkovic
		* @version 2016-11-14
		* 
		* @param array $data (title, description, controller_name, action_name, user_id, read, issuer)
		* @throws none
		* @return array $result
	*/
	public function createNotification($data){
		try{

			$this->create();
			if (!$this->save($data)){
				$result['errors'] = $this->validationErrors;
				$error = $this->findValidationError( $this->validationErrors );
				throw new Exception( __('Greška prilikom generisanja notifikacije. '). $error );
			}	
			$result['success'] = $data;
		}catch(Exception $e){
			$result['error'] = $e->getMessage();
		}
		
		return $result;
	}//~!

	/**
		* get unread notifications based on user_id
		* @author Davor Petkovic
		* @version 2016-11-14
		* 
		* @param int $user_id
		* @throws none
		* @return array $notifications
	*/
	public function getNotifications($user_id){
		$notifications = $this->find('all', array(
			'recursive' => -1,
			'conditions' => array(
				'Notification.user_id' => $user_id,
				'Notification.read' => 0
				)
			));
		
		foreach ($notifications as $key => $value) {
			$issuer = unserialize($value['Notification']['issuer']);
			if (isset($issuer['document_id'])) $notifications[$key]['Notification']['document_id'] = $issuer['document_id'];
			if (isset($issuer['document_code'])) $notifications[$key]['Notification']['document_code'] = $issuer['document_code'];
			if (isset($issuer['document_title'])) $notifications[$key]['Notification']['document_title'] = $issuer['document_title'];
			if (isset($issuer['link_controller'])) $notifications[$key]['Notification']['link_controller'] = $issuer['link_controller'];
			if (isset($issuer['link_action'])) $notifications[$key]['Notification']['link_action'] = $issuer['link_action'];
		}

		return $notifications;
	}//~!

	/**
	 * automatic system mark notification as read
	 *
	 * @author Davor Petkovic <davor.petkovic@mikroe.com>
	 * @version 1.0
	 * @since 2016-26-01
	 * @param string $controller
	 * @param string $action
	 * @param string $data_id
	 * @throws exception on error
	 * @return array $result
	*/
	public function automaticRead($controller, $action, $data_id){
		$dataSource = $this->getDataSource();
		$dataSource->begin();
		try{
			# set data string
			$data_string = '%"document_id";s:';
			$s = strlen("$data_id");
			$data_string .= $s.':"'.$data_id.'"%';

			$notifications = $this->find('all', array(
				'recursive' => -1,
				'conditions' => array(
					'Notification.controller_name' => $controller,
					'Notification.action_name' => $action,
					'Notification.issuer LIKE' => $data_string,
					'Notification.read' => 0
					),
				'order' => 'Notification.id DESC'
				));

			if (empty($notifications)){
				$result['success']['message'] = 'Notifikacija je vec oznacena kao procitana.';
			}else{
				foreach ($notifications as $notification) {
					$this->id = $notification['Notification']['id'];
					if (!$this->saveField('read', 1)) throw new Exception('Error Processing Notification');
					$result['success']['message'] = 'Notifikacija je oznacena kao procitana.';
				}
			}
		}catch(Exception $e){
			$result['error'] = $e->getMessage();
		}

		if (isset($result['error'])){
			$dataSource->rollBack();
		}else{
			$dataSource->commit();
		}

		return $result;
	}//~!

	/**
	 * Count unread notifications
	 *
	 * @author Vladislav Hristodulo <vladislav.hristodulo@mikroe.com>
	 * @since 24.06.2019
	 * @param int $userId
	 * @return int
	 */
	public function countUnreadNotifications($userId) {
		$unreadNotificationCount = $this->find('count', array(
			'conditions' => array(
				'Notification.user_id' => $userId,
				'Notification.read' => 0
			),
			'recursive' => -1
		));
		return $unreadNotificationCount;
	}//~!

	/**
	 * Get last notifications for user
	 *
	 * @author Vladislav Hristodulo <vladislav.hristodulo@mikroe.com>
	 * @since 24.06.2019
	 * @param int $userId
	 * @return array
	 */
	public function getLastNotifications($userId) {
		$notifications = $this->find('all', array(
			'conditions' => array(
				'Notification.user_id' => $userId
			),
			'order' => array('Notification.created' => 'desc'),
			'limit' => 10,
			'recursive' => -1
		));
		foreach ($notifications as $key => $value) {
			$issuer = unserialize($value['Notification']['issuer']);
			if (isset($issuer['document_id'])) $notifications[$key]['Notification']['document_id'] = $issuer['document_id'];
			if (isset($issuer['document_code'])) $notifications[$key]['Notification']['document_code'] = $issuer['document_code'];
			if (isset($issuer['document_title'])) $notifications[$key]['Notification']['document_title'] = $issuer['document_title'];
			if (isset($issuer['link_controller'])) $notifications[$key]['Notification']['link_controller'] = $issuer['link_controller'];
			if (isset($issuer['link_action'])) $notifications[$key]['Notification']['link_action'] = $issuer['link_action'];
		}
		return $notifications;
	}//~!
}	
?>