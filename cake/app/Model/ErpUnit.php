<?php

class ErpUnit extends AppModel {

    // Variables

    var $name = 'ErpUnit';

    var $levels = array(
    	'module' => 'Modul',
    	'group' => 'Grupa',
    	'subgroup' => 'Podgrupa',
    	'type' => 'Vrsta',
    	'subtype' => 'Podvrsta'
    );

    var $sublevels = array(
    	'modules' => 'groups',
    	'groups' => 'subgroups',
    	'subgroups' => 'types',
    	'types' => 'subtypes',
    	'subtypes' => ''
    );

	var $rowTemplate = array(
		'id' => null,
		'module' => null,
		'group' => null,
		'subgroup' => null,
		'type' => null,
		'subtype' => null,
		'developer' => null,
		'maintainer' => null,
		'controller' => null,
		'note' => null,
	);

	var $romanDigits = array(
		array('arabic' => 1000, 'roman' => 'M' ),
		array('arabic' =>  900, 'roman' => 'CM'),
		array('arabic' =>  500, 'roman' => 'D' ),
		array('arabic' =>  400, 'roman' => 'CD'),
		array('arabic' =>  100, 'roman' => 'C' ),
		array('arabic' =>   90, 'roman' => 'XC'),
		array('arabic' =>   50, 'roman' => 'L' ),
		array('arabic' =>   40, 'roman' => 'XL'),
		array('arabic' =>   10, 'roman' => 'X' ),
		array('arabic' =>    9, 'roman' => 'IX'),
		array('arabic' =>    5, 'roman' => 'V' ),
		array('arabic' =>    4, 'roman' => 'IV'),
		array('arabic' =>    1, 'roman' => 'I' )
	);

    // Associations
    
    public $belongsTo = array(
        'Parent' => array(
            'className' => 'ErpUnit',
            'foreignKey' => 'parent_id'
        )
    );

    public $hasMany = array(
    	'ErpUnitDeveloper' => array(
    		'className' => 'ErpUnitDeveloper',
			'foreignKey' => 'erp_unit_id',
			'conditions' => array('ErpUnitDeveloper.deleted' => 0)
    	),
    	'ErpUnitController' => array(
    		'className' => 'ErpUnitController',
			'foreignKey' => 'erp_unit_id',
			'conditions' => array('ErpUnitController.deleted' => 0)
    	),
    );

    // Recursion

    public $recursive = -1;

    // Behaviors

    public $actsAs = array('Containable', 'Tree');

    // Validation    

    public $validate = array(
		'parent_id' => array(
			'parentIdRule1' => array(
				'rule' => array('exist', 'ErpUnit'),
				'message' => 'Nadređeni modul ne postoji',
				'required' => true,
				'allowEmpty' => false
			)
		),
		'code' => array(
			'codeRule1' => array(
				'rule' => array('notEmpty'),
				'message' => 'Kod nije definisan',
				'required' => true,
				'allowEmpty' => false
			),
			'codeRule2' => array(
				'rule' => array('uniqueCode'),
				'message' => 'Kod nije jedinstven',
				'required' => true,
				'allowEmpty' => false
			),
			'codeRule3' => array(
				'rule' => array('validateCode'),
				'message' => 'Kod nije ispravan',
				'required' => true,
				'allowEmpty' => false
			)
		),
		'name' => array(
			'nameRule1' => array(
				'rule' => array('notEmpty'),
				'message' => 'Ime nije definisano',
				'required' => true,
				'allowEmpty' => false
			),
			'nameRule2' => array(
				'rule' => array('uniqueName'),
				'message' => 'Ime nije jedinstveno',
				'required' => true,
				'allowEmpty' => false
			)
		),
		'level' => array(
			'levelRule1' => array(
				'rule' => array('notEmpty'),
				'message' => 'Nivo nije definisan',
				'required' => true,
				'allowEmpty' => false
			),
			'levelRule2' => array(
				'rule' => array('inList', array('module','group','subgroup','type','subtype')),
				'message' => 'Nivo nije ispravan',
				'required' => true,
				'allowEmpty' => false
			)
		)
	);
	
    /**
	 * Code unique validation
	 *
     * @throws nothing
     * @param array $check
     * @return boolean
     */
	public function uniqueCode($check) {
		$code = $check['code'];
		$unitId = null;
		if(isset($this->data['ErpUnit']['id']))
			$unitId = $this->data['ErpUnit']['id'];
		$unit = $this->find('first', array(
			'conditions' => array(
				'ErpUnit.id <>' => $unitId,
				'ErpUnit.code' => $code,
				'ErpUnit.deleted' => 0
			),
			'recursive' => -1
		));
		return empty($unit);
	}//~!

    /**
	 * Code format validation
	 *
     * @throws nothing
     * @param array $check
     * @return boolean
     */
    public function validateCode($check) {
    	// Check format //
    	return true;
    }//~!

    /**
	 * Name unique validation - only in the same level and with same parent
	 *
     * @throws nothing
     * @param array $check
     * @return boolean
     */
    public function uniqueName($check) {
		$id = null;
		if(isset($this->data['ErpUnit']['id']))
			$id = $this->data['ErpUnit']['id'];
    	$name = $check['name'];
    	$level = $this->data['ErpUnit']['level'];
    	$parentId = $this->data['ErpUnit']['parent_id'];
    	$erpUnits = $this->find('all', array(
    		'conditions' => array(
    			'ErpUnit.id <>' => $id,
    			'ErpUnit.parent_id' => $parentId,
    			'ErpUnit.name' => $name,
    			'ErpUnit.level' => $level,
    			'ErpUnit.deleted' => 0
    		)
    	));
    	if (!empty($erpUnits)) return false;
    	return true;
    }//~!

    // Callbacks

    /**
	 * Generate code and unset unnecessary validation
	 *
     * @throws nothing
     * @param array $options
     * @return boolean
     */
    public function beforeValidate($options = array()) {
    	if(isset($this->data['ErpUnit']['id']) && empty($this->data['ErpUnit']['id'])) {
	    	$this->data['ErpUnit']['code'] = $this->generateCode($this->data['ErpUnit']['parent_id']);
    	} else {
    		unset($this->validate['code']);
    	}
    	if($this->data['ErpUnit']['level'] == 'module')
    		unset($this->validate['parent_id']);
    	return true;
    }//~!

