<?php
App::uses('VmVehicle', 'Model');

/**
 * VmVehicle Test Case
 *
 */
class VmVehicleTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.vm_vehicle',
		'app.vm_change_log',
		'app.vm_crossed_km',
		'app.vm_damage',
		'app.vm_external_worker_vehicle',
		'app.vm_fuel',
		'app.vm_image',
		'app.vm_internal_worker_vehicle',
		'app.vm_maintenance',
		'app.vm_vehicle_file',
		'app.vm_vehicle_internal_worker'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->VmVehicle = ClassRegistry::init('VmVehicle');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->VmVehicle);

		parent::tearDown();
	}

}
