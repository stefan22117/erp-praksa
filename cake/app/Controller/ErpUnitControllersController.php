<?php
class ErpUnitControllersController extends AppController {

    /**
     * index
     *
     * @throws nothing
     * @param int id
     * @return void
     */
    public function view($id) {
		$this->set('title_for_layout', __('Kontroleri i akcije - Organizacija ERP-a - MikroERP'));
        $data = $this->ErpUnitController->getOneForView($id);
        foreach ($data as $key => $value) $this->set($key, $value);
    }//~!

    /**
     * changeDescription
     *
     * @throws nothing
     * @param int id
     * @return string
     */
    public function changeDescription($id) {
        $newValue = $this->request->data['newValue'];
        $this->ErpUnitController->id = $id;
        $this->ErpUnitController->saveField('description', $newValue);
        echo $newValue;
    }//~!

}