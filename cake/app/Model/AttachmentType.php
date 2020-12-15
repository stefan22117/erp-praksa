<?php
class AttachmentType extends AppModel{
	var $name = 'AttachmentType';

	public $validate = array(
		'type' => array(
			'typeNotEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Molimo unesite tip priloga'
			),
			'typeUnique' => array(
				'rule' => 'isUnique',
				'message' => 'Tip priloga mora biti jedinstven'
			)
		)
	);
}
?>