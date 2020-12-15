<?php
class CommentsController extends AppController{
	var $name = 'Comments';

	public $components = array('ErpLogManagement', 'Paginator', 'Search');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('saveComment');
	}//~!

	/**
	 * Save comment
	 * @author Boris Urosevic - boris.urosevic@mikroe.com
	 * @since 03.04.2018
	*/
	public function saveComment(){
		// Check if user is logged in
		if ( empty( $this->user['id'] ) ){
			$this->Session->setFlash( __('Morate biti ulogovani da biste komentarisali'), 'flash_error' );
			return $this->redirect('/');
		}

		// Check if request is ajax
		if ( $this->request->is('ajax') ){

			// Save comment
			$result = $this->Comment->saveComment( $this->request->data );

			$model = $this->request->data['Comment']['model'];
			$this->loadModel( $model );
			if ( method_exists( $this->{$model}, 'afterSaveComment' ) ) {
				$this->$model->afterSaveComment( $this->Comment->id );
			}

			$this->set('result', $result);
			$this->set('_serialize', 'result');
		}else{
			$this->Session->setFlash( __('Nepostojeća stranica'), 'flash_error' );
			return $this->redirect('/');
		}
	}//~!

	/**
	 * Get comments for selected model
	 * @author Boris Urosevic - boris.urosevic@mikroe.com
	 * @since 16.08.2019
	 * @param string $model - Name of model
	 * @param int $model_id - ID of model
	*/
	public function getComments($modeL, $model_id){
		// Check if request is ajax
		if ( $this->request->is('ajax') ){
			// Comments
			$this->Paginator->settings = array(
				'recursive' => -1,
				'joins' => array(
					array(
						'table' => 'users',
						'alias' => 'User',
						'type' => 'left',
						'conditions' => array('User.id = Comment.user_id')
					)
				),
				'fields' => '*',
				'conditions' => array(
					'Comment.model' => $model,
					'Comment.model_id' => $model_id
				),
				'limit' => 20
			);
			$comments = $this->paginate('Comment');
			$this->set('comments', $comments);

		}else{
			$this->Session->setFlash( __('Nepostojeća stranica'), 'flash_error' );
			return $this->redirect('/');
		}
	}//~!
}
?>