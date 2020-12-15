<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	//public $components = array('Auth');


    public $uses = array('Menu', 'MenuElement');
	public $components = array(
		'Acl',
        'Session',
        'RequestHandler',
        'Auth' => array(
        	'authorize' => array('Actions' => array('actionPath' => 'controllers'),
            'loginRedirect' => array('controller' => 'users', 'action' => 'index'),
            'logoutRedirect' => array('controller' => 'pages', 'action' => 'display', 'home')
        )
    ));
    
    public $helpers = array('Html', 'Form', 'Session');
    
    public $linux_repository_path;
    public $win_repository_path;

    public $user = array();

    private function setRepositoryPath() {
        $this->linux_repository_path = '/home/repository';
        $this->win_repository_path = APP.'private';
    }//~!

    public function beforeFilter() {
        //$this->Auth->allow('index', 'view');

        //Set repository patth
        $this->setRepositoryPath();

        $this->Auth->authorize = 'Actions';
		$this->Auth->actionPath = 'controllers/';

        $this->user = $this->Session->read('Auth.User');
        $this->set('auth_user', $this->user);
        //Check if user language is set
        if(!empty($this->user['language'])){
            //Set user language
            Configure::write('Config.language', $this->user['language']);
        }else{
            //Check for intern language
            if ($this->Session->check('Config.language')) {
                //Set intern language
                Configure::write('Config.language', $this->Session->read('Config.language'));
            }
        }        

        $user = $this->Session->Read('Auth.User');

        // MENU //

        // Get user id //
        $userId = !empty($user['id']) ? $user['id'] : null;
        
        if(isset($userId)) {

            // Read cache //
            $itemsMenu = Cache::read('menuItems'.$user['id'], 'menus');

            // If cache is cleared //
            if(!$itemsMenu) {

                // Find Aro object for this user //
                $aro = $this->Acl->Aro->find('first', array(
                    'conditions' => array(
                        'model' => 'User',
                        'foreign_key' => $userId
                    ),
                    'recursive' => -1
                ));

                $this->loadModel('MenuItem');

                $acoMenus = $this->MenuItem->find('all', array(
                    'conditions' => array(
                        'MenuItem.deleted' => 0
                    ),
                    'fields' => array('aco_id'),
                    'recursive' => -1
                ));

                $menusAcoIds = array();

                foreach ($acoMenus as $acoMenu) {
                    $menusAcoIds[] = $acoMenu['MenuItem']['aco_id'];
                }

                // Find all Aco objects //
                $acos = $this->Acl->Aco->find('all', array(
                    'conditions' => array('id' => $menusAcoIds),
                    'recursive' => -1
                ));

                // Init Aco ids array //
                $acoIds = array();

                // For each Aco object //
                foreach($acos as $aco) {

                    // Check privilege on Aco object for this Aro object //
                    $acoId = $aco['Aco']['id'];
                    $query = "SELECT CASE (getPermission('User', $userId, $acoId)) WHEN 1 THEN 1 ELSE 0 END AS permission";
                    $result = $this->MenuItem->query($query);
                    $permission = $result[0][0]['permission'];
                    if($permission) {
                        // Add Aco id to array //
                        $acoIds[] = $aco['Aco']['id'];
                    }

                }

                // acoIds now contains ids to all Aco objects(controllers and actions) that is allowed to this user(Aro object) //

                // Find all menu items whose aco_id is in list of alowed Aco objects //
                $menuItems = $this->MenuItem->find('all', array(
                    'conditions' => array(
                        'OR' => array(
                            array('MenuItem.aco_id' => $acoIds),
                            array('MenuItem.aco_id' => null)
                        ),
                        'MenuItem.deleted' => 0
                    ),
                    'contain' => array('ErpKickstartIcon', 'Aco'),
                    'order' => array('MenuItem.name ASC'),
                    'recursive' => -1
                ));

                // For each menu item // 
                foreach ($menuItems as $key => $menuItem) {

                    // Find Aco object //
                    $aco = $this->Acl->Aco->find('first', array(
                        'conditions' => array(
                            'Aco.id' => $menuItem['MenuItem']['aco_id']
                        ),
                        'recursive' => -1
                    ));

                    // If Aco object exist //
                    if(!empty($aco)) {

                        // Find parent Aco object //
                        $acoParent = $this->Acl->Aco->find('first', array(
                            'conditions' => array(
                                'Aco.id' => $aco['Aco']['parent_id']
                            ),
                            'recursive' => -1
                        ));
                        
                        // Set controller and action fields //
                        $menuItems[$key]['MenuItem']['controller'] = $acoParent['Aco']['alias'];
                        $menuItems[$key]['MenuItem']['action'] = $aco['Aco']['alias'];

                        // Set options field //
                        $menuItems[$key]['MenuItem']['options'] = array('escape' => false);
                        
                    } else {

                        // Count children //
                        $countChildren = $this->MenuItem->find('count', array(
                            'conditions' => array(
                                'MenuItem.parent_id' => $menuItem['MenuItem']['id'],
                                'MenuItem.aco_id' => $acoIds,
                                'MenuItem.deleted' => 0
                            )
                        ));

                        // If there are children //
                        if($countChildren) {

                            // Set controller and action fields //
                            $menuItems[$key]['MenuItem']['controller'] = '';
                            $menuItems[$key]['MenuItem']['action'] = '';
                            $menuItems[$key]['MenuItem']['params'] = "\0";

                            // Disable links if there is no aco_id //
                            $menuItems[$key]['MenuItem']['options'] = array('escape' => false, 'onclick'=>'return false');

                        } else {

                            // If there is no Aco object and there are no children, remove menu item //
                            unset($menuItems[$key]);

                        }
                    
                    }

                }

                $itemsMenu = array();

                foreach ($menuItems as $menuItem) {
                    $itemsMenu[$menuItem['MenuItem']['parent_id']][] = $menuItem;
                }

                // Write cache //
                Cache::write('menuItems'.$user['id'], $itemsMenu, 'menus');
            }

            // Set data for view //
            $this->set('itemsMenu', $itemsMenu);
            $this->set('userName', $user['username']);
            $this->set('userId', $userId);

            $unreadNotificationCount = 0;
            $this->loadModel('Notification');
            $unreadNotificationCount = $this->Notification->countUnreadNotifications($userId);
            $this->set('unreadNotificationCount', $unreadNotificationCount);
            $lastNotifications = $this->Notification->getLastNotifications($userId);
            $this->set('lastNotifications', $lastNotifications);

        }

        // End of MENU //

        //Check if call is ajax
        $this->set('isAjax', $this->RequestHandler->isAjax());
    }//~!

    /**
        * Proxy for Controller::redirect() to handle AJAX redirects
        *
        * @param string $url
        * @param int $status
        * @param bool $exit
        * @return void
    */
    public function redirect($url, $status = null, $exit = true) {
    // this statement catches not authenticated or not authorized ajax requests
    // AuthComponent will call Controller::redirect(null, 403) in those cases.
    // with this we're making sure that we return valid JSON responses in all cases
    if($this->request->is('ajax') && $url == null && $status == 403) {
    $this->response = new CakeResponse(array('code' => 'code'));
    $this->response->body('<div id="alert_auth" class="notice error margin20"><i class="icon-remove-sign icon-large"></i>Nemate dozvolu da pristupite traženoj stranici.<a class="icon-remove" href="#close"></a></div>');
    $this->response->send();
    return $this->_stop();
    //if($this->Auth->loggedIn() !== true && $this->params['action'] !== 'login'){ exit("<script>window.location.href='".FULL_BASE_URL."/login';</script>");

    }
    return parent::redirect($url, $status, $exit);
    }//~!

}