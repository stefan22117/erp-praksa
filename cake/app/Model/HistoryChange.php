<?php
class HistoryChange extends AppModel{
	var $name = 'HistoryChange';

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);

	public $validate = array(
		'user_id' => array(
			'userIdExists' => array(
				'rule' => array('userIdExistsValidation'),
				'message' => 'Nepostojeći korisnik.'
			)
		),
		'change_description' => array(
			'changeDescriptionNotEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Opis promene nije popunjen.'
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
	 * Save history chage
	 * @author Boris Urosevic - boris.urosevic@mikroe.com
	 * @since 05.08.2019
	 * @param string $model - Name of model
	 * @param int $model_id - ID of model
	 * @param int $foreign_key - ID of foreign model
	 * @return array $result success[boolean] and message[string]
	*/
	public function saveChange($model, $model_id, $change_description, $foreign_key = null, $user_id = null){
		// Set user
		if ( empty( $user_id ) ){
			$user_id = AuthComponent::user('id');
		}

		// Set data
		$data['HistoryChange']['model'] = $model;
		$data['HistoryChange']['model_id'] = $model_id;
		$data['HistoryChange']['foreign_key'] = $foreign_key;
		$data['HistoryChange']['change_description'] = $change_description;
		$data['HistoryChange']['user_id'] = $user_id;

		// Create change
		$this->create();
		if ( $this->save( $data ) ){
			$result['success'] = true;
			$result['message'] = __('Istorija promena je uspešno sačuvana.');
		}else{
			// First validation error
			$error = $this->findValidationError( $this->validationErorrs );

			$result['success'] = false;
			$result['message'] = __('Istorija promena nije sačuvana. Molimo pokušajte ponovo. '.$error);
		}

		return $result;
	}//~!

	/**
	 * Get history changes
	 * @author Boris Urosevic - boris.urosevic@mikroe.com
	 * @since 05.08.2019
	 * @param int $model_id - ID of model
	 * @param string $model - Model
	 * @return array $historyChanges - History changes
	*/
	public function getHistoryChanges($model, $model_id){
		// History changes
		$historyChanges = $this->find('all', array(
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'users',
					'alias' => 'User',
					'conditions' => array('User.id = HistoryChange.user_id')
				)
			),
			'fields' => '*',
			'conditions' => array(
				'HistoryChange.model' => $model,
				'HistoryChange.model_id' => $model_id
			),
			'order' => 'HistoryChange.created DESC, HistoryChange.id DESC'
		));

		return $historyChanges;
	}//~!

}
?>