    // Helpers

    /**
	 * Generate code form parent code
	 *
     * @throws nothing
     * @param int $parentId
     * @return string
     */
    public function generateCode($parentId) {
    	if(empty($parentId)) {
			$count = $this->countModules();
			$code = $this->convertArabicToRomanNumeral($count + 1);
    	} else {
			$parentCode = $this->getParentCode($parentId);
			$count = $this->countChildren($parentId);
			$code = $parentCode.str_pad($count+1, 2, '0', STR_PAD_LEFT);
    	}
    	return $code;
    }//~!

    /**
	 * Convert arabic to roman numeral for code generation
	 *
     * @throws nothing
     * @param int $number
     * @return string
     */
    public function convertArabicToRomanNumeral($number) {
    	$romanNumber = '';
    	$count = count($this->romanDigits);
    	for($i = 0; $i < $count; $i++) {
	    	if($number - $this->romanDigits[$i]['arabic'] >= 0) {
	    		$romanNumber .= $this->romanDigits[$i]['roman'];
	    		$number = $number - $this->romanDigits[$i]['arabic'];
	    		$i = -1;
	    	}
    	}
    	return $romanNumber;
    }//~!

    // Counters

    /**
	 * Count all modules
	 *
     * @throws nothing
     * @param none
     * @return int
     */
    public function countModules() {
		$count = $this->find('count', array(
			'conditions' => array(
				'level' => 'module',
				'deleted' => 0
			)
		));
		return $count;
    }//~!

    /**
	 * Count immediate childrens
	 *
     * @throws nothing
     * @param none
     * @return int
     */
    public function countChildren($parentId) {
		$count = $this->find('count', array(
			'conditions' => array(
				'parent_id' => $parentId,
				'deleted' => 0
			)
		));
		return $count;
    }//~!

    // Getters

    /**
	 * Get data for index page
	 *
     * @throws nothing
     * @param string $level
     * @param int $parentId
     * @return array
     */
    public function getIndexData($level, $parentId) {
    	$data = array();
		if(empty($level)) $level = 'modules';
		$data['level'] = $level;
		$parent = $this->find('first', array(
			'conditions' => array(
				'ErpUnit.id' => $parentId
			)
		));
		if(empty($parent)) $data['title'] = 'Organizacija ERP-a';
		else $data['title'] = $parent['ErpUnit']['code'].' - '.$parent['ErpUnit']['name'];
		$data['settings'] = $this->getPaginatorSettings($level, $parentId);
		$data['sublevel'] = $this->getSublevel($level);
		$data['parentId'] = $parentId;
    	return $data;
    }//~!

    /**
	 * Get query settings for pagination
	 *
     * @throws nothing
     * @param string $level
     * @param int $parentId
     * @return array
     */
    public function getPaginatorSettings($level, $parentId = null) {
		$level = Inflector::singularize($level);
		$settings =  array(
			'conditions' => array(
				'ErpUnit.parent_id' => $parentId,
				'ErpUnit.level' => $level,
				'ErpUnit.deleted' => 0
			),
			'contain' => array('ErpUnitDeveloper', 'ErpUnitDeveloper.ErpDeveloper', 'ErpUnitDeveloper.ErpDeveloper.User'),
			'limit' => 10
		);
		return $settings;
    }//~!

    public function getSublevel($level) {
    	$sublevel = $this->sublevels[Inflector::pluralize($level)];
    	return $sublevel;
    }//~!

    /**
	 * Get parents for dropdown
	 *
     * @throws nothing
     * @param string $level
     * @return array
     */
    public function getParents($level = '') {
    	$parentLevel = '';
		switch ($level) {
			case 'module': $parentLevel = ''; break;
			case 'group': $parentLevel = 'module'; break;
			case 'subgroup': $parentLevel = 'group'; break;
			case 'type': $parentLevel = 'subgroup'; break;
			case 'subtype': $parentLevel = 'type'; break;
		}
		$parents = $this->getParentsList($parentLevel);
   		return $parents;
    }//~!

    /**
	 * Get parents module
	 *
     * @throws nothing
     * @param int $parentId
     * @return array
     */
    public function getParentUnit($parentId) {
		$parentUnit = $this->find('first', array(
			'conditions' => array(
				'ErpUnit.id' => $parentId
			),
			'contain' => array('ErpUnitDeveloper', 'ErpUnitDeveloper.ErpDeveloper', 'ErpUnitDeveloper.ErpDeveloper.User')
		));
		return $parentUnit;
    }//~!

    /**
	 * Get list of parents for hierarchy level
	 *
     * @throws nothing
     * @param string $parentLevel
     * @return array
     */
    public function getParentsList($parentLevel) {
		$this->virtualFields = array('full_name' => 'CONCAT(code," - ",name)');
		$parents = $this->find('list', array(
			'fields' => array('id', 'full_name'),
			'conditions' => array(
				'ErpUnit.level' => $parentLevel,
				'ErpUnit.deleted' => 0
			),
			'order' => 'ErpUnit.lft'
		));
		unset($this->virtualFields);
		return $parents;
    }//~!

    /**
	 * Get list of parents for hierarchy level
	 *
     * @throws nothing
     * @param string $parentLevel
     * @return array
     */
    public function getParentsListById($parentId) {
		$this->virtualFields = array('full_name' => 'CONCAT(code," - ",name)');
		$parents = $this->find('list', array(
			'fields' => array('id', 'full_name'),
			'conditions' => array(
				'ErpUnit.parent_id' => $parentId,
				'ErpUnit.deleted' => 0
			),
			'order' => 'ErpUnit.lft'
		));
		unset($this->virtualFields);
		return $parents;
    }//~!

	/**
	 * Get code from parent
	 *
     * @throws nothing
     * @param int $parentId
     * @return string
     */
    public function getParentCode($parentId) {
		$parent = $this->find('first', array(
			'conditions' => array(
				'id' => $parentId
			)
		));
		if(empty($parent)) throw new Exception('Parent ne postoji');
		$parentCode = $parent['ErpUnit']['code'];
		return $parentCode;
    }//~!

