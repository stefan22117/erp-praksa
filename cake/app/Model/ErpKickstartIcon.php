<?php
class ErpKickstartIcon extends AppModel{
	var $name = 'ErpKickstartIcon'; 

    public $virtualFields = array('title' => 'CONCAT("<i class=\"", ErpKickstartIcon.icon_class, "\"></i> ", ErpKickstartIcon.icon_name)');

    public $validate = array(
        'icon_name' => array(
            'iconNameRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Naziv ikonice nije definisan',
                'required' => true
            ),
            'iconNameRule2' => array(
                'rule' => 'isUnique',
                'message' => 'Naziv ikonice nije jedinstven',
                'required' => true
            )
        ),
        'icon_class' => array(
            'iconClassRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Klasa ikonice nije definisana',
                'required' => true
            ),
            'iconClassRule2' => array(
                'rule' => 'isUnique',
                'message' => 'Klasa ikonice nije jedinstvena',
                'required' => true
            )
        )        
	);
}
?>