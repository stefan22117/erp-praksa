<?php
class ErpLog extends AppModel {
	var $name = 'ErpLog';
	var $order = array('ErpLog.created DESC', 'ErpLog.id DESC');

	public $belongsTo = array(
	        'User' => array(
	            'className' => 'User',
	            'foreignKey' => 'user_id'
	        )   
	    );
	}
			
?>

