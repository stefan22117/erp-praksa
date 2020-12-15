<?php
class ErpLogManagementComponent extends Component{

	//$model = ClassRegistry::init('ErpLog');
	var $components = array('RequestHandler');

	public function erplog($user_id, $controller, $action, $input_data, $input_data_source, $description){
	$this->ErpLog = ClassRegistry::init('ErpLog');
	
	$data['ErpLog']['user_id'] = $user_id;
	$data['ErpLog']['controller'] = $controller;
	$data['ErpLog']['action'] = $action;
	$data['ErpLog']['input_data'] = $input_data;
	$data['ErpLog']['input_data_source'] = $input_data_source;
	$data['ErpLog']['description'] = $description;
	$data['ErpLog']['ip_address'] = $this->RequestHandler->getClientIp();

    $this->ErpLog->create();
	$this->ErpLog->save($data);
}
}
?>