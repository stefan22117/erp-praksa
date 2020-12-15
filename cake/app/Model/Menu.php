<?php

class Menu extends AppModel {
	var $name = 'Menu';

	public $belongsTo = array(
		'Group' => array(
		  'className' => 'Group',
		  'foreignKey' => 'group_id'
		),
		'User' => array(
		  'className' => 'User',
		  'foreignKey' => 'user_id'
		)
	);

    public $hasMany = array(
        'MenuElement' => array(
            'className' => 'MenuElement',
            'foreignKey' => 'menu_id',
            'dependent' => true
        )
    );

	public $validate = array(
		'title' => array(
            'titleRule' => array(
                'rule' => 'notEmpty',
                'message' => 'Menu title is required'
            )
        ),
        'group_id' => array(
            'check_id' => array(
                'rule' => 'hasId',
                'message' => 'Name of the group is required'
            )
        ),
        'user_id' => array(
            'check_id' => array(
                'rule' => 'hasId',
                'message' => 'Name of the user is required'
            )
        ),
        'allowed' => array(
            'allowedRule' => array(
                'rule' => 'notEmpty',
                'message' => 'Choose one option'
            )
        )
        
    );

    function hasId($field){
        if(!empty($this->data[$this->name]['user_id']) || !empty($this->data[$this->name]['group_id'])){
            return true;
        } else {
            return false;
        }
    }
}