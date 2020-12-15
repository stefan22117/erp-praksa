<?php

App::uses('AppHelper', 'View/Helper');
App::uses('TimeFormat', 'Lib');

/**
 * Custom Helper with functions for handling time
 *
 * @see TimeFormat ('Lib\TimeFormat.php')
 * This Helper relies on functions from TimeFormat class
 */
class TimeFormatHelper extends AppHelper {

	/**
	 * Instance of TimeFormat class
	 *
	 * @var Class
	 */
	protected $_engine = null;

	/**
	 * Constructor
	 *
	 * ### Settings:
	 *
	 * - `engine` Class name to use to replace TimeFormat functionality
	 *
	 * @param View $View the view object the helper is attached to.
	 * @param array $settings Settings array
	 * @throws CakeException When the engine class could not be found.
	 */
	public function __construct(View $View, $settings = array()) {

		parent::__construct($View, $settings);

		if (!class_exists('TimeFormat')) {
			throw new CakeException(__d('cake_dev', 'Could not find TimeFormat.'));
		}

		$this->_engine = new TimeFormat();
	}

	/**
	 * Call methods from TimeFormat library
	 */
	public function __call($method, $params) {
		return call_user_func_array(array($this->_engine, $method), $params);
	}

}

?>