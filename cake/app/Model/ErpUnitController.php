<?php
class ErpUnitController extends AppModel {

    // Variables

    var $name = 'ErpUnitController';

    // Associations
    
    public $belongsTo = array(
        'ErpUnit' => array(
            'className' => 'ErpUnit',
            'foreignKey' => 'erp_unit_id'
        ),
    	'Aco' => array(
    		'className' => 'Aco',
    		'foreignKey' => 'aco_id'
    	)
	);

    // Recursion

    public $recursive = -1;

    // Behaviors

    public $actsAs = array('Containable');

    // Validation

    public $validate = array(
		'erp_unit_id' => array(
			'erpUnitIdRule1' => array(
				'rule' => array('notEmpty'),
				'message' => 'Modul nije definisan',
				'required' => true,
				'allowEmpty' => false
			),
			'erpUnitIdRule2' => array(
				'rule' => array('exist', 'ErpUnit'),
				'message' => 'Modul ne postoji',
				'required' => true,
				'allowEmpty' => false
			)
		),
		'aco_id' => array(
			'acoIdRule1' => array(
				'rule' => array('notEmpty'),
				'message' => 'Kontroler nije definisan',
				'required' => true,
				'allowEmpty' => false
			),
			'acoIdRule2' => array(
				'rule' => array('exist', 'Aco'),
				'message' => 'Kontroler ne postoji',
				'required' => true,
				'allowEmpty' => false
			)
		)
    );

    /**
     * Get aco id from link
     *
     * @throws nothing
     * @param string $link
     * @return int or false
     */
    public function getAcoIdFromLink($link) {
		$fullLinkArray = explode('/', $link);
		$controllerName = 'Pages';
		if(isset($fullLinkArray[3]) && !empty($fullLinkArray[3])) {
			$controller = $fullLinkArray[3];
			$controllerArray = explode('?', $controller);
			if (!empty($controllerArray[0]))
				$controllerName = $controllerArray[0];
			$controllerName = str_replace(' ', '', ucwords(str_replace('_', ' ', $controllerName)));
		}
		$controllerAco = $this->Aco->find('first', array(
			'conditions' => array(
				'Aco.alias' => $controllerName
			),
			'recursive' => -1
		));
		$actionName = 'index';
		if($controllerName == 'Pages') $actionName = 'display';
		if(isset($fullLinkArray[4]) && !empty($fullLinkArray[4])) {
			$action = $fullLinkArray[4];
			$actionArray = explode('?', $action);
			$actionName = $actionArray[0];
		}
		$actionAco = $this->Aco->find('first', array(
			'conditions' => array(
				'Aco.parent_id' => $controllerAco['Aco']['id'],
				'Aco.alias' => $actionName
			),
			'recursive' => -1
		));
		$result = false;
		if(!empty($actionAco)) $result = $actionAco['Aco']['id'];
		return $result;
	}//~!
	
    /**
     * Get data from view
     *
     * @throws nothing
     * @param string $link
     * @return int or false
     */
	public function getOneForView($id) {
		$data = array();
		$data['erpUnitController'] = $this->find('first', array(
			'fields' => array('ErpUnitController.*', 'ErpUnit.*', 'Aco.*', 'ParentAco.*'),
			'joins' => array(
				array(
					'table' => 'acos',
					'alias' => 'Aco',
					'conditions' => array('ErpUnitController.aco_id = Aco.id')
				),
				array(
					'table' => 'acos',
					'alias' => 'ParentAco',
					'conditions' => array('Aco.parent_id = ParentAco.id')
				)
			),
			'conditions' => array(
				'ErpUnitController.id' => $id,
				'ErpUnitController.deleted' => 0
			),
			'contain' => array('ErpUnit')
		));
		$data['erpUnitControllers'] = $this->find('all', array(
			'fields' => array('ErpUnitController.*', 'ErpUnit.*', 'Aco.*', 'ParentAco.*'),
			'joins' => array(
				array(
					'table' => 'acos',
					'alias' => 'Aco',
					'conditions' => array('ErpUnitController.aco_id = Aco.id')
				),
				array(
					'table' => 'acos',
					'alias' => 'ParentAco',
					'conditions' => array('Aco.parent_id = ParentAco.id')
				)
			),
			'conditions' => array(
				'ErpUnitController.erp_unit_id' => $data['erpUnitController']['ErpUnitController']['erp_unit_id'],
				'Aco.lft >' => $data['erpUnitController']['Aco']['lft'],
				'Aco.rght <' => $data['erpUnitController']['Aco']['rght'],
				'ErpUnitController.deleted' => 0
			),
			'contain' => array('ErpUnit')
		));

		return $data;
	}//~!

    // Delete

    public function deleteErpUnitController($id) {
    	if(!empty($id)) {
    		$this->id = $id;
    		$this->saveField('deleted', '1');
	    	return true;
    	}
    }//~!

}