    /**
	 * Get developers for dropdowns
	 *
     * @throws nothing
     * @param none
     * @return array
     */
    public function getDevelopers() {
    	$erpDevelopers = $this->ErpUnitDeveloper->ErpDeveloper->find('all', array(
    		'conditions' => array(
    			'ErpDeveloper.deleted' => 0
    		),
    		'contain' => array('User')
    	));
    	$developers = array();
    	foreach ($erpDevelopers as $erpDeveloper) {
    		$developers[$erpDeveloper['ErpDeveloper']['id']] = $erpDeveloper['User']['first_name'].' '.$erpDeveloper['User']['last_name'];
    	}
		return $developers;
    }//~!

    /**
	 * Get controllers for dropdown
	 *
     * @throws nothing
     * @param none
     * @return array
     */
    public function getControllers() {
    	$controllers = $this->ErpUnitController->Aco->find('list', array(
    		'fields' => array('id', 'alias'),
    		'conditions' => array(
    			'Aco.parent_id' => 1
    		)
    	));
    	return $controllers;
	}//~!
	
    /**
	 * Get acos for dropdown
	 *
     * @throws nothing
     * @param none
     * @return array
     */
    public function getAcos($id) {
		$listAcos = array();
		if(!empty($id)) {
			$unitControllers = $this->ErpUnitController->find('all', array(
				'conditions' => array(
					'ErpUnitController.erp_unit_id' => $id,
					'ErpUnitController.deleted' => 0
				),
				'contain' => array('Aco')
			));
			foreach ($unitControllers as $unitController) {
				$aco = $this->ErpUnitController->Aco->find('first', array(
					'conditions' => array(
						'Aco.id' => $unitController['Aco']['id']
					),
					'recursive' => -1
				));
				$result = array();
				$listAcos[$aco['Aco']['id']] = $aco['Aco']['alias'];
				if (!empty($aco['Aco']['parent_id']) && $aco['Aco']['parent_id'] != 1) {
					$parentAco = $this->ErpUnitController->Aco->find('first', array(
						'conditions' => array(
							'Aco.id' => $aco['Aco']['parent_id']
						),
						'recursive' => -1
					));
					$listAcos[$aco['Aco']['id']] = $parentAco['Aco']['alias'].'/'.$aco['Aco']['alias'];
				}
			}
		}
    	return $listAcos;
	}//~!

    /**
	 * Get all modules
	 *
     * @throws nothing
     * @param none
     * @return array
     */
    public function getModules() {
		$modules = $this->find('all', array(
			'conditions' => array(
				'level' => 'module',
				'deleted' => 0
			)
		));
		return $modules;
    }//~!

    /**
	 * Get lists of developers and maintainers for modules
	 *
     * @throws nothing
     * @param array $erpUnits
     * @return array
     */
    public function getDevelopersMaintainers($erpUnits) {
		foreach ($erpUnits as $key => $erpUnit) {
			$developers = array();
			$maintainers = array();
	        if (!empty($erpUnit['ErpUnitDeveloper'])) {
	            foreach ($erpUnit['ErpUnitDeveloper'] as $developer) {
					if(!$developer['deleted']) {
						$firstName = $developer['ErpDeveloper']['User']['first_name'];
						$lastName = $developer['ErpDeveloper']['User']['last_name'];
						$fullName = $firstName.' '.$lastName;
						if ($developer['type'] == 'developer') 
							$developers[] = $fullName;
						if ($developer['type'] == 'maintainer') 
							$maintainers[] = $fullName;
					}
	            }
	        } else {
	            $parents = array_reverse($this->getPath($erpUnit['ErpUnit']['id']));
	            foreach ($parents as $parent) {
	            	$parentId = $parent['ErpUnit']['id'];
	            	$parentUnit = $this->getParentUnit($parentId);
	            	if(!empty($parentUnit['ErpUnitDeveloper'])) {
						foreach ($parentUnit['ErpUnitDeveloper'] as $developer) {
							if(!$developer['deleted']) {
								$firstName = $developer['ErpDeveloper']['User']['first_name'];
								$lastName = $developer['ErpDeveloper']['User']['last_name'];
								$fullName = $firstName.' '.$lastName;
								if ($developer['type'] == 'developer') 
									$developers[] = $fullName;
								if ($developer['type'] == 'maintainer') 
									$maintainers[] = $fullName;
							}
						}
						break;
	            	}
	            }
	        }
	        $erpUnits[$key]['developers'] = $developers;
	        $erpUnits[$key]['maintainers'] = $maintainers;
		}
		return $erpUnits;
    }//~!

