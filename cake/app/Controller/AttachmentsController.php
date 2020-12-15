<?php
class AttachmentsController extends AppController {

	public $components = array('ErpLogManagement', 'Paginator', 'Search');

    /**
     * view
     *
     * @throws nothing
     * @param string $model
     * @param int $foreign_key
     * @return void
     */
	public function view($model, $foreign_key) {
		//Disable caching
		$this->disableCache();
		
		//Set skip history push state for ajax
		$this->set('skip_history_push_state', true);
		
		//Check for model and foreign key
		if(!empty($model) && !empty($foreign_key)) {
			$this->Attachment->checkPermission($model, $foreign_key, 'view');
			$buttonShow = $this->Attachment->setButtons($model, $foreign_key);
			$this->set('buttonShow', $buttonShow);

			$attachments = $this->Attachment->find('all', array(
				'conditions' => array(
					'Attachment.model' => $model,
					'Attachment.foreign_key' => $foreign_key,
					'Attachment.deleted' => 0
				),
				'contain' => array('User', 'AttachmentType'),
				'recursive' => -1
			));

			$this->set('model', $model);
			$this->set('foreignKey', $foreign_key);
			$this->set('attachments', $attachments);

			//Check for query container query
			$container_id = !empty($this->request->query['container_id']) ? $this->request->query['container_id'] : null;
			$this->set('container_id', $container_id);
		}
	}//~!

    /**
     * save
     *
     * @throws nothing
     * @param string $model
     * @param int $foreign_key
     * @param int $id
     * @return void
     */
	public function save($model, $foreign_key, $id = null, $callback = null) {
		if($id == 'undefined') $id = null;
		// Set data for dropdown //

		$conditions = array();

		$this->loadModel( $model );
		if ( isset( $this->{$model}->notAllowedAttachmentTypes ) ){
			$conditions['AttachmentType.id !='] = $this->{$model}->notAllowedAttachmentTypes;
		}

		$attachmentTypes = $this->Attachment->AttachmentType->find('list', array(
			'fields' => array('AttachmentType.id', 'AttachmentType.type'),
			'conditions' => $conditions,
			'recursive' => -1
		));
		$this->set('attachmentTypes', $attachmentTypes);

		//Check for query container query
		$container_id = !empty($this->request->query['container_id']) ? $this->request->query['container_id'] : null;
		$this->set('container_id', $container_id);

		// Init settings //
		$settings = array(
			'conditions' => array(
				'Attachment.model' => $model,
				'Attachment.foreign_key' => $foreign_key,
				'Attachment.deleted' => 0
			),
			'recursive' => -1
		);
		if(!empty($model) && !empty($foreign_key)) {
			$this->Attachment->checkPermission($model, $foreign_key, 'save');
			$buttonShow = $this->Attachment->setButtons($model, $foreign_key);
			$this->set('buttonShow', $buttonShow);
			$this->Attachment->setAllowedFileTypes($model, $foreign_key);

			if(empty($id)) {
				// Set action name for view //
				$this->set('action', 'add');
				// Create model //
				$this->Attachment->create();
			} else {
				// Set action name for view //
				$this->set('action', 'edit');
			}

			if ($this->request->is(array('post', 'put'))) {
				$this->request->data['Attachment']['user_id'] = $this->user['id'];
				$this->request->data['Attachment']['model'] = $model;
				$this->request->data['Attachment']['foreign_key'] = $foreign_key;

				// Set file for upload //
				$file = array();
				// Get temp server name of file //
				$file['tmpName'] = $this->data['Attachment']['upload']['tmp_name'];
				$file['name'] = $this->data['Attachment']['upload']['name'];
				// Get file type //
				$file['type'] = $this->data['Attachment']['upload']['type'];

				// Check if file type is in proper format //
				$typeProperFormat = false;
				foreach ($this->Attachment->allowed_file_types as $extension => $mime_types) {
					foreach ($mime_types as $mime_type) {
						// Check MIME type //
						$finfo = finfo_open(FILEINFO_MIME_TYPE);
						if (finfo_file($finfo, $file['tmpName']) == $mime_type) {
							$typeProperFormat = true;
							break;
						}
					}
					// Get extension //
					$ext = strtolower('.'.pathinfo($file['name'], PATHINFO_EXTENSION));
					// Check extension //
					if($ext != $extension) {
						$typeProperFormat = false;
						continue;
					} else {
						if ($typeProperFormat) break;
					}
				}
				if (!$typeProperFormat) {
					$this->Session->setFlash(__('Greška u tipu fajla'), 'flash_error');
					$settings = array(
						'conditions' => array(
							'Attachment.model' => $model,
							'Attachment.foreign_key' => $foreign_key,
							'Attachment.deleted' => 0
						),
						'contain' => array('User', 'AttachmentType'),
						'recursive' => -1
					);
					$this->set('model', $model);
					$this->set('foreignKey', $foreign_key);
					$this->set('attachments', $this->Attachment->find('all', $settings));
					return $this->render('view', 'ajax');
				}

				// Save data //
				try {
					$this->Attachment->saveAttachment($file, $this->request->data, $id);

					// Get user //
					$user = $this->user;

					// Save action log //
					$input_data = serialize($this->request->data);
		            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The Attachment has been saved');

		            // Set message and redirect to index page //
					$this->Session->setFlash(__('Prilog je uspešno sačuvan.'), 'flash_success');

					// Callback function //
					if(method_exists($this->{$model}, $callback)) {
						$this->{$model}->{$callback}($this->Attachment->id);
					}

					// Init settings //
					$settings = array(
						'conditions' => array(
							'Attachment.model' => $model,
							'Attachment.foreign_key' => $foreign_key,
							'Attachment.deleted' => 0
						),
						'contain' => array('User', 'AttachmentType'),
						'recursive' => -1
					);

					// Set data for view //
					$this->set('model', $model);
					$this->set('foreignKey', $foreign_key);
					$this->set('attachments', $this->Attachment->find('all', $settings));

					return $this->render('view', 'ajax');
				} catch (Exception $e) {
					// Set message //
					$this->Session->setFlash($e->getMessage(), 'flash_error');

				}
			}
		}

		$this->set('model', $model);
		$this->set('foreignKey', $foreign_key);
		$this->set('allowedFileTypes', $this->Attachment->allowed_file_types);
		$this->render('save', 'ajax');
	}//~!

