<?php
class HistoryChangesController extends AppController{
	var $name = 'HistoryChanges';

	public $components = array('ErpLogManagement', 'Paginator', 'Search');

	/**
	 * Get history changes for selected model
	 * @author Boris Urosevic - boris.urosevic@mikroe.com
	 * @since 19.08.2019
	 * @param string $model - Name of model
	 * @param int $model_id - ID of model
	*/
	public function gethistoryChanges($modeL, $model_id){
		// Check if request is ajax
		if ( $this->request->is('ajax') ){
			// History changes
			$this->Paginator->settings = array(
				'recursive' => -1,
				'joins' => array(
					array(
						'table' => 'users',
						'alias' => 'User',
						'type' => 'left',
						'conditions' => array('User.id = HistoryChange.user_id')
					)
				),
				'fields' => '*',
				'conditions' => array(
					'HistoryChange.model' => $model,
					'HistoryChange.model_id' => $model_id
				),
				'limit' => 20
			);
			$historyChanges = $this->paginate('HistoryChange');
			$this->set('historyChanges', $historyChanges);

		}else{
			$this->Session->setFlash( __('Nepostojeća stranica'), 'flash_error' );
			return $this->redirect('/');
		}
	}//~!
}
?>