    /**
	 * Get one for view
	 *
     * @throws nothing
     * @param int $id
     * @return array
     */
    public function getOne($id) {
    	$unit = $this->find('first', array(
    		'conditions' => array(
    			'ErpUnit.id' => $id
    		),
    		'contain' => array('Parent', 'ErpUnitController')
    	));
		$unit['controllers'] = array();
		$unit['controllers']['main'] = array();
		$unit['controllers']['children'] = array();
		foreach ($unit['ErpUnitController'] as $erpUnitController) {
			$aco = $this->ErpUnitController->Aco->find('first', array(
				'fields' => array('Aco.*', 'Parent.*'),
				'joins' => array(
					array(
						'table' => 'acos',
						'alias' => 'Parent',
						'conditions' => array('Aco.parent_id = Parent.id')
					)
				),
				'conditions' => array(
					'Aco.id' => $erpUnitController['aco_id']
				),
				'recursive' => -1
			));
			if (!empty($aco)) {
				if ($aco['Aco']['parent_id'] != 1) {
					if(!in_array($aco['Parent']['alias'], $unit['controllers']['main']))
						$unit['controllers']['main'][$erpUnitController['id']] = $aco['Parent']['alias'].'/'.$aco['Aco']['alias'];
				}
				else
					$unit['controllers']['main'][$erpUnitController['id']] = $aco['Aco']['alias'];
			}
		}
		$childrenControllers = $this->getChildrensControllers($id);
		foreach ($childrenControllers as $childrenController) {
			if (!empty($childrenController['ErpUnitController'])) {
				foreach ($childrenController['ErpUnitController'] as $unCtrl) {
					$aco = $this->ErpUnitController->Aco->find('first', array(
						'fields' => array('Aco.*', 'Parent.*'),
						'joins' => array(
							array(
								'table' => 'acos',
								'alias' => 'Parent',
								'conditions' => array('Aco.parent_id = Parent.id')
							)
						),
						'conditions' => array(
							'Aco.id' => $unCtrl['aco_id']
						),
						'recursive' => -1
					));
					if (!empty($aco)) {
						if($aco['Aco']['parent_id'] != 1) {
							if(!in_array($aco['Parent']['alias'], $unit['controllers']['children']))
								$unit['controllers']['children'][$unCtrl['id']] = $aco['Parent']['alias'].'/'.$aco['Aco']['alias'];
						}
						else
							$unit['controllers']['children'][$unCtrl['id']] = $aco['Aco']['alias'];
					}
				}
			}
		}
		foreach ($unit['controllers'] as $key => $aco) {
			//
		}
		$unit['children'] = $this->children($id, true, null, null, null, 1, 1);
    	foreach ($unit['children'] as $key => $child) {
    		if($child['ErpUnit']['deleted']) {
    			unset($unit['children'][$key]);
    		}
		}
		$unit['feedbacks'] = $this->getFeedbacksNumber($id);
    	$units = array($unit);
    	$units = $this->getDevelopersMaintainers($units);
		$unit = $units[0];
    	return $unit;
    }//~!

    /**
	 * Get controller from all children
	 *
     * @throws nothing
     * @param int $unitId
     * @return array
     */
    public function getChildrensControllers($unitId) {
    	$children = $this->children($unitId, false, null, null, null, 1, 1);
		return $children;
    }//~!

    /**
	 * Get data for preview
	 *
     * @throws nothing
     * @param array $filters
     * @return array
     */
    public function getData($filters = array()) {
		$data = array();
		$maintainerId = null;
		$developerId = null;
		$conditions = array(
			'ErpUnit.deleted' => 0
		);
		if(empty($filters)) {
			$conditions['ErpUnit.level'] = 'module';
		} else {
			$unitId = null;
			$parentIds = array();
			if(isset($filters['module_id'])) {
				$conditions['OR'][]['ErpUnit.level'] = 'module';
				if(!empty($filters['module_id'])) {
					$unitId = $filters['module_id'];
					$parentIds[] = $filters['module_id'];
				}
			}
			if(isset($filters['group_id'])) {
				$conditions['OR'][]['ErpUnit.level'] = 'group';
				if(!empty($filters['group_id'])) {
					$parentIds[] = $filters['group_id'];
				}
			}
			if(isset($filters['subgroup_id'])) {
				$conditions['OR'][]['ErpUnit.level'] = 'subgroup';
				if(!empty($filters['subgroup_id'])) {
					$parentIds[] = $filters['subgroup_id'];
				}
			}
			if(isset($filters['type_id'])) {
				$conditions['OR'][]['ErpUnit.level'] = 'type';
				if(!empty($filters['type_id'])) {
					$parentIds[] = $filters['type_id'];
				}
			}
			if(isset($filters['subtype_id'])) {
				$conditions['OR'][]['ErpUnit.level'] = 'subtype';
				if(!empty($filters['subtype_id'])) {
					$parentIds[] = $filters['subtype_id'];
				}
			}
			if(!empty($parentIds)) {
				$conditions['ErpUnit.parent_id'] = $parentIds;
			}
			if(isset($filters['developer_id']) && !empty($filters['developer_id'])) {
				$developerId = $filters['developer_id'];
			}
			if(isset($filters['maintainer_id']) && !empty($filters['maintainer_id'])) {
				$maintainerId = $filters['maintainer_id'];
			}
		}
		$units = $this->find('all', array(
			'conditions' => $conditions,
			'contain' => array('ErpUnitDeveloper'),
			'order' => 'ErpUnit.lft'
		));
		if(!empty($unitId)) {
			$unitMain = $this->find('first', array(
				'conditions' => array(
					'ErpUnit.id' => $unitId
				),
				'contain' => array('ErpUnitDeveloper', 'ErpUnitController'),
			));
			array_unshift($units, $unitMain);
		}
		$data = $this->getAllData($units, $developerId, $maintainerId);
		return $data;
	}//~!
	
