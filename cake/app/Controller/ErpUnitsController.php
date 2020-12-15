<?php

App::import('Vendor', 'phpexcel', array('file' => 'phpexcel/excel.php'));
App::import('Vendor', 'xlsxwriter', array('file' => 'xlsxwriter/xlsxwriter.class.php'));

class ErpUnitsController extends AppController {

	public $components = array('ErpLogManagement', 'Paginator', 'Search');

	/**
	 * index
	 *
	 * @throws nothing
	 * @param none
	 * @return void
	 */
	public function index($level = null, $parentId = null) {
		$this->set('title_for_layout', __('Organizacija ERP-a - MikroERP'));
		$data = $this->ErpUnit->getIndexData($level, $parentId);
		if(isset($this->request->query['keywords'])) {
			$keywords = $this->request->query['keywords'];
			if(!empty($keywords)) {
				$data['settings']['conditions'] = array('ErpUnit.name LIKE' => '%'.$keywords.'%');
				$this->request->data['ErpUnit']['keywords'] = $keywords;
			}
		}
		$this->Paginator->settings = $data['settings'];
		$erpUnits = $this->Paginator->paginate();
		$data['erpUnits'] = $this->ErpUnit->getDevelopersMaintainers($erpUnits);
		$this->set('data', $data);
	}//~!

	/**
	 * preview
	 *
	 * @throws nothing
	 * @param none
	 * @return void
	 */
	public function preview() {
		$this->layout = 'fullscreen';
		$this->set('title_for_layout', __('Organizacija ERP-a - MikroERP'));
		$filters = $this->request->query;
		$data = $this->ErpUnit->getData($filters);
		$this->set('data', $data);
		$modules = $this->ErpUnit->getParentsList('module');
		$this->set('modules', $modules);
		if(isset($this->request->query['module_id']))
			$this->request->data['ErpUnit']['module_id'] = $this->request->query['module_id'];
		if(isset($this->request->query['group_id']))
			$this->request->data['ErpUnit']['group_id'] = $this->request->query['group_id'];
		if(isset($this->request->query['subgroup_id']))
			$this->request->data['ErpUnit']['subgroup_id'] = $this->request->query['subgroup_id'];
		if(isset($this->request->query['type_id']))
			$this->request->data['ErpUnit']['type_id'] = $this->request->query['type_id'];
		if(isset($this->request->query['subtype_id']))
			$this->request->data['ErpUnit']['subtype_id'] = $this->request->query['subtype_id'];
		$developers = $this->ErpUnit->getDevelopers();
		$this->set('developers', $developers);
		$this->set('maintainers', $developers);
		if(isset($this->request->query['developer_id']))
			$this->request->data['ErpUnit']['developer_id'] = $this->request->query['developer_id'];
		if(isset($this->request->query['maintainer_id']))
			$this->request->data['ErpUnit']['maintainer_id'] = $this->request->query['maintainer_id'];
	}//~!

	/**
	 * view
	 *
	 * @throws nothing
	 * @param none
	 * @return void
	 */
	public function view($id) {
		if(!empty($id)) {
			$this->set('title_for_layout', __('Detalji - Organizacija ERP-a - MikroERP'));
			$unit = $this->ErpUnit->getOne($id);
			$this->set('unit', $unit);
			$this->set('levels', $this->ErpUnit->levels);
		}
	}//~!

	/**
	 * save
	 *
	 * @throws nothing
	 * @param none
	 * @return void
	 */
	public function save($level, $parentId = null, $id = null) {
		$this->set('title_for_layout', __('Snimanje - Organizacija ERP-a - MikroERP'));
		$this->set('levels', $this->ErpUnit->levels);
		$this->set('level', Inflector::singularize($level));
		$this->set('parentId', $parentId);
		// if(isset($this->request->data['ErpUnit']['level']))
			$this->set('parents', $this->ErpUnit->getParents(Inflector::singularize($level)));
		// else
		// 	$this->set('parents', $this->ErpUnit->getParents());
		$this->set('developers', $this->ErpUnit->getDevelopers());
		$this->set('maintainers', $this->ErpUnit->getDevelopers());
		$this->set('controllers', $this->ErpUnit->getControllers());
		$this->set('acos', $this->ErpUnit->getAcos($id));

		if($this->request->is(array('put','post'))) {
			if(!$this->ErpUnit->saveErpUnit($this->request->data, $id)) {
				$this->Session->setFlash(array_shift($this->ErpUnit->validationErrors)[0], 'flash_error');
			} else {
				$this->Session->setFlash('Modul je uspesno sačuvan', 'flash_success');
				if($parentId == 'null') $parentId = null;
				$this->redirect(array(
					'controller' => 'ErpUnits',
					'action' => 'index',
					$level,
					$parentId
				));
			}
		} else {
			if(!empty($id)) {
				$this->request->data = $this->ErpUnit->find('first', array(
					'conditions' => array(
						'ErpUnit.id' => $id
					),
					'contain' => array('ErpUnitDeveloper', 'ErpUnitController')
				));
				$acos = array();
				foreach ($this->request->data['ErpUnitController'] as $erpUnitController) {
					if(!$erpUnitController['deleted'])
						$acos[] = $erpUnitController['aco_id'];
				}
				foreach ($acos as $acoId) {
					$aco = $this->Acl->Aco->find('first', array(
						'conditions' => array(
							'Aco.id' => $acoId
						),
						'recursive' => -1
					));
					if(!empty($aco['Aco']['parent_id']) && $aco['Aco']['parent_id'] == 1) {
						$childAcos = $this->Acl->Aco->find('all', array(
							'conditions' => array(
								'Aco.parent_id' => $aco['Aco']['id']
							),
							'recursive' => -1
						));
						foreach ($childAcos as $childAco) {
							if ($pos = array_search($childAco['Aco']['id'], $acos)) {
								unset($acos[$pos]);
							}
						}
					}
				}
				//debug($acos); exit();
				$this->request->data['ErpUnit']['aco_id'] = $acos;
				$developers = array();
				$maintainers = array();
				foreach ($this->request->data['ErpUnitDeveloper'] as $erpDeveloper) {
					if($erpDeveloper['type'] == 'developer') {
						$developers[] = $erpDeveloper['erp_developer_id'];
					}
					if($erpDeveloper['type'] == 'maintainer') {
						$maintainers[] = $erpDeveloper['erp_developer_id'];
					}
				}
				$this->request->data['ErpUnit']['developer_id'] = $developers;
				$this->request->data['ErpUnit']['maintainer_id'] = $maintainers;
				if(isset($this->request->data['ErpUnit']['level']))
					$this->set('parents', $this->ErpUnit->getParents($this->request->data['ErpUnit']['level']));
				else
					$this->set('parents', $this->ErpUnit->getParents());
			}
		}
	}//~!

