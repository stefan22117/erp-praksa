<?php
class FeedbackComment extends AppModel{
	var $name = 'FeedbackComment'; 
    var $order = array('FeedbackComment.created DESC', 'FeedbackComment.id DESC');

	public $belongsTo = array(
        'User' => array(
        	'className' => 'User',
        	'foreignKey' => 'user_id'
        ),
        'Feedback' => array(
            'className' => 'Feedback',
            'foreignKey' => 'feedback_id'
        )
    );

    public $validate = array(
        'comment' => array(
            'commentRule' => array(
                'rule' => 'notEmpty',
                'message' => 'Niste uneli komentar.'
            )
        )
    );
}
?>