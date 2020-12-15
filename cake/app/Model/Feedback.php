<?php
class Feedback extends AppModel {

    // Variables

	var $name = 'Feedback';

    var $order = array('Feedback.created DESC', 'Feedback.id DESC'); 

    var $statuses = array(
        'open' => 'otvoren',
        'working_on' => 'rešava se',
        'closed' => 'rešen',
        'postponed' => 'odložen'
    );

    // Associations

	public $belongsTo = array(
        'Aco' => array(
        	'className' => 'Aco',
        	'foreignKey' => 'aco_id'
        ),
        'User' => array(
        	'className' => 'User',
        	'foreignKey' => 'user_id'
        ),
        'UserWorking' => array(
            'className' => 'User',
            'foreignKey' => 'user_working_id'
        ),
        'UserClosed' => array(
            'className' => 'User',
            'foreignKey' => 'user_closed_id'
        )
    );

    public $hasMany = array(
        'FeedbackComment' => array(
            'className' => 'FeedbackComment',
            'foreignKey' => 'feedback_id'
        )
    );

    // Behaviors

    public $actsAs = array('Containable');

    // Validation

    public $validate = array(
        'comment' => array(
            'commentRule' => array(
                'rule' => 'notEmpty',
                'message' => 'Niste uneli komentar.'
            )
        )
    );

    /**
     * Check dates for filter
     *
     * @since 2016-06-27
     * @throws nothing
     * @param string date $from
     * @param string date $to
     * @return array
     */
    public function checkFilterDates($from, $to){
        $result = array();
        $today = strtotime(date('Y-m-d'));
        if(!empty($to)){
            $to = strtotime($to);
            if($to > $today){
                $result['to_error'] = __('Do: Ne možete birati datum u budućnosti!');
                $result['error'] = $result['to_error'];
            }
        }
        if(!empty($from)){
            $from = strtotime($from);
            if(($from > $today)){
                $result['from_error'] = __('Od: Ne možete birati datum veći od danjašnjeg datuma!');
            }
            if(!empty($to) && $from > $to){
                $result['from_error'] = __('Od: Ne možete birati datum veći od datuma Do!');
            }
            if(isset($result['from_error'])){
                if(isset($result['error'])){
                    $result['error'] .= $result['from_error'];
                }else{
                    $result['error'] = $result['from_error'];
                }
            }
        }
        return $result;
    }//~!

    // Getters

    /**
     * Get all users who added fedback
     *
     * @since 2016-06-27
     * @throws nothing
     * @param none
     * @return array
     */
    public function getFeedbackCreators(){
        $users = array();
        $distinct_users = $this->find('all', array(
            'recursive' => -1,
            'fields' => 'DISTINCT Feedback.user_id'
            ));
        foreach ($distinct_users as $user) {
            $users[] = $user['Feedback']['user_id'];
        }
        return $users;
    }//~!

}
