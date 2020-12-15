<?php 
class cake_sessions extends AppModel{	
	public function beforeSave($options = array()){
		/* Useful in cases where you would want to store extra data - like an IP-address - in your session handle */
		//Init user id
		$this->data[$this->alias]['user_id'] = null;
		//Set logged in user id
		$user_id = AuthComponent::user('id');
		//Check if user id is set
		if(empty($this->data[$this->alias]['user_id']) && !empty($user_id)){
			$this->data[$this->alias]['user_id'] = $user_id;
		}
	}//~!

	public function getActiveUsers(){
		$minutes = 10; // Conditions for the interval of an active session
		$sessionData = $this->find('all',array(
			'conditions' => array(
				'expires >=' => time() - ($minutes * 60) // Making sure we only get recent user sessions
			),
			'order' => 'expires ASC'
		));


		$activeUsers = array();
		foreach($sessionData as $session){

			$data = $session['cake_sessions']['data'];

			// Clean the string from unwanted characters
			$data = str_replace('Config','',$data);
			$data = str_replace('Message','',$data);
			$data = str_replace('Auth','',$data);
				$data = substr($data, 1); // Removes the first pipe, don't need it


			// Explode the string so we get an array of data
			$data = explode('|',$data);
			//var_dump($data); //exit();

			// Unserialize all the data so we can use it
			if(!empty($data[1]) || !empty($data[2])){
				$a = unserialize($data[1]);
				$auth = !empty($a) ? unserialize($data[1]) : unserialize($data[2]);
			}


			// Check if we are dealing with a signed-in user
			if(!isset($auth['User']) || is_null($auth['User']['id'])) continue;
			/* Because a user session contains all the data of a user 
				* (except the password), I will only return the User id 
				* and the first and last name of the user */

			/* First check if a user id hasn't already been saved 
				* (can happen because of multiple sign-ins on different 
				* browsers / computers!) */

			if(!in_array($auth['User']['id'],$activeUsers)){
				$activeUsers[$auth['User']['id']] = array('first_name' => $auth['User']['first_name'], 'last_name' => $auth['User']['last_name'], 'expires' => $session['cake_sessions']['expires']); 

				/* Keep in mind, your User table needs to contain 
				 * a first- and lastname to return them. If not, 
				 * you could use the email address or username 
				 * instead of this data. */

			}
		}
		//var_dump($activeUsers);
		return $activeUsers;
	}

	public function updateUserIds(){
		//Get all session data
		$sessionData = $this->find('all',array('recursive' => -1));

		$activeUsers = array();
		foreach($sessionData as $session){

			$data = $session['cake_sessions']['data'];

			// Clean the string from unwanted characters
			$data = str_replace('Config','',$data);
			$data = str_replace('Message','',$data);
			$data = str_replace('Auth','',$data);
				$data = substr($data, 1); // Removes the first pipe, don't need it


			// Explode the string so we get an array of data
			$data = explode('|',$data);
			//var_dump($data); //exit();

			// Unserialize all the data so we can use it
			if(!empty($data[1]) || !empty($data[2])){
				$a = unserialize($data[1]);
				$auth = !empty($a) ? unserialize($data[1]) : unserialize($data[2]);
			}


			// Check if we are dealing with a signed-in user
			if(!isset($auth['User']) || is_null($auth['User']['id'])) continue;
			/* Because a user session contains all the data of a user 
				* (except the password), I will only return the User id 
				* and the first and last name of the user */

			/* First check if a user id hasn't already been saved 
				* (can happen because of multiple sign-ins on different 
				* browsers / computers!) */

			if(!in_array($auth['User']['id'], $activeUsers)){
				//Update user id
				$session['cake_sessions']['user_id'] = $auth['User']['id'];
				$this->save($session);
			}
		}
	}//~!
	
    /**
     * Force user logout
     *
     * @throws nothing
     * @param $user_id - User.id that forces logout
     * @return result with success flag and message
     */
    public function forceLogout($user_id){
        //Init result
        $result = array();        
        try {
			//Set user model
			$UserModel = ClassRegistry::init('User');
            //Get user
            $user = $UserModel->find('first', array(
				'conditions' => array('User.id' => $user_id),
				'fields' => array('User.id'),
                'recursive' => -1
            ));
            //Check for user
            if(empty($user)){
                throw new Exception(__("Korisnik nije validan!"));
			}
			//Delete all user session data
			$delete_result = $this->deleteAll(array('user_id' => $user_id));
			if(!$delete_result){
				throw new Exception("Sesije korisnika ne mogu biti obrisane!");
			}
            //Set return result
            $result['success'] = true;
            $result['message'] = __("Korisnik uspešno izlogovan!");
        } catch (Exception $e) {
            //Save error message
            $result['success'] = false;
            $result['message'] = $e->getMessage();
        }
        //Return result
        return $result;
    }//~!   			
}