<?php
/**
 * @author Marko Jovanovic
 * 
 */
App::uses('AppShell', 'Console/Command');
App::uses('CakeEmail', 'Network/Email');

class QueueEmailResponseTask extends AppShell {

	public $timeout = 120;

	public $retries = 1;

/**
 * @var bool
 */
	public $autoUnserialize = true;

/**
 * "Add" the task, not possible for QueueEmailTask
 *
 * @return void
 */
	public function add() {
		$this->err('Queue Email Response Task cannot be added via Console.');
		$this->out('Please use createJob() on the QueuedTask Model to create a Proper Email Task.');
		$this->out('The Data Array should look something like this:');
		$this->out(var_export(array(
			'settings' => array(
				'to' => 'email@example.com',				
				'bcc' => 'optional array with emails',
				'title' => 'Email Subject',
				'view' => 'Controller/Action',
				'transfer_protocol' => 'optional (defaults to smtp)',
				'layout' => 'optional (defaults to simple-basic)',
				'format' => 'optional (defaults to html)',
				'reply_to' => 'optional'
			),
			'vars' => array(
				'data' => 'array with data needed for view rendering',
			)
		), true));
	}

/**
 * QueueEmailTask::run()
 *
 * @param mixed $data Job data
 * @return bool Success
 */
	public function run($data) {
		//Check if data is set
		if (!isset($data['settings']) || !isset($data['vars']['data'])) {
			$this->err('Queue Email Response task called without required data.');
			return false;
		}
		//Create new cake email object
		$email = new CakeEmail();
		//Check transfer protocol, if does not exist set default (smtp)
		if(empty($data['settings']['transfer_protocol'])) {
			$data['settings']['transfer_protocol'] = 'smtp';
		}
		//Set transfer protocol		
		$email->config($data['settings']['transfer_protocol']);
		//Set recipient
		$email->to($data['settings']['to']);
		//Check for reply to header field
		if(!empty($data['settings']['reply_to'])) $email->replyTo($data['settings']['reply_to']);
		//Check for bcc header field
		if(!empty($data['settings']['bcc'])) $email->bcc(explode(',', $data['settings']['bcc']));
		//Set attachments if any
		if(!empty($data['settings']['attachment'])) $email->attachments($data['settings']['attachment']);
		//Check if it is plain text wrapped in html
		$is_plain_html = !empty($data['settings']['plainAsHtml']) ? true : false;
		//Set email subject
		$email->subject($data['settings']['title']);
		//Set email format (plain or html)
		if(empty($data['settings']['format'])) $data['settings']['format'] = 'html';
		$email->emailFormat($data['settings']['format']);
		//Check if html
		if ($data['settings']['format'] == 'html'){
			//If not html wrapper, send regular html email
			if(!$is_plain_html){
				//Set layout
				if(empty($data['settings']['layout'])) $data['settings']['layout'] = 'simple-basic';
				//Set view
				if(empty($data['settings']['view'])) $data['settings']['view'] = 'default';
				//Set template
				$email->template($data['settings']['view'], $data['settings']['layout']);
				//Set variables 
				$email->viewVars(array('data' => $data['vars']['data'], 'email_title' => $data['settings']['title']));
				//Send email
				return $email->send();
			}else{
				//Set default layout and view for html wrapper
				$data['settings']['layout'] = 'default';
				$data['settings']['view'] = 'default';
				$email->template($data['settings']['view'], $data['settings']['layout']);
				//Set email title
				$email->viewVars(array('email_title' => $data['settings']['title']));
				//Send plain text using html
				return $email->send($data['vars']['data']);
			}
		}else{
			//If plain, send directly
			return $email->send($data['vars']['data']);
		}		
	}//~!

/**
 * Log message
 *
 * @param array $contents log-data
 * @param mixed $log int for loglevel, array for merge with log-data
 * @return void
 */
	protected function _log($contents, $log) {
		$config = array(
			'level' => LOG_DEBUG,
			'scope' => 'email'
		);
		if ($log !== true) {
			if (!is_array($log)) {
				$log = array('level' => $log);
			}
			$config = array_merge($config, $log);
		}
		CakeLog::write(
			$config['level'],
			PHP_EOL . $contents['headers'] . PHP_EOL . $contents['message'],
			$config['scope']
		);
	}

}
