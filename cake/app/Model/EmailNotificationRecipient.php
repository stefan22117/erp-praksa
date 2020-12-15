<?php
class EmailNotificationRecipient extends AppModel{
	var $name = 'EmailNotificationRecipient';

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);

	public $validate = array(
		'function_tag' => array(
			'functionTagNotEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'message' => 'Molimo unesite tag funkcije'
			)
		),
		'description' => array(
			'descriptionNotEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'message' => 'Molimo unesite opis'
			)
		),
		'user_id' => array(
			'userIdExists' => array(
				'rule' => 'userIdExistsValidation',
				'allowEmpty' => true,
				'message' => 'Korisnik nije definisan'
			)
		),
		'email' => array(
			'emailEmail' => array(
				'rule' => 'email',
				'allowEmpty' => true,
				'message' => 'Email nije validan'
			)
		)
	);

	/**
	 * Check for user existance
	 * @param array
	 * @return boolean
	*/
	public function userIdExistsValidation($check){
		return $this->User->exists( $check['user_id'] );
	}//~!

	/**
	 * Get list of email for selected tag
	 * @author Boris Urosevic - boris.urosevic@mikroe.com
	 * @since 23.05.2019
	 * @param string $function_tag - Function tag to get emails
	 * @return array $emails - List of emails
	*/
	public function getEmails($function_tag = null){
		$conditions = array();

		if ( !empty( $function_tag ) ){
			$conditions['EmailNotificationRecipient.function_tag'] = $function_tag;
		}

		$sistemEmails = $this->find('list', array(
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'users',
					'alias' => 'User',
					'conditions' => array('User.id = EmailNotificationRecipient.user_id')
				)
			),
			'fields' => 'User.email, User.email',
			'conditions' => $conditions
		));

		$manualEmails = $this->find('list', array(
			'recursive' => -1,
			'fields' => 'EmailNotificationRecipient.email, EmailNotificationRecipient.email',
			'conditions' => array(
				'EmailNotificationRecipient.function_tag' => $function_tag,
				'EmailNotificationRecipient.email is not null',
				'EmailNotificationRecipient.email !=' => ''
			)
		));

		// Objedinjena lista svih emailova
		$emails = array_merge($sistemEmails, $manualEmails);

		return $emails;
	}//~!

	/** 
	 * Get list of all function tags
	 * @author Boris Urosevic <boris.urosevic@mikroe.com>
	 * @since 24.04.2020
	 * @return array $functionTags  Function tags
	 */
	public function getFunctionTags(){
		$functionTags = $this->find('list', array(
			'recursive' => -1,
			'fields' => 'EmailNotificationRecipient.function_tag, EmailNotificationRecipient.function_tag',
			'group' => 'EmailNotificationRecipient.function_tag'
		));

		return $functionTags;
	}//~!
}
?>