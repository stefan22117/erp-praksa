<?php
class EmailNotificationRecipientsController extends AppController{
	var $name = 'EmailNotificationRecipients';

	public $components = array('ErpLogManagement', 'Paginator', 'Search');

	/**
	 * List of all email notification recipients
	 * @author Boris Urosevic - boris.urosevic@mikroe.com
	 * @since 23.05.2019
	*/
	public function index(){
		$this->set('title_for_layout', __('Pregled svih email notifikacija - MikroERP'));

		$conditions = array();

		// Function tag
		if ( !empty( $this->request->query['function_tag'] ) ){
			$conditions['EmailNotificationRecipient.function_tag'] = $this->request->query['function_tag'];
			$this->request->data['EmailNotificationRecipient']['function_tag'] = $this->request->query['function_tag'];
		}

		// Description
		if ( !empty( $this->request->query['description'] ) ){
			$description = $this->Search->formatSearchString( $this->request->query['description'] );
			$conditions['EmailNotificationRecipient.description LIKE'] = $description;
			$this->request->data['EmailNotificationRecipient']['description'] = $this->request->query['description'];
		}

		// Email recipient
		if ( !empty( $this->request->query['email'] ) ){
			$conditions['OR']['User.email LIKE'] = '%'.$this->request->query['email'].'%';
			$conditions['OR']['EmailNotificationRecipient.email LIKE'] = '%'.$this->request->query['email'].'%';
			$this->request->data['EmailNotificationRecipient']['email'] = $this->request->query['email'];
		}

		$this->Paginator->settings = array(
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'users',
					'alias' => 'User',
					'type' => 'left',
					'conditions' => array('User.id = EmailNotificationRecipient.user_id')
				)
			),
			'fields' => 'EmailNotificationRecipient.*, User.email',
			'conditions' => $conditions,
			'limit' => 20
		);
		$emailNotificationRecipients = $this->Paginator->paginate('EmailNotificationRecipient');
		$this->set('emailNotificationRecipients', $emailNotificationRecipients);

		// Function tags
		$functionTags = $this->EmailNotificationRecipient->getFunctionTags();
		$this->set('functionTags', $functionTags);

		// Emails
		$emails = $this->EmailNotificationRecipient->getEmails();
		$this->set('emails', $emails);
	}//~!

	/**
	 * Save email tag
	 * @author Boris Urosevic - boris.urosevic@mikroe.com
	 * @since 22.05.2019
	*/
	public function save(){
		$this->set('title_for_layout', __('Dodavanje email-a - MikroERP'));

		// Provera da li su podaci poslati
		if ( $this->request->is('post') || $this->request->is('put') ){
			if ( empty( $this->request->data['EmailNotificationRecipient']['user_id'] ) && empty( $this->request->data['EmailNotificationRecipient']['email'] ) ){
				$this->Session->setFlash( __('Mora biti odabran primalac ili mejl primaoca.'), 'flash_error' );
			}elseif ( $this->EmailNotificationRecipient->save( $this->request->data ) ){
				// Cuvanje podataka
				$this->Session->setFlash( __('Podaci su uspešno sačuvani.'), 'flash_success' );
				return $this->redirect(array('controller' => 'EmailNotificationRecipients', 'action' => 'index'));
			}
		}

		$this->set('users', $this->EmailNotificationRecipient->User->getAllUsers());
	}//~!

	/**
	 * Delete email tag
	 * @author Boris Urosevic - boris.urosevic@mikroe.com
	 * @since 22.05.2019
	 * @param int $email_notification_recipient_id - ID of email notification recipient
	*/
	public function delete($email_notification_recipient_id){
		if ( $this->EmailNotificationRecipient->delete( $email_notification_recipient_id ) ){
			$this->Session->setFlash( __('Podaci su uspešno obrisani.'), 'flash_success' );
		}else{
			$this->Session->setFlash( __('Podaci nisu obrisani.'), 'flash_error' );
		}

		return $this->redirect(array('controller' => 'EmailNotificationRecipients', 'action' => 'index'));
	}//~!
}
?>