    /**
     * delete
     *
     * @throws nothing
     * @param string $model
     * @param int $foreign_key
     * @param int $id
     * @return void
     */
	public function delete($model, $foreign_key, $id, $callback = null) {
		if(!empty($id)) {
			$this->Attachment->checkPermission($model, $foreign_key, 'delete');
			$buttonShow = $this->Attachment->setButtons($model, $foreign_key);
			$this->set('buttonShow', $buttonShow);
			if($this->Attachment->remove($id)) {
				// Get user //
				$user = $this->user;

				// Save action log //
				$input_data = serialize($this->request->data);
	            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The Attachment has been deleted');

	            // Set message and redirect to index page //
				$this->Session->setFlash(__('Prilog je uspešno izbrisan.'), 'flash_success');

				// Callback function //
				if(method_exists($this->{$model}, $callback)) {
					$this->{$model}->{$callback}($this->Attachment->id);
				}

				// Init settings //
				$settings = array(
					'conditions' => array(
						'Attachment.model' => $model,
						'Attachment.foreign_key' => $foreign_key,
						'Attachment.deleted' => 0
					),
					'contain' => array('User', 'AttachmentType'),
					'recursive' => -1
				);

				// Set data for view //
				$this->set('model', $model);
				$this->set('foreignKey', $foreign_key);
				$this->set('attachments', $this->Attachment->find('all', $settings));

				//Check for query container query
				$container_id = !empty($this->request->query['container_id']) ? $this->request->query['container_id'] : null;
				$this->set('container_id', $container_id);

				return $this->render('view', 'ajax');
			}
		}
	}//~!

    /**
     * download
     *
     * @throws nothing
     * @param int $id
     * @return void
     */
	public function download($id) {
		if(!empty($id)) {
			// Get model and foreign_key //
			$attachment = $this->Attachment->get($id);
			$model = $attachment['Attachment']['model'];
			$foreign_key = $attachment['Attachment']['foreign_key'];
			$this->Attachment->checkPermission($model, $foreign_key, 'download');
			$buttonShow = $this->Attachment->setButtons($model, $foreign_key);
			$this->set('buttonShow', $buttonShow);
			$download = true;
			$extension = substr($attachment['Attachment']['file_name'], strrpos($attachment['Attachment']['file_name'], '.') + 1);
			if($extension == 'pdf') $download = false;
			$this->response->file($attachment['Attachment']['link'], array('name' => $attachment['Attachment']['file_name'], 'download' => $download));
			return $this->response;
		}
	}//~!
	
}
