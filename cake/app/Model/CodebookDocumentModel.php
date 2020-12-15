<?php
class CodebookDocumentModel extends AppModel{
	var $name = 'CodebookDocumentModel';

	public $validate = array(
		'model' => array(
			'modelNotEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Molimo unesite model'
			)
		),
		'document' => array(
			'documentNotEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Molimo unesite dokument'
			)
		)
	);

	public $hasMany = array(
		'CodebookDocumentModelConnection' => array(
			'className' => 'CodebookDocumentModelConnection',
			'foreignKey' => 'codebook_document_model_id'
		)
	);
}
?>