    /**
	 * Get all data
	 *
     * @throws nothing
     * @param int $id
     * @return int
     */
	public function getAllData($units, $developerId = null, $maintainerId = null) {
		$data = array();
		foreach ($units as $unit) {
			$row = $this->rowTemplate;
			$row['id'] = $unit['ErpUnit']['id'];
			$row[$unit['ErpUnit']['level']]['code'] = $unit['ErpUnit']['code'];
			$row[$unit['ErpUnit']['level']]['name'] = $unit['ErpUnit']['name'];
			$row[$unit['ErpUnit']['level']]['feedbacks'] = $this->getFeedbacksNumber($unit['ErpUnit']['id']);
			if(!empty($unit['ErpUnitDeveloper'])) {
				foreach ($unit['ErpUnitDeveloper'] as $erpUnitDeveloper) {
					if(!empty($developerId) || !empty($maintainerId))
					if($erpUnitDeveloper['erp_developer_id'] != $developerId && $erpUnitDeveloper['erp_developer_id'] != $maintainerId) continue 2;
					if(!$erpUnitDeveloper['deleted']) {
						if($erpUnitDeveloper['type'] == 'developer') {
							$erpDeveloper = $this->ErpUnitDeveloper->ErpDeveloper->find('first', array(
								'conditions' => array(
									'ErpDeveloper.id' => $erpUnitDeveloper['erp_developer_id']
								),
								'contain' => array('User')
							));
							$row['developer'] .= $erpDeveloper['User']['first_name'].' '.$erpDeveloper['User']['last_name'].', ';
						}
						if($erpUnitDeveloper['type'] == 'maintainer') {
							$erpDeveloper = $this->ErpUnitDeveloper->ErpDeveloper->find('first', array(
								'conditions' => array(
									'ErpDeveloper.id' => $erpUnitDeveloper['erp_developer_id']
								),
								'contain' => array('User')
							));
							$row['maintainer'] .= $erpDeveloper['User']['first_name'].' '.$erpDeveloper['User']['last_name'].', ';
						}
					}
				}
				$row['developer'] = rtrim($row['developer'], ', ');
				$row['maintainer'] = rtrim($row['maintainer'], ', ');
			} else {
				$parents = array_reverse($this->getPath($unit['ErpUnit']['id']));
				foreach ($parents as $parent) {
					$erpUnitDevelopers = $this->ErpUnitDeveloper->find('all', array(
						'fields' => array('ErpUnitDeveloper.*', 'User.*'),
						'joins' => array(
							array(
								'table' => 'erp_developers',
								'alias' => 'ErpDeveloper',
								'conditions' => 'ErpUnitDeveloper.erp_developer_id = ErpDeveloper.id'
							),
							array(
								'table' => 'users',
								'alias' => 'User',
								'conditions' => 'ErpDeveloper.user_id = User.id'
							)
						),
						'conditions' => array(
							'ErpUnitDeveloper.erp_unit_id' => $parent['ErpUnit']['id'],
							'ErpUnitDeveloper.deleted' => 0
 						)
					));
					if(!empty($erpUnitDevelopers)) {
						foreach ($erpUnitDevelopers as $erpUnitDeveloper) {
							if($erpUnitDeveloper['ErpUnitDeveloper']['type'] == 'developer') {
								$row['developer'] .= $erpUnitDeveloper['User']['first_name'].' '.$erpUnitDeveloper['User']['last_name'].', ';
							}
							if($erpUnitDeveloper['ErpUnitDeveloper']['type'] == 'maintainer') {
								$row['maintainer'] .= $erpUnitDeveloper['User']['first_name'].' '.$erpUnitDeveloper['User']['last_name'].', ';
							}
						}
					}
				}
				$row['developer'] = rtrim($row['developer'], ', ');
				$row['maintainer'] = rtrim($row['maintainer'], ', ');
			}
			if (!empty($unit['ErpUnitController'])) {
				foreach ($unit['ErpUnitController'] as $erpUnitController) {

					$aco = $this->ErpUnitController->Aco->find('first', array(
						'fields' => array('Aco.*', 'Parent.*'),
						'joins' => array(
							array(
								'table' => 'acos',
								'alias' => 'Parent',
								'conditions' => array('Aco.parent_id = Parent.id')
							)
						),
						'conditions' => array(
							'Aco.id' => $erpUnitController['aco_id']
						),
						'recursive' => -1
					));
					if (!empty($aco)) {
						if ($aco['Aco']['parent_id'] != 1) {
							if(strpos($row['controller'], $aco['Parent']['alias']) === false)
								$row['controller'] .= $aco['Parent']['alias'].'/'.$aco['Aco']['alias'].', ';
						}
						else
							$row['controller'] .= $aco['Aco']['alias'].', ';
					}
				}
				$row['controller'] = rtrim($row['controller'], ', ');
			}
			$row['parent_id'] = $unit['ErpUnit']['parent_id'];
			$row['level'] = $unit['ErpUnit']['level'];
			$row['note'] = $unit['ErpUnit']['note'];
			$data[] = $row;
		}
		return $data;
	}//~!

    /**
	 * Get number of feedbacks for given unit id
	 *
     * @throws nothing
     * @param int $id
     * @return int
     */
	public function getFeedbacksNumber($id) {
		$acoIds = array();
		$erpUnitControllers = $this->ErpUnitController->find('all', array(
			'conditions' => array(
				'ErpUnitController.erp_unit_id' => $id,
				'ErpUnitController.deleted' => 0
			)
		));
		foreach($erpUnitControllers as $erpUnitController) {
			$acoIds[] = $erpUnitController['ErpUnitController']['aco_id'];
		}
		$unitControllers = $this->getChildrensControllers($id);
		foreach ($unitControllers as $unitController) {
			if(!empty($unitController['ErpUnitController'])) {
				foreach ($unitController['ErpUnitController'] as $unCtrl) {
					$acoIds[] = $unCtrl['aco_id'];
				}
			}
		}
		$this->Feedback = ClassRegistry::init('Feedback');
		$feedbacks = 0;
		if(!empty($acoIds)) {
			$feedbacks = $this->Feedback->find('count', array(
				'conditions' => array(
					'Feedback.aco_id' => $acoIds,
					'Feedback.status' => 'open'
				),
				'recursive' => -1
			));
		}
		return $feedbacks;
	}//~!

    // Save