	/**
	 * delete
	 *
	 * @throws nothing
	 * @param none
	 * @return void
	 */
	public function delete($level, $parentId, $id) {
		if(!empty($id)) {
			if(!$this->ErpUnit->deleteErpUnit($id)) {
				$this->Session->setFlash('Modul ne može biti izbrisan', 'flash_error');
			} else {
				$this->Session->setFlash('Modul je uspešno izbrisan', 'flash_success');
			}
			$this->redirect($this->referer());
		}
	}//~!

	/**
	 * selectParents
	 *
	 * @throws nothing
	 * @param none
	 * @return void
	 */
	public function selectParents() {
		if($this->request->is('ajax')) {
			$level = $this->request->data['level'];
			$result = $this->ErpUnit->getParents($level);
			$this->set('result', $result);
			$this->set('_serialize', 'result');
		} else {
			$this->Session->setFlash(__('Nepostojeća stranica.'), 'flash_error');
			$this->redirect(array('action' => 'index'));
		}
	}//~!

	/**
	 * selectGroups
	 *
	 * @throws nothing
	 * @param none
	 * @return void
	 */
	public function selectGroups() {
		if($this->request->is('ajax')) {
			$id = $this->request->data['id'];
			$result = $this->ErpUnit->getParentsListById($id);
			$this->set('result', $result);
			$this->set('_serialize', 'result');
		} else {
			$this->Session->setFlash(__('Nepostojeca stranica.'), 'flash_error');
			$this->redirect(array('action' => 'preview'));
		}
	}//~!

	/**
	 * exportToExcel
	 *
	 * @throws nothing
	 * @param none
	 * @return void
	 */
	public function exportToExcel() {
		$this->layout = 'xlsx';
		$data = $this->ErpUnit->exportToExcel();
		// debug($data); exit();
		$writer = new XLSXWriter();
		$this->set('writer', $writer);
		$this->set('header', $data['header']);
		$this->set('data', $data['rows']);
		$this->set('filename', 'mikroerp.xlsx');
		$this->response->type('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$this->render('excel');
	}//~!

	/**
	 * importFromExcel
	 *
	 * @throws nothing
	 * @param none
	 * @return void
	 */
	public function importFromExcel() {
    	if($this->request->is('post')) {
			$data = array();
			if($this->data['ErpUnit']['upload']['name'] != '') {
				$tmpName = $this->data['ErpUnit']['upload']['tmp_name'];
				$filename = 'files'.DS.$this->data['ErpUnit']['upload']['name'];
				$parts = split("\.", $filename);
				$ext = $parts[count($parts) - 1]; 
				if(in_array($ext, array('xls', 'xlsx'))) {
					move_uploaded_file($tmpName, $filename);
					$src = WWW_ROOT.$filename;
		            $inputFileName = $src;
		            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
		            $objPHPExcel = $objReader->load($inputFileName);
	                $sheet = $objPHPExcel->getSheet(0); 
	                $highestRow = $sheet->getHighestRow();
	                $firstColum = 'A';
					$highestColumn = 'W';
	                $row = 3;

	                for ($row; $row <= $highestRow; $row++){ 
						$rowData = $sheet->rangeToArray($firstColum.$row.':'.$highestColumn.$row, NULL, TRUE, FALSE);
	                    $data[] = $rowData[0];
					}
				}
			}
			$result = $this->ErpUnit->importFromExcel($data);
			if($result) {
				$this->Session->setFlash(__('Podaci uspešno uvučeni iz Excel fajla.'), 'flash_success');
				$this->redirect(array('controller' => 'ErpUnits', 'action' => 'index'));
			}
		}
	}//~!

	public function selectAco() {
		if($this->request->is('ajax')) {
			$acoId = $this->request->data['acoId'];
			$aco = $this->Acl->Aco->find('first', array(
				'conditions' => array(
					'Aco.id' => $acoId
				),
				'recursive' => -1
			));
			$result = array();
			$result[$aco['Aco']['id']] = $aco['Aco']['alias'];
			if (!empty($aco['Aco']['parent_id']) && $aco['Aco']['parent_id'] != 1) {
				$parentAco = $this->Acl->Aco->find('first', array(
					'conditions' => array(
						'Aco.id' => $aco['Aco']['parent_id']
					),
					'recursive' => -1
				));
				$result[$aco['Aco']['id']] = $parentAco['Aco']['alias'].'/'.$aco['Aco']['alias'];
			}
			$this->set('result', $result);
			$this->set('_serialize', 'result');
		} else {
			$this->Session->setFlash(__('Nepostojeca stranica.'), 'flash_error');
			$this->redirect(array('action' => 'index'));
		}
	}

}
