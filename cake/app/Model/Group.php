<?php

class Group extends AppModel {
    var $name = 'Group';
    public $actsAs = array('Acl' => array('type' => 'requester'));
    public $proizvodnja = 19; // id grupe Proizvodnja
    public $knjigovodstvo_group_id = 17; // id grupe Knjigovodstvo
    public $admin_group_id = 1; // id grupe Administratori
    public $magsped_group_id = 12; // id grupe Spedicija i magacioneri
    public $mag_group_id = 16; // id grupe Magacioneri
    public $sales_group_id = 5; // id grupe prodaja
    public $support_group_id = 9; //id grupe tehnicka podrska
    public $manager_group_id = 4; //id grupe menadzeri

    public $hasMany = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'group_id'
        )
    );

    public $validate = array(
        'name' => array(
            'nameRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Group name is required'
                ),
            'nameRule2' => array(
                'rule' => 'isUnique',
                'message' => 'Group name must be unique'
            )
        )
    );

    public function parentNode() {
        return null;
    }

    /**
     * getPermissions
     *
     * @param int $groupId
     * @return array
     */
    public function getPermissions($groupId){
        $data = array();
        $group = $this->find('first', array(
            'conditions' => array(
                'Group.id' => $groupId
            ),
            'recursive' => -1
        ));
        $data['group'] = $group;
        return $data;
    }//~!
}
?>