    public function saveErpUnit($data, $id = null) {
		$this->id = $id;
    	if(!empty($data['ErpUnit']['developer_id']) && !empty($data['ErpUnit']['maintainer_id'])) {
			if(!$this->save($data))
				return false;
			$id = $this->id;
		}
		if(!empty($data['ErpUnit']['developer_id'])) {
			// Get old developers //
			$oldUnitDevelopers = array();
			$unitDevelopers = $this->ErpUnitDeveloper->find('all', array(
				'conditions' => array(
					'ErpUnitDeveloper.erp_unit_id' => $id,
					'ErpUnitDeveloper.type' => 'developer',
					'ErpUnitDeveloper.deleted' => 0
				)
			));
			foreach ($unitDevelopers as $unitDeveloper) {
				$oldUnitDevelopers[] = $unitDeveloper['ErpUnitDeveloper']['erp_developer_id'];
			}

			// Get new developers //
			$newUnitDevelopers = $data['ErpUnit']['developer_id'];

			// Find added developers //
			$addedUnitDevelopers = array_diff($newUnitDevelopers, $oldUnitDevelopers);

			foreach ($addedUnitDevelopers as $addedUnitDeveloper) {
				$this->ErpUnitDeveloper->create();
				$unitDeveloper = array();
				$unitDeveloper['erp_unit_id'] = $id;
				$unitDeveloper['erp_developer_id'] = $addedUnitDeveloper;
				$unitDeveloper['type'] = 'developer';
				$this->ErpUnitDeveloper->save($unitDeveloper);
			}

			// Find removed developers //
			$deletedUnitDevelopers = array_diff($oldUnitDevelopers, $newUnitDevelopers);

			foreach ($deletedUnitDevelopers as $deletedUnitDeveloper) {
				$erpUnitDeveloper = $this->ErpUnitDeveloper->find('first', array(
					'conditions' => array(
						'ErpUnitDeveloper.erp_developer_id' => $deletedUnitDeveloper,
						'ErpUnitDeveloper.erp_unit_id' => $id,
						'ErpUnitDeveloper.type' => 'developer',
						'ErpUnitDeveloper.deleted' => 0
					)
				));
				$this->ErpUnitDeveloper->id = $erpUnitDeveloper['ErpUnitDeveloper']['id'];
				$this->ErpUnitDeveloper->saveField('deleted', 1);
			}
    	} else {
			if($data['ErpUnit']['level'] == 'module') {
				if(!$this->save($data))
					return false;
				return true;
			}
    		$this->validationErrors[] = array('Developeri nisu definisani');
    		return false;
    	}
    	if(!empty($data['ErpUnit']['maintainer_id'])) {
			// Get old maintainers //
			$oldUnitMaintainers = array();
			$unitMaintainers = $this->ErpUnitDeveloper->find('all', array(
				'conditions' => array(
					'ErpUnitDeveloper.erp_unit_id' => $id,
					'ErpUnitDeveloper.type' => 'maintainer',
					'ErpUnitDeveloper.deleted' => 0
				)
			));
			foreach ($unitMaintainers as $unitMaintainer) {
				$oldUnitMaintainers[] = $unitMaintainer['ErpUnitDeveloper']['erp_developer_id'];
			}

			// Get new maintainers //
			$newUnitMaintainers = $data['ErpUnit']['maintainer_id'];

			// Find added maintainers //
			$addedUnitMaintainers = array_diff($newUnitMaintainers, $oldUnitMaintainers);

			foreach ($addedUnitMaintainers as $addedUnitMaintainer) {
				$this->ErpUnitDeveloper->create();
				$unitMaintainer = array();
				$unitMaintainer['erp_unit_id'] = $id;
				$unitMaintainer['erp_developer_id'] = $addedUnitMaintainer;
				$unitMaintainer['type'] = 'maintainer';
				$this->ErpUnitDeveloper->save($unitMaintainer);
			}

			// Find removed developers //
			$deletedUnitMaintainers = array_diff($oldUnitMaintainers, $newUnitMaintainers);

			foreach ($deletedUnitMaintainers as $deletedUnitMaintainer) {
				$erpUnitDeveloper = $this->ErpUnitDeveloper->find('first', array(
					'conditions' => array(
						'ErpUnitDeveloper.erp_developer_id' => $deletedUnitMaintainer,
						'ErpUnitDeveloper.erp_unit_id' => $id,
						'ErpUnitDeveloper.type' => 'maintainer',
						'ErpUnitDeveloper.deleted' => 0
					)
				));
				$this->ErpUnitDeveloper->id = $erpUnitDeveloper['ErpUnitDeveloper']['id'];
				$this->ErpUnitDeveloper->saveField('deleted', 1);
			}
    	} else {
			if($data['ErpUnit']['level'] == 'module') {
				if(!$this->save($data))
					return false;
				return true;
			}
    		$this->validationErrors[] = array('Maintaineri nisu definisani');
    		return false;
		}

		// Get old ACOs ids //
		$oldAcosIds = array();
		$oldAcos = $this->ErpUnitController->find('all', array(
			'conditions' => array(
				'ErpUnitController.erp_unit_id' => $id,
				'ErpUnitController.deleted' => 0
			),
			'recursive' => -1
		));
		foreach ($oldAcos as $oldAco) {
			$oldAcosIds[] = $oldAco['ErpUnitController']['aco_id'];
		}

		// Get new ACOs ids //
		$newAcosIds = array();
		$newAcos = $data['ErpUnit']['aco_id'];
		if(empty($newAcos)) $newAcos = array();
		foreach ($newAcos as $newAcoId) {
			$childrenAcos = $this->ErpUnitController->Aco->find('all', array(
				'conditions' => array(
					'Aco.parent_id' => $newAcoId
				),
				'recursive' => -1
			));
			$newAcosIds[] = $newAcoId;
			foreach ($childrenAcos as $childrenAco) {
				$unitChild = $this->ErpUnitController->find('first', array(
					'conditions' => array(
						'ErpUnitController.erp_unit_id <>' => $id,
						'ErpUnitController.aco_id' => $childrenAco['Aco']['id'],
						'ErpUnitController.deleted' => 0
					)
				));
				if(!empty($unitChild)) continue;
				$newAcosIds[] = $childrenAco['Aco']['id'];
			}
		}

		// Exist in new but doesnt exist in old //
		$addedAcosIds = array_diff($newAcosIds, $oldAcosIds);

		// Add them //
		foreach ($addedAcosIds as $addedAcoId) {
			$this->ErpUnitController->create();
			$unitController = array();
			$unitController['erp_unit_id'] = $id;
			$unitController['aco_id'] = $addedAcoId;
			$this->ErpUnitController->save($unitController);
		}
		
		// Exist in old but doesnt exist in new //
		$deletedAcosIds = array_diff($oldAcosIds, $newAcosIds);

		// Delete them //
		foreach ($deletedAcosIds as $deletedAcoId) {
			$unit = $this->ErpUnitController->find('first', array(
				'conditions' => array(
					'ErpUnitController.aco_id' => $deletedAcoId,
					'ErpUnitController.deleted' => 0
				)
			));
			if(!empty($unit)) {
				$this->ErpUnitController->id = $unit['ErpUnitController']['id'];
				$this->ErpUnitController->saveField('deleted', '1');
			}
		}
    	return $data;
    }//~!

