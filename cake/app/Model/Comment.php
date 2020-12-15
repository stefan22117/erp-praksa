<?php
class Comment extends AppModel{
	var $name = 'Comment';

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);

	public $validate = array(
		'user_id' => array(
			'userIdNotEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Korisnik nije definisan'
			),
			'userIdExists' => array(
				'rule' => array('userIdExistsValidation'),
				'message' => 'Nepostojeći korisnik'
			)
		),
		'comment' => array(
			'commentNotEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Molimo unesite komentar'
			)
		)
	);

	/**
	 * Check for costing type existance
	 * @param array
	 * @return boolean
	*/
	public function userIdExistsValidation($check){
		return $this->User->exists( $check['user_id'] );
	}//~!

	/**
	 * Get comments for specific model
	 * @author Boris Urosevic - boris.urosevic@mikroe.com
	 * @since 09.11.2017
	 * @param string $model - document
	 * @param int $model_id - id of document
	 * @return array $comments
	*/
	public function getComments($model, $model_id){
		$comments = $this->find('all', array(
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'users',
					'alias' => 'User',
					'conditions' => array('User.id = Comment.user_id')
				)
			),
			'fields' => 'User.id, User.username, User.first_name, User.last_name, User.avatar_link, Comment.*',
			'conditions' => array(
				'Comment.model' => $model,
				'Comment.model_id' => $model_id,
				'Comment.deleted' => 0
			),
			'order' => 'Comment.created DESC'
		));

		return $comments;
	}//~!

	/**
	* Get one comment
	* @author Boris Urosevic - boris.urosevic@mikroe.com
	* @since 09.11.2017
	* @param int $comment_id - id of comment
	* @return array $result - Comment data
	*/
	public function getComment($comment_id){
		$result = $this->find('first', array(
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'users',
					'alias' => 'User',
					'conditions' => array('User.id = Comment.user_id')
				)
			),
			'fields' => 'Comment.*, User.id, User.username, User.first_name, User.last_name, User.avatar_link',
			'conditions' => array(
				'Comment.id' => $comment_id,
				'Comment.deleted' => 0
			)
		));
		$result['Comment']['created_beautify'] = date('d.M.Y H:i', strtotime( $result['Comment']['created'] ));

		return $result;
	}//~!

	/**
	 * Save comment
	 * @author Boris Urosevic - boris.urosevic@mikroe.com
	 * @since 09.11.2017
	 * @param array $data - data to save
	 * @return array $result - success[boolean] and message[string]
	*/
	public function saveComment($data){
		// Set user
		$data['Comment']['user_id'] = AuthComponent::user('id');

		// Save data
		if ( $this->save( $data ) ){
			$result = $this->getComment( $this->id );

			$result['success'] = true;
			$result['message'] = __('Komentar je uspešno snimljen');
		}else{
			$errors = $this->validationErrors;
			$result['success'] = false;
			$result['message'] = __('Komentar nije snimljen. ' . array_shift( $errors )[0] );
		}

		return $result;
	}//~!

	/**
	 * Delete comment
	 * @author Boris Urosevic - boris.urosevic@mikroe.com
	 * @since 09.11.2017
	 * @param int $comment_id - id of comment
	 * @return array $result - success['boolean'] and message[string]
	*/
	public function deleteComment($comment_id){
		$this->id = $comment_id;
		if ( $this->saveField('deleted', 1) ){
			$result['success'] = true;
			$result['message'] = __('Komentar je obrisan.');
		}else{
			$result['success'] = true;
			$result['message'] = __('Komentar nije obrisan. Molimo pokušajte ponovo.');
		}

		return $result;
	}//~!

	/**
	 * Get users who commented earlier
	 * @author Boris Urosevic <boris.urosevic@mikroe.com>
	 * @since 30.01.2020
	 * @param int $model_id - ID of comment
	 * @return array $commentUsers - List of users emails
	*/
	public function getCommentReceiveres($model_id){
		// E-mail receivers
		$commentUsers = $this->find('list', array(
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'users',
					'alias' => 'User',
					'conditions' => array('User.id = Comment.user_id')
				)
			),
			'fields' => 'User.email',
			'conditions' => array(
				'Comment.model_id' => $model_id,
				'Comment.model' => 'PoDocument'
			),
			'group' => 'User.email'
		));

		return $commentUsers;
	}//~!
}
?>