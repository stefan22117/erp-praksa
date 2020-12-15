<?php
App::uses('AppModel', 'Model');

/**
 * MenuItem Model
 *
 */
class MenuItem extends AppModel {
    
    /**
     * Model name
     *
     * @var string
     */
    public $name = 'MenuItem';

    /**
     * Behaviors
     *
     * @var array
     */
    public $actsAs = array('Containable');

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        // Menu item has many child menu items //
        'Child' => array(
            'className' => 'MenuItem',
            'foreignKey' => 'parent_id'
        )
    );
    
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        // Menu item belongs to parent menu item //
        'Parent' => array(
            'className' => 'MenuItem',
            'foreignKey' => 'parent_id'
        ),
        // Menu item belongs to Aco object //
        'Aco' => array(
            'className' => 'Aco',
            'foreignKey' => 'aco_id'
        ),
        // Menu item has icon //
        'ErpKickstartIcon' => array(
            'className' => 'ErpKickstartIcon',
            'foreignKey' => 'erp_kickstart_icon_id'
        )
    );
    
    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'parent_id' => array(
            // Menu item with this id has to exist //
            'parentIdRule1' => array(
                'rule' => array('exist', 'MenuItem'),
                'message' => 'Stavka u meniju sa ovim id-jem ne postoji',
                'required' => false,
                'allowEmpty' => true
            )
        ),
        'aco_id' => array(
            // Aco object with this id has to exist //
            'acoIdRule1' => array(
                'rule' => array('exist', 'Aco'),
                'message' => 'Aco objekat sa ovim id-jem ne postoji',
                'required' => false,
                'allowEmpty' => true
            )
        ),
        'erp_kickstart_icon_id' => array(
            // Icon with this id has to exist //
            'erpKickstartIconIdRule1' => array(
                'rule' => array('exist', 'ErpKickstartIcon'),
                'message' => 'Ikonica sa ovim id-jem ne postoji',
                'required' => false,
                'allowEmpty' => true
            )
        ),
        'name' => array(
            // Field cannot be left blank //
            'nameRule1' => array(
                'rule' => array('notEmpty'),
                'message' => 'Polje ne moze da bude prazno',
                'required' => true
            )
        )
    );

    /**
     * countChildMenuItems method
     *
     * Count all child menu items
     *
     * @throws nothing
     * @param int $menuItemId Id of menu item
     * @return boolean
     */
    public function countChildMenuItems($menuItemId) {
        return $this->find('count', array(
            'conditions' => array(
                'parent_id' => $menuItemId,
                'deleted' => 0
            ),
            'recursive' => -1
        ));
    }

    /**
     * deleteMenuItem method
     *
     * Delete menu item
     *
     * @throws NotFoundException
     * @param int $id Menu item id
     * @return result or boolean
     */
    public function deleteMenuItem($id = null) {

        // Set id //
        $this->id = $id;
        
        // Check if record exist //
        if (!$this->exists()) {
            throw new NotFoundException(__('Stavka u meniju sa ovim id-jem ne postoji'));
        } else {

            // Check if record has associated data //
            if(!$this->countChildMenuItems($id)) {
        
                // Soft delete //
                return $this->saveField('deleted', 1);
        
            } else {
                return false;
            }

        }
    
    }

}