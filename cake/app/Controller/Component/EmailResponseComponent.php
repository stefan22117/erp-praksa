<?php
App::uses('Component', 'Controller');
App::uses('CakeEmail', 'Network/Email');
class EmailResponseComponent extends Component {
    public function responseMeetingParticipant($participants, $title, $data, $view) {
		//Add email to job queue
		$this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
		$email_data['settings'] = array('to' => $participants, 'title' => $title, 'view' => $view);
		$email_data['vars']['data'] = $data;
		$this->QueuedTask->createJob('EmailResponse', $email_data);
    }//~!

    /**
	 * general html email function
	 * param $to - array with email addresses
	 * param $title - string email title
	 * param $data - data for view
	 * param $transfer_protocol - default null - smtp
	 * param $layout - default null - simple_basic
	 * @return void
	 */
    public function sendEmailHtml($to, $title, $data, $view, $transfer_protocol = null, $layout = null) { 
		//Add email to job queue
		$this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
		$email_data['settings'] = array('to' => $to, 'title' => $title, 'view' => $view, 'transfer_protocol' => $transfer_protocol, 'layout' => $layout);
		$email_data['vars']['data'] = $data;
		$this->QueuedTask->createJob('EmailResponse', $email_data);
    }//~!
   
}
?>