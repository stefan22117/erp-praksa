<?php
class FeedbacksController extends AppController {

    public $components = array('ErpLogManagement','Paginator','EmailResponse', 'Search');

    /**
     * beforeFilter
     *
     * @throws nothing
     * @param none
     * @return void
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $user = $this->Auth->user();
        if (!empty($user)){
            $aclChangeGroup = $this->Acl->check(
                array(
                    'model' => 'Group',
                    'foreign_key' => $user['group_id']
                ),
                'Feedbacks/change'
            );
            $aclChangeUser = $this->Acl->check(
                array(
                    'model' => 'User',
                    'foreign_key' => $user['id']
                ),
                'Feedbacks/change'
            );
            $this->set('aclChangeGroup', $aclChangeGroup, 'Feedbacks/change');
            $this->set('aclChangeUser', $aclChangeUser, 'Feedbacks/change');
        }
    }//~!

    /**
     * index
     *
     * @throws nothing
     * @param none
     * @return void
     */
    public function index(){
        # set title for layout
        $this->set('title_for_layout', __('Feedbacks - MikroERP'));
        
        # set statuses
        $this->set('statuses', $this->Feedback->statuses);

        $this->loadModel('ErpUnit');
        $this->ErpUnit->virtualFields = array('full_name' => 'CONCAT(ErpUnit.code," - ",ErpUnit.name)');
        $erpUnits = $this->ErpUnit->find('list', array(
            'fields' => array('id', 'full_name'),
            'conditions' => array(
                'ErpUnit.deleted' => 0
            )
        ));
        unset($this->ErpUnit->virtualFields);
        $erpUnits = array('Nedodeljen') + $erpUnits;
        $this->set('erpUnits', $erpUnits);

        $developers = $this->ErpUnit->ErpUnitDeveloper->ErpDeveloper->find('all', array(
            'contain' => array('User'),
            'recursive' => -1
        ));
        $userWorkings = array();
        foreach ($developers as $developer) {
            $userWorkings[$developer['User']['id']] = $developer['User']['first_name'].' '.$developer['User']['last_name'];
        }
        $userWorkingsFinal = array(0 => 'Nedodeljen');
        foreach ($userWorkings as $key => $userWorking) {
            $userWorkingsFinal[$key] = $userWorking;
        }
        $this->set('userWorkings', $userWorkingsFinal);

        #init values
        // get date of first feedback
        $first_feedback = $this->Feedback->find('first', array('recursive' => -1, 'fields' => 'DATE(Feedback.created) as created', 'order' => 'Feedback.created ASC'));
        $from = !empty($first_feedback[0]['created']) ? $first_feedback[0]['created'] : null; // date of first feedback
        $to = date('Y-m-d'); //current date
        $term = null;


        $selectedStatuses = array();
        $defaultStatuses = array('open', 'working_on');

        # set conditions and joins
        $conditions = array();
        $joins = array(
            array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Feedback.user_id = User.id'
                        )
                    )
            );
        
        # term
        if(isset($this->request->query['term']) && !empty($this->request->query['term'])){
            $term = $this->request->query['term'];
            $search = $this->Search->formatSearchString($term);

            $joins[] = array(
                    'table' => 'feedback_comments',
                    'alias' => 'FeedbackComment',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Feedback.id = FeedbackComment.feedback_id'
                        )
                    );


            $con_term['OR'] = array(
                'Feedback.description LIKE' => $search.'%',
                'Feedback.link LIKE' => $search.'%',
                'FeedbackComment.comment LIKE' => $search.'%'
                );

            $conditions += $con_term;
        }

        # erp module
        $erpUnitId = null;
        if (isset($this->request->query['erp_unit_id']) && !empty($this->request->query['erp_unit_id'])) {
            $erpUnitId = $this->request->query['erp_unit_id'];
            $erpUnitsIds = array();
            $erpUnitsIds[] = $erpUnitId;
            $this->loadModel('ErpUnit');
            $children = $this->ErpUnit->children($erpUnitId);
            foreach ($children as $child) {
                $erpUnitsIds[] = $child['ErpUnit']['id'];
            }
            $joins = array(
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'conditions' => array(
                        'Feedback.user_id = User.id'
                    )
                ),
                array(
                    'table' => 'acos',
                    'alias' => 'Aco',
                    'conditions' => array(
                        'Feedback.aco_id = Aco.id'
                    )
                ),
                array(
                    'table' => 'erp_unit_controllers',
                    'alias' => 'ErpUnitController',
                    'conditions' => array(
                        'ErpUnitController.aco_id = Aco.id'
                    )
                )
            );
            $conditions += array(
                'ErpUnitController.erp_unit_id' => $erpUnitsIds,
                'ErpUnitController.deleted' => 0
            );
        } else {
            if (isset($this->request->query['erp_unit_id']) && $this->request->query['erp_unit_id'] == '0') {
                $erpUnitId = 0;
                $this->loadModel('ErpUnit');
                $erpUnitControllers = $this->ErpUnit->ErpUnitController->find('all', array(
                    'conditions' => array(
                        'ErpUnitController.deleted' => 0
                    )
                ));
                $acoIds = array();
                foreach ($erpUnitControllers as $erpUnitController) {
                    $acoIds[] = $erpUnitController['ErpUnitController']['aco_id'];
                }
                $conditions += array(
                    'OR' => array(
                        array('Feedback.aco_id <>' => $acoIds),
                        array('Feedback.aco_id' => null)
                    )
                );
            }
        }

        # user working on feedback
        if (isset($this->request->query['user_working_id']) && !empty($this->request->query['user_working_id'])) {
            $conditions['Feedback.user_working_id'] = $this->request->query['user_working_id'];
            $this->request->data['Feedback']['user_working_id'] = $this->request->query['user_working_id'];
        } else {
            if (isset($this->request->query['user_working_id']) && $this->request->query['user_working_id'] == '0') {
                $conditions['Feedback.user_working_id'] = null;
                $this->request->data['Feedback']['user_working_id'] = $this->request->query['user_working_id'];
            }
        }

        # feedback date from
        if(isset($this->request->query['from']) && !empty($this->request->query['from'])){
            $from = $this->request->query['from'];
        }
        
        $conditions += array('DATE(Feedback.created) >=' => $from);

        # feedback date to
        if(isset($this->request->query['to']) && !empty($this->request->query['from'])){
            $to = $this->request->query['to'];
        }

        $conditions += array('DATE(Feedback.created) <=' => $to);

        
        
        # selected statuses
        if(isset($this->request->query['status']) && !empty($this->request->query['status'])){
            $selectedStatuses = $this->request->query['status'];
            $conditions += array('Feedback.status' => $selectedStatuses);
        }else{
            if (isset($defaultStatuses) && !empty($defaultStatuses)){
                $conditions += array('Feedback.status' => $defaultStatuses);
                $selectedStatuses = $defaultStatuses;
            }
        }

        # init user
        $user_id = null;
        $user_name = null;

        # if user set add condition
        if (isset($this->request->query['user_id']) && !empty($this->request->query['user_id'])){
            $user_id = intval($this->request->query['user_id']);
            # get user
            $user = $this->Feedback->User->find('first', array(
                'recursive' => -1,
                'fields' => array(
                    'User.first_name',
                    'User.last_name'
                    ),
                'conditions' => array(
                    'User.id' => $user_id
                    )
                ));
            $user_name = $user['User']['first_name'].' '.$user['User']['last_name'];

            $conditions += array('Feedback.user_id' => $user_id);
        }
        
        # check if user authorised
        if (!$this->Acl->check(array('model' => 'Group', 'foreign_key' => $this->user['group_id']), 'Feedbacks/change') && !$this->Acl->check(array('model' => 'User', 'foreign_key' => $this->user['id']), 'Feedbacks/change')){
            $conditions += array('Feedback.user_id' => $this->user['id']);
        }

        $this->Paginator->settings = array(
                'recursive' => -1,
                'fields' => array(
                    'Feedback.*',
                    'User.first_name',
                    'User.last_name',
                    'User.username',
                    'User.avatar_link',
                    ),
                'joins' => $joins,
                'limit' => 20,
                'conditions' => $conditions,
                'order' => 'created DESC',
                'group' => 'Feedback.id'
            );
        
        $feedbacks = $this->Paginator->paginate('Feedback');

        foreach ($feedbacks as $key => $value) {
            $feedbacks[$key]['Feedback']['short_url'] = $value['Feedback']['link'];
            if (strlen($value['Feedback']['link']) > 90) $feedbacks[$key]['Feedback']['short_url'] = substr($value['Feedback']['link'], 0, 90).'...';
            $acoId = $value['Feedback']['aco_id'];
            $this->loadModel('ErpUnitController');
            $erpUnitController = $this->ErpUnitController->find('first', array(
                'conditions' => array(
                    'ErpUnitController.aco_id' => $acoId,
                    'ErpUnitController.deleted' => 0
                ),
                'contain' => array('ErpUnit')
            ));
            $feedbacks[$key]['Feedback']['ErpUnit']['id'] = null;
            $feedbacks[$key]['Feedback']['ErpUnit']['code'] = null;
            $feedbacks[$key]['Feedback']['ErpUnit']['name'] = null;
            if(!empty($erpUnitController)) {
                $feedbacks[$key]['Feedback']['ErpUnit']['id'] = $erpUnitController['ErpUnit']['id'];
                $feedbacks[$key]['Feedback']['ErpUnit']['code'] = $erpUnitController['ErpUnit']['code'];
                $feedbacks[$key]['Feedback']['ErpUnit']['name'] = $erpUnitController['ErpUnit']['name'];
            }
        }

        $this->set('feedbacks', $feedbacks);

        $this->set('to', $to);
        $this->set('from', $from);
        $this->set('term', $term);
        $this->set('erpUnitId', $erpUnitId);
        $this->set('selectedStatuses', $selectedStatuses);
        $this->set('user_id', $user_id);
        $this->set('user_name', $user_name);
    }//~!

    /**
     * get users who added feedbacks
     * created: 2016-06-27
     * @param none
    */
    public function getUsersBySearch(){
        if ($this->request->is('ajax')){
            $this->disableCache();

            # get search term
            $term = $this->Search->formatSearchString($_REQUEST['term']).'%';

            # get all users who added feedbacks
            $feedback_creators = $this->Feedback->getFeedbackCreators();

            # search users
            $result = $this->Feedback->User->find('all', array(
                'recursive' => -1,
                'fields' => array(
                    'User.id',
                    'User.first_name',
                    'User.last_name'
                    ),
                'conditions' => array(
                    'User.id' => $feedback_creators,
                    'OR' => array(
                        'User.username LIKE' => $term, 
                        'User.first_name LIKE' => $term, 
                        'User.last_name LIKE' => $term 
                        )
                    )
                ));

            # set and serialize result
            $this->set('result', $result);
            $this->set('_serialize', 'result');
        }else{
            $this->Session->setFlash(__('Izabrali ste nepostojeću stranicu!'), 'flash_error');
            return $this->redirect('/');
        }
    }//~!

    /**
        * created: 2016-06-27
        * check if dates for filter valid
        * @param none
    */
    public function checkDates(){
        if($this->request->is('ajax')){
            $this->disableCache();
            $from = null;
            $to = null;
            if(!empty($_REQUEST['from'])) $from = $_REQUEST['from'];
            if(!empty($_REQUEST['to'])) $to = $_REQUEST['to'];
            $result = $this->Feedback->checkFilterDates($from, $to);
            $this->set('result', $result);
            $this->set('_serialize', 'result');
        }else{
            $this->Session->setFlash(__('Izabrali ste nepostojeću stranicu!'), 'flash_error');
            return $this->redirect('/');
        }
    }//~!

    /**
   * 
   * add feedback 
   * @param none
   * @throws exception if it's not ajax
    */
    public function add(){
        $result = array();
        if ($this->request->is('ajax')){
            $this->disableCache();
            $this->loadModel('ErpUnitController');
            $data['Feedback']['description'] = $_REQUEST['description'];
            $data['Feedback']['user_id'] = $this->user['id'];
            $data['Feedback']['link'] = $_SERVER['HTTP_REFERER'];
            $data['Feedback']['short_link'] = $_SERVER['HTTP_REFERER'];
            if (strlen($_SERVER['HTTP_REFERER']) > 90) $data['Feedback']['short_link'] = substr($_SERVER['HTTP_REFERER'], 0, 90).'...';
            $data['Feedback']['status'] = 'open';
            $data['Feedback']['server'] = $_SERVER['SERVER_NAME'];
            $data['Feedback']['port'] = $_SERVER['SERVER_PORT'];
            $data['Feedback']['aco_id'] = $this->ErpUnitController->getAcoIdFromLink($data['Feedback']['link']);
            $this->Feedback->create();
            if ($this->Feedback->save($data)){
                $user = $this->Feedback->User->find('first', array(
                    'recursive' => -1,
                    'fields' => 'full_name', 
                    'conditions' => array(
                        'User.id' => $this->user['id']
                        )
                    ));
                $data['Feedback']['id'] = $this->Feedback->id;
                $data['Feedback']['name'] = $user['User']['full_name'];
                //Load EmailNotificationRecipient model
                $this->loadModel('EmailNotificationRecipient');
                //Get list of feedback receivers
                $to = $this->EmailNotificationRecipient->getEmails('Feedback');
                //Check if list is not empty
                if(!empty($to)){
                    $title = 'MikroERP - New feedback';
                    $view = 'Feedbacks/add';
                    $transfer_protocol = 'smtp';
                    $layout = 'template_d';
                    $this->EmailResponse->sendEmailHtml($to, $title, $data, $view, $transfer_protocol, $layout);
                    $input_data = serialize($data);
                    $this->ErpLogManagement->erplog($this->user['id'], $this->params['controller'], $this->params['action'], $input_data, 'form', 'User added feedback.');
                    $result['success'] = 'Feedback je poslat. Hvala Vam na sugestiji.';
                }else{
                    $result['error'] = 'Greška! Feedback je snimljen ali je lista primaoca feedback-ova je prazna.';
                }
            }else{
                $result['error'] = 'Greška!';
            }
            $this->set('result', $result);
            $this->set('_serialize', 'result');
        }else{
            $this->Session->setFlash(__('Izabrali ste nepostojeću stranicu!'), 'flash_error');
            return $this->redirect('/');
        }
    }//~!

    

    public function view($id = null){
        try{
            $this->Feedback->id = $id;
            if (!$this->Feedback->exists() || $id == null) {
                throw new Exception('Izabrali ste nepostojeći feedback!');
            }
            $check = $this->Feedback->find('count', array('conditions' => array('Feedback.id'=>$id,'Feedback.user_id' => $this->user['id'])));
            $aclChange = $this->Acl->check(array('model' => 'Group', 'foreign_key' => $this->user['group_id']), 'Feedbacks/change');
            $aclChangeUser = $this->Acl->check(array('model' => 'User', 'foreign_key' => $this->user['id']), 'Feedbacks/change');
            if(!$aclChange && $check == 0 && !$aclChangeUser){
                throw new Exception('Nemate dozvolu da vidite ovaj feedback!');
            }
            $this->set('fb', $this->Feedback->read(null, $id));
            $us = $this->Feedback->query("select CONCAT(u.first_name, ' ', u.last_name) as ime, u.first_name
                                            from feedbacks f
                                            join users u on f.user_id = u.id
                                            where f.id = ".$id);
            $this->set('us', $us);
            $this->set('id',$id);
            //

            $this->Feedback->User->virtualFields = array('full_name' => 'CONCAT(first_name," ",last_name)');
            $users = $this->Feedback->User->find('list', array(
                'fields' => array('User.id', 'User.full_name'),
                'joins' => array(
                    array(
                        'table' => 'erp_developers',
                        'alias' => 'ErpDeveloper',
                        'conditions' => array('User.id = ErpDeveloper.user_id')
                    )
                ),
                'conditions' => array(
                    'ErpDeveloper.active' => 1,
                    'ErpDeveloper.deleted' => 0
                ),
                'recursive' => -1
            ));
            unset($this->Feedback->User->virtualFields);
            $this->set('users', $users);

            $controllers = array();
            $controllers = $this->Feedback->Aco->find('list', array(
                'fields' => array('Aco.id', 'Aco.alias'),
                'conditions' => array(
                    'Aco.parent_id' => 1
                ),
                'recursive' => -1
            ));
            $this->set('controllers', $controllers);

            $feedback = $this->Feedback->find('first', array(
                'fields' => array(
                    'Feedback.*',
                    'Aco.*',
                    'ErpUnitController.*',
                    'ErpUnit.*',
                    'ErpUnitDeveloper.*',
                    'ErpDeveloper.*',
                    'User.*',
                    'UserWorking.*',
                    'UserClosed.*'
                ),
                'joins' => array(
                    array(
                        'table' => 'acos',
                        'alias' => 'Aco',
                        'conditions' => array('Feedback.aco_id = Aco.id')
                    ),
                    array(
                        'table' => 'erp_unit_controllers',
                        'alias' => 'ErpUnitController',
                        'type' => 'left',
                        'conditions' => array('Aco.id = ErpUnitController.aco_id')
                    ),
                    array(
                        'table' => 'erp_units',
                        'alias' => 'ErpUnit',
                        'type' => 'left',
                        'conditions' => array('ErpUnitController.erp_unit_id = ErpUnit.id')
                    ),
                    array(
                        'table' => 'erp_unit_developers',
                        'alias' => 'ErpUnitDeveloper',
                        'type' => 'left',
                        'conditions' => array('ErpUnit.id = ErpUnitDeveloper.erp_unit_id')
                    ),
                    array(
                        'table' => 'erp_developers',
                        'alias' => 'ErpDeveloper',
                        'type' => 'left',
                        'conditions' => array('ErpUnitDeveloper.erp_developer_id = ErpDeveloper.id')
                    ),
                    array(
                        'table' => 'users',
                        'alias' => 'User',
                        'conditions' => array('Feedback.user_id = User.id')
                    )
                ),
                'conditions' => array(
                    'Feedback.id' => $id,
                    'ErpUnitController.deleted' => 0
                ),
                'contain' => array('UserWorking', 'UserClosed')
            ));

            $this->loadModel('ErpUnitDeveloper');
            $developers = array();
            $maintainers = array();
            if(isset($feedback['ErpUnit']['id']) && !empty($feedback['ErpUnit']['id'])) {
                $developers = $this->ErpUnitDeveloper->find('all', array(
                    'conditions' => array(
                        'ErpUnitDeveloper.type' => 'developer',
                        'ErpUnitDeveloper.erp_unit_id' => $feedback['ErpUnit']['id'],
                        'ErpUnitDeveloper.deleted' => 0
                    ),
                    'contain' => array('ErpDeveloper', 'ErpDeveloper.User'),
                    'recursive' => -1
                ));
                $maintainers = $this->ErpUnitDeveloper->find('all', array(
                    'conditions' => array(
                        'ErpUnitDeveloper.type' => 'maintainer',
                        'ErpUnitDeveloper.erp_unit_id' => $feedback['ErpUnit']['id'],
                        'ErpUnitDeveloper.deleted' => 0
                    ),
                    'contain' => array('ErpDeveloper', 'ErpDeveloper.User'),
                    'recursive' => -1
                ));
            }
            $feedback['developers'] = $developers;
            $feedback['maintainers'] = $maintainers;
            $this->set('feedback', $feedback);

            $controller = null;
            $action = null;

            if(isset($feedback['Aco']) && !empty($feedback['Aco'])) {
                $controller = $feedback['Aco']['parent_id'];
                $action = $feedback['Aco']['id'];
            }

            $this->set('controller', $controller);
            $this->set('action', $action);

            $this->loadModel('ErpDeveloper');
            $userIsDeveloper = false;
            $developer = $this->ErpDeveloper->find('first', array(
                'conditions' => array(
                    'ErpDeveloper.user_id' => $this->user['id'],
                    'ErpDeveloper.deleted' => 0
                ),
                'recursive' => -1
            ));
            if(!empty($developer)) $userIsDeveloper = true;
            $this->set('userIsDeveloper', $userIsDeveloper);

            $comments = $this->Feedback->query("select fc.*, CONCAT(u.first_name, ' ', u.last_name) as ime, u.avatar_link
                                                from feedback_comments fc
                                                join users u on fc.user_id = u.id
                                                where fc.feedback_id = ".$id."
                                                order by created desc");
            $this->set('comments', $comments);
            if ($this->request->is('post') || $this->request->is('put')) {
                $data = $this->request->data;
                $comment['FeedbackComment']['feedback_id'] = $id;
                $comment['FeedbackComment']['user_id'] = $this->user['id'];
                $comment['FeedbackComment']['comment'] = $data['Feedback']['comment'];
                $comment['FeedbackComment']['server'] = $_SERVER['SERVER_NAME'];
                $comment['FeedbackComment']['port'] = $_SERVER['SERVER_PORT'];
                $this->Feedback->FeedbackComment->set($comment);
                if(!$this->Feedback->FeedbackComment->validates()) throw new Exception("Niste napisali komentar");
                
                if (!$this->Feedback->FeedbackComment->save($comment)) {
                    throw new Exception("Greška. Komentar nije dodat. Molimo vas pokušajte ponovo.");
                }
                //send mail
                $user = $this->Feedback->User->find('first', array(
                    'recursive' => -1, 
                    'conditions' => array(
                        'User.id' => $this->user['id']
                        )
                    ));
                $comment['FeedbackComment']['name'] = $user['User']['first_name'].' '.$user['User']['last_name'];
                $comment['FeedbackComment']['feedback_id'] = $id;
                $comment['FeedbackComment']['feedback'] = '';
                $f_Data = $this->Feedback->find('first', array(
                    'recursive' => -1,
                    'fields' => 'Feedback.description',
                    'conditions' => array(
                        'Feedback.id' => $id
                        )
                    ));
                $comment['FeedbackComment']['feedback'] = $f_Data['Feedback']['description'];
                
                //Load EmailNotificationRecipient model
                $this->loadModel('EmailNotificationRecipient');
                //Get list of feedback receivers
                $to = $this->EmailNotificationRecipient->getEmails('Feedback');
                $comm_user_ids = $this->Feedback->FeedbackComment->find('all', array(
                        'recursive'=>-1,
                        'fields' => 'DISTINCT user_id',
                        'conditions' => array(
                            'FeedbackComment.feedback_id' => $id
                            )
                        ));
                foreach ($comm_user_ids as $user) {
                    $us = $this->Feedback->User->find('first', array(
                        'recursive' => -1,
                        'fields' => 'email', 
                        'conditions' => array(
                            'User.id' => $user['FeedbackComment']['user_id']
                            )
                        ));
                    array_push($to, $us['User']['email']);
                }

                // get feedback creator
                $feedback_creator = $this->Feedback->find('first', array(
                    'recursive' => -1,
                    'fields' => array(
                        'User.email',
                        'User.first_name',
                        'User.last_name'
                        ),
                    'joins' => array(
                        array(
                            'table' => 'users',
                            'alias' => 'User',
                            'conditions' => array(
                                'Feedback.user_id = User.id'
                                )
                            )
                        ), 
                    'conditions' => array(
                        'Feedback.id' => $id
                        )
                    ));
                // set creator name
                $comment['FeedbackComment']['feedback_creator'] = $feedback_creator['User']['first_name'].' '.$feedback_creator['User']['last_name'];

                array_push($to, $feedback_creator['User']['email']);
                
                $title = 'MikroERP - Novi komentar na feedback';
                $view = 'Feedbacks/comment';
                $transfer_protocol = 'smtp';
                $layout = 'template_d';
                $this->EmailResponse->sendEmailHtml($to, $title, $comment, $view, $transfer_protocol, $layout);

                $input_data = serialize($comment);
                $this->ErpLogManagement->erplog($this->user['id'], $this->params['controller'], $this->params['action'], $input_data, 'form', 'User added comment.');
                $this->Session->setFlash(__('Vaš komentar je dodat. Hvala Vam na povratnoj informaciji.'), 'flash_success');
                return $this->redirect(array('action' => 'view', $id));
            }
        }catch(Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash_error');
            return $this->redirect(array('action' => 'index' ));
        }
    }

      /**
   * 
   * change status
   * @param none
   * @throws exception if it's not ajax
    */
    public function change(){
        $result = array();
        if ($this->request->is('ajax')){
            $this->disableCache();
            $status = $_REQUEST['status'];
            $id = $_REQUEST['id'];
            $this->Feedback->id = $id;
            $data['Feedback']['status'] = $status;
            if ($status == 'closed') {
                $data['Feedback']['user_closed_id'] = $this->user['id'];
                $data['Feedback']['date_time_closed'] = date('Y-m-d H:i:s');
            }
            if($this->Feedback->save($data)){
                $result['status'] = '';
                switch ($status) {
                         case 'open':
                             $result['status'] = 'otvoren';
                             break;
                         case 'working_on':
                             $result['status'] = 'u toku';
                             break;
                         case 'closed':
                             $result['status'] = 'zatvoren';
                             break;
                         case 'postponed':
                             $result['status'] = 'odloženo';
                             break;     
                         default:
                             $result['status'] = '';
                             break;
                     }

                $result['success'] = __('Status je promenjen.');
                $result['feedback_id'] = $id;
                $input_data = serialize($result);
                $this->ErpLogManagement->erplog($this->user['id'], $this->params['controller'], $this->params['action'], $input_data, 'form', 'User changed status.');
            }else{
                $result['error'] = __('Status nije promenjen.');
            }
            $this->set('result', $result);
            $this->set('_serialize', 'result');

        }else{
            $this->Session->setFlash(__('Izabrali ste nepostojeću stranicu!'), 'flash_error');
            return $this->redirect('/');
        }

    }//~!

    public function addUserWorking($feedbackId, $userId) {
        $this->autoRender = false;
        $this->Feedback->id = $feedbackId;
        $this->Feedback->saveField('user_working_id', $userId);
        $user = $this->Feedback->User->find('first', array(
            'conditions' => array(
                'id' => $userId
            ),
            'recursive' => -1
        ));
        echo $user['User']['first_name'].' '.$user['User']['last_name'] ;
    }//~!

    public function addAco($feedbackId, $acoId) {
        $result = array();
        if ($this->request->is('ajax')){
            $this->autoRender = false;
            $this->Feedback->id = $feedbackId;
            $this->Feedback->saveField('aco_id', $acoId);
            $unit = array();
            $this->loadModel('ErpUnit');
            $unit = $this->ErpUnit->find('first', array(
                'fields' => array('ErpUnit.*'),
                'joins' => array(
                    array(
                        'table' => 'erp_unit_controllers',
                        'alias' => 'ErpUnitController',
                        'conditions' => array('ErpUnit.id = ErpUnitController.erp_unit_id')
                    )
                ),
                'contain' => array('ErpUnitDeveloper', 'ErpUnitDeveloper.ErpDeveloper', 'ErpUnitDeveloper.ErpDeveloper.User'),
                'conditions' => array(
                    'ErpUnitController.aco_id' => $acoId,
                    'ErpUnitController.deleted' => 0
                )
            ));
            $result['module'] = $unit['ErpUnit']['code'].' - '.$unit['ErpUnit']['name'];
            $developers = '';
            $maintainers = '';
            foreach ($unit['ErpUnitDeveloper'] as $erpUnitDeveloper) {
                if ($erpUnitDeveloper['type'] == 'developer') {
                    $developers .= $erpUnitDeveloper['ErpDeveloper']['User']['first_name'].' '.$erpUnitDeveloper['ErpDeveloper']['User']['last_name'].', ';
                }
                if ($erpUnitDeveloper['type'] == 'maintainer') {
                    $maintainers .= $erpUnitDeveloper['ErpDeveloper']['User']['first_name'].' '.$erpUnitDeveloper['ErpDeveloper']['User']['last_name'].', ';
                }
            }
            $developers = rtrim($developers, ', ');
            $maintainers = rtrim($maintainers, ', ');
            $result['developers'] = $developers;
            $result['maintainers'] = $maintainers;
            echo json_encode($result);
        }
    }//~!

}
?>