    /**
     * Save same developers for all children of given module unit id
     *
     * @throws nothing
     * @param int $id
     * @return boolean
     */
    public function saveAssoc($id) {
    	if (empty($id)) return false;
    	$error = 0;
		$unitDevelopers = $this->ErpUnitDeveloper->find('all', array(
			'conditions' => array(
				'ErpUnitDeveloper.erp_unit_id' => $id,
				'ErpUnitDeveloper.deleted' => 0
			)
		));
		$children = $this->children($id, false, null, null, null, 1, -1);
		$dataSource = $this->ErpUnitDeveloper->getDataSource();
		$dataSource->begin();
		foreach ($children as $child) {
			foreach ($unitDevelopers as $unitDeveloper) {
				$this->ErpUnitDeveloper->create();
				$erpUnitDeveloper = array();
				$erpUnitDeveloper['erp_unit_id'] = $child['ErpUnit']['id'];
				$erpUnitDeveloper['type'] = $unitDeveloper['ErpUnitDeveloper']['type'];
				$erpUnitDeveloper['erp_developer_id'] = $unitDeveloper['ErpUnitDeveloper']['erp_developer_id'];
    			$oldUnitDeveloper = $this->ErpUnitDeveloper->find('first', array(
    				'conditions' => array(
    					'ErpUnitDeveloper.erp_unit_id' => $child['ErpUnit']['id'],
    					'ErpUnitDeveloper.erp_developer_id' => $unitDeveloper['ErpUnitDeveloper']['erp_developer_id'],
    					'ErpUnitDeveloper.type' => $unitDeveloper['ErpUnitDeveloper']['type'],
    					'ErpUnitDeveloper.deleted' => 0
    				)
    			));
    			if(!empty($oldUnitDeveloper)) continue;
				if(!$this->ErpUnitDeveloper->save($erpUnitDeveloper)) $error++;
			}
		}
		if (!$error) {
		    $dataSource->commit();
		} else {
		    $dataSource->rollback();
		}
		return !$error;
    }//~!

    // Delete

    public function deleteErpUnit($id) {
    	if(!empty($id)) {
			$subUnits = $this->find('all', array(
				'conditions' => array(
					'ErpUnit.parent_id' => $id,
					'ErpUnit.deleted' => 0
				)
			));
			if(!empty($subUnits)) return false;
			$this->id = $id;
			$this->saveField('deleted', '1');
			return true;
    	}
	}//~!
	
    /**
     * Import data into database from Excel file
     *
     * @throws nothing
     * @param array $data
     * @return boolean
     */
	public function importFromExcel($data) {
		$dataRows = array();
		$levels = array_keys($this->levels);
		$oldLevelId = 0;
		$newLevelId = 0;
		$parentId = null;
		$parentStack = array();
		$this->id = null;
		foreach ($data as $key => $unit) {
			$newRow = array();
			if(!empty($unit[0])) {
				$newRow['ErpUnit']['code'] = $unit[0];
				$newRow['ErpUnit']['name'] = $unit[1];
				$newRow['ErpUnit']['level'] = 'module';
				$newLevelId = 0;
			}
			if(!empty($unit[4])) {
				$newRow['ErpUnit']['code'] = $unit[4];
				$newRow['ErpUnit']['name'] = $unit[5];
				$newRow['ErpUnit']['level'] = 'group';
				$newLevelId = 1;
			}
			if(!empty($unit[8])) {
				$newRow['ErpUnit']['code'] = $unit[8];
				$newRow['ErpUnit']['name'] = $unit[9];
				$newRow['ErpUnit']['level'] = 'subgroup';
				$newLevelId = 2;
			}
			if(!empty($unit[12])) {
				$newRow['ErpUnit']['code'] = $unit[12];
				$newRow['ErpUnit']['name'] = $unit[13];
				$newRow['ErpUnit']['level'] = 'type';
				$newLevelId = 3;
			}
			if(!empty($unit[16])) {
				$newRow['ErpUnit']['code'] = $unit[16];
				$newRow['ErpUnit']['name'] = $unit[17];
				$newRow['ErpUnit']['level'] = 'subtype';
				$newLevelId = 4;
			}
			$unitDevelopers = array();
			if(!empty($unit[20])) {
				$developers = explode(', ', $unit['20']);
				foreach ($developers as $developer) {
					$user = $this->ErpUnitDeveloper->ErpDeveloper->User->find('first', array(
						'conditions' => array(
							'CONCAT(User.first_name," ",User.last_name)' => $developer
						),
						'recursive' => -1
					));
					if(!empty($user)) {
						$erpDeveloper = $this->ErpUnitDeveloper->ErpDeveloper->find('first', array(
							'conditions' => array(
								'ErpDeveloper.user_id' => $user['User']['id'],
								'ErpDeveloper.deleted' => 0
							)
						));
						if(empty($erpDeveloper)) {
							$newDeveloper = array();
							$newDeveloper['user_id'] = $user['User']['id'];
							$this->ErpUnitDeveloper->ErpDeveloper->create();
							$this->ErpUnitDeveloper->ErpDeveloper->save($newDeveloper);
							$unitDevelopers[] = $this->ErpUnitDeveloper->ErpDeveloper->id;
						} else {
							$unitDevelopers[] = $erpDeveloper['ErpDeveloper']['id'];
						}
					}
				}
			}
			$unitMaintainers = array();
			if(!empty($unit[21])) {
				$maintainers = explode(', ', $unit['21']);
				foreach ($maintainers as $maintainer) {
					$user = $this->ErpUnitDeveloper->ErpDeveloper->User->find('first', array(
						'conditions' => array(
							'CONCAT(User.first_name," ",User.last_name)' => $maintainer
						),
						'recursive' => -1
					));
					if(!empty($user)) {
						$erpDeveloper = $this->ErpUnitDeveloper->ErpDeveloper->find('first', array(
							'conditions' => array(
								'ErpDeveloper.user_id' => $user['User']['id'],
								'ErpDeveloper.deleted' => 0
							)
						));
						if(empty($erpDeveloper)) {
							$newDeveloper = array();
							$newDeveloper['user_id'] = $user['User']['id'];
							$this->ErpUnitDeveloper->ErpDeveloper->create();
							$this->ErpUnitDeveloper->ErpDeveloper->save($newDeveloper);
							$unitMaintainers[] = $this->ErpUnitDeveloper->ErpDeveloper->id;
						} else {
							$unitMaintainers[] = $erpDeveloper['ErpDeveloper']['id'];
						}
					}
				}
			}
			$unitControllers = array();
			if(!empty($unit[22])) {
				$controllers = explode(', ', $unit['22']);
				foreach ($controllers as $controller) {
					$controllerAction = explode('/', $controller);
					$controllerAco = $this->ErpUnitController->Aco->find('first', array(
						'conditions' => array(
							'Aco.alias' => $controllerAction[0]
						),
						'recursive' => -1
					));
					if(!empty($controllerAco)) {
						if(isset($controllerAction[1]) && !empty($controllerAction[1])) {
								$actionAco = $this->ErpUnitController->Aco->find('first', array(
								'conditions' => array(
									'Aco.parent_id' => $controllerAco['Aco']['id'],
									'Aco.alias' => $controllerAction[1]
								),
								'recursive' => -1
							));
							$unitControllers[] = $actionAco['Aco']['id'];
						} else {
							$unitControllers[] = $controllerAco['Aco']['id'];
							$acoChildren = $this->ErpUnitController->Aco->find('all', array(
								'conditions' => array(
									'Aco.parent_id' => $controllerAco['Aco']['id']
								),
								'recursive' => -1
							));
							foreach ($acoChildren as $acoChild) {
								$unitControllers[] = $acoChild['Aco']['id'];
							}
						}
					}
				}
			}
			if(!empty($unit[23])) {
				$newRow['ErpUnit']['note'] = $unit[23];
			}
			$direction = 'equal';
			$move = 0;
			if($newLevelId > $oldLevelId) {
				$direction = 'down';
				$move = 1;
			}
			if($newLevelId < $oldLevelId) {
				$direction = 'up';
				$move = $oldLevelId - $newLevelId;
			}
			switch ($direction) {
				case 'down':
					array_push($parentStack, $parentId);
					$parentId = $this->id;
					break;
				case 'up':
					for($i = 0; $i < $move; $i++)
						$parentId = array_pop($parentStack);
			}
			if($newRow['ErpUnit']['level'] == 'module') $parentId = null;
			$newRow['ErpUnit']['parent_id'] = $parentId;
			$this->create();
			$this->save($newRow);
			foreach ($unitDevelopers as $developerId) {
				$newUnitDeveloper = array();
				$newUnitDeveloper['erp_unit_id'] = $this->id;
				$newUnitDeveloper['erp_developer_id'] = $developerId;
				$newUnitDeveloper['type'] = 'developer';
				$this->ErpUnitDeveloper->create();
				$this->ErpUnitDeveloper->save($newUnitDeveloper);
			}
			foreach ($unitMaintainers as $maintainerId) {
				$newUnitDeveloper = array();
				$newUnitDeveloper['erp_unit_id'] = $this->id;
				$newUnitDeveloper['erp_developer_id'] = $maintainerId;
				$newUnitDeveloper['type'] = 'maintainer';
				$this->ErpUnitDeveloper->create();
				$this->ErpUnitDeveloper->save($newUnitDeveloper);
			}
			foreach ($unitControllers as $unitController) {
				$newUnitController = array();
				$newUnitController['aco_id'] = $unitController;
				$newUnitController['erp_unit_id'] = $this->id;
				$this->ErpUnitController->create();
				$this->ErpUnitController->save($newUnitController);
			}
			$oldLevelId = array_search($newRow['ErpUnit']['level'], $levels);
		}
		return true;
	}//~!

