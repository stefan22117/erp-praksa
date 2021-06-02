<?php
/**
 * VmVehicleFixture
 *
 */
class VmVehicleFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'brand_and_model' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'reg_number' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'in_use' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 4),
		'active_from' => array('type' => 'date', 'null' => false, 'default' => null),
		'active_to' => array('type' => 'date', 'null' => true, 'default' => null),
		'horse_power' => array('type' => 'integer', 'null' => true, 'default' => null),
		'engine_capacity_cm3' => array('type' => 'integer', 'null' => true, 'default' => null),
		'year_of_production' => array('type' => 'integer', 'null' => true, 'default' => null),
		'color' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'number_of_seats' => array('type' => 'integer', 'null' => true, 'default' => null),
		'chassis_number' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'engine_number' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'date_of_purchase' => array('type' => 'date', 'null' => true, 'default' => null),
		'price' => array('type' => 'float', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'brand_and_model' => 'Lorem ipsum dolor sit amet',
			'reg_number' => 'Lorem ipsum dolor sit amet',
			'in_use' => 1,
			'active_from' => '2021-05-28',
			'active_to' => '2021-05-28',
			'horse_power' => 1,
			'engine_capacity_cm3' => 1,
			'year_of_production' => 1,
			'color' => 'Lorem ipsum dolor sit amet',
			'number_of_seats' => 1,
			'chassis_number' => 'Lorem ipsum dolor sit amet',
			'engine_number' => 'Lorem ipsum dolor sit amet',
			'date_of_purchase' => '2021-05-28',
			'price' => 1,
			'created' => '2021-05-28 12:22:14',
			'modified' => '2021-05-28 12:22:14'
		),
	);

}
