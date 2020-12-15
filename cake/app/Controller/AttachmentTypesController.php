<?php
class AttachmentTypesController extends AppController {
	var $name = 'AttachmentTypes';

	public $components = array('ErpLogManagement', 'Paginator', 'Search');

	/**
	 * @author Boris Urosevic - boris.urosevic@mikroe.com
	 * @since 26.03.2018
	*/
	public function index(){
		$this->set('title_for_layout', __('Tipovi priloga'));

		$this->Paginator->settings = array(
			'recursive' => -1,
			'limit' => 20,
			'fields' => 'AttachmentType.type',
			'order' => 'AttachmentType.created DESC'
		);
		$attachmentTypes = $this->Paginator->paginate('AttachmentType');

		$this->set('attachmentTypes', $attachmentTypes);
	}//~!

    /**
     * save
     * @author Boris Urosevic - boris.urosevic@mikroe.com
	 * @since 26.03.2018
	 * @param int $id - ID of attachment type
     */
	public function save($attachment_type_id = null){
		$this->set('title_for_layout', __('Tip priloga'));

		if ( $this->request->is('post') || $this->request->is('put') ){
			if ( $this->AttachmentType->save( $this->request->data ) ){
				$this->Session->setFlash( __('Tip priloga je uspešno dodat.'), 'flash_success' );
				return $this->redirect(array('controller' => 'AttachmentTypes', 'action' => 'index'));
			}else{
				$this->Session->setFlash( __('Tip priloga nije uspešno dodat.'), 'flash_error' );
			}
		}

		$this->request->data = $this->AttachmentType->find('first', array(
			'recursive' => -1,
			'conditions' => array('AttachmentType.id' => $attachment_type_id)
		));
	}//~!
	
}