    /**
     * Export all data to Excel file
     *
     * @throws nothing
     * @param none
     * @return boolean
     */
	public function exportToExcel() {
		$units = $this->find('all', array(
			'conditions' => array(
				'ErpUnit.deleted' => 0
			),
			'contain' => array('ErpUnitDeveloper', 'ErpUnitController'),
			'order' => 'ErpUnit.lft'
		));
		$data = $this->getAllData($units);

		$header = array(
			'Kod modula' => 'string',
			'Modul' => 'string',
			'Kod grupe' => 'string',
			'Grupa' => 'string',
			'Kod podgrupe' => 'string',
			'Podgrupa' => 'string',
			'Kod vrste' => 'string',
			'Vrsta' => 'string',
			'Kod podvrste' => 'string',
			'Podvrsta' => 'string',
			'Developeri' => 'string',
			'Maintaineri' => 'string',
			'Kontroleri' => 'string',
			'Note' => 'string'
		);

		$rows = array();
		foreach ($data as $unit) {
			$row = array();

			if(empty($unit['module'])) {
				$row[] = '';
				$row[] = '';
			} else {
				$row[] = $unit['module']['code'];
				$row[] = $unit['module']['name'];
			}

			if(empty($unit['group'])) {
				$row[] = '';
				$row[] = '';
			} else {
				$row[] = $unit['group']['code'];
				$row[] = $unit['group']['name'];
			}

			if(empty($unit['subgroup'])) {
				$row[] = '';
				$row[] = '';
			} else {
				$row[] = $unit['subgroup']['code'];
				$row[] = $unit['subgroup']['name'];
			}

			if(empty($unit['type'])) {
				$row[] = '';
				$row[] = '';
			} else {
				$row[] = $unit['type']['code'];
				$row[] = $unit['type']['name'];
			}

			if(empty($unit['subtype'])) {
				$row[] = '';
				$row[] = '';
			} else {
				$row[] = $unit['subtype']['code'];
				$row[] = $unit['subtype']['name'];
			}

			if(empty($unit['developer'])) {
				$row[] = '';
			} else {
				$row[] = $unit['developer'];
			}

			if(empty($unit['maintainer'])) {
				$row[] = '';
			} else {
				$row[] = $unit['maintainer'];
			}

			if(empty($unit['controller'])) {
				$row[] = '';
			} else {
				$row[] = $unit['controller'];
			}

			if(empty($unit['note'])) {
				$row[] = '';
			} else {
				$row[] = $unit['note'];
			}

			$rows[] = $row;
		}

		$result['header'] = $header;
		$result['rows'] = $rows;

		return $result;
	}//~!

}