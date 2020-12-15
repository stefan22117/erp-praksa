<?php
App::uses('AuthComponent', 'Controller/Component');

class MenuElement extends AppModel {
	var $name='MenuElement';
	
	public $belongsTo = array(
        'Menu' => array(
            'className' => 'Menu',
            'foreignKey' => 'menu_id'
        )
    );

	public $validate = array(
		'menu_id' => array(
            'menuIdRule' => array(
                'rule' => 'notEmpty',
                'message' => 'Menu is required'
            )
        ),
        'title' => array(
            'titleRule' => array(
                'rule' => 'notEmpty',
                'message' => 'Menu element title is required'
            )
        ),
        'controller' => array(
            'controllerRule' => array(
                'rule' => 'notEmpty',
                'message' => 'Controller is required'
            )
        ),
        'action' => array(
            'actionRule' => array(
                'rule' => 'notEmpty',
                'message' => 'Method is required'
            )
        )
        
    );
}
?>