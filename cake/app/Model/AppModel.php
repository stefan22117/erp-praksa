<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
	public $validationDomain = 'validation_errors';

	/**
		* round hours mintes, ten minutes model
		* valid time presumed
		* @author Davor Petkovic
		* @version 2016-10-04
		*
		* @param string $hours
		* @param string $minutes
		* @return string H:i:s
	*/
	public function roundTimeHI($hours, $minutes){
		$hours_array = str_split($hours);
		$minutes_array = str_split($minutes);

		if ($minutes_array[1] < 5){
			$time = $hours.':'.$minutes_array[0].'0';
		}else{
			if ($minutes_array[0] == 5){
				$hours_int = (int)$hours;
				$hours_int++;
				if ($hours_int == 24){
					$hours_str = '00';
				}elseif($hours_int < 10){
					$hours_str = '0'.$hours_int;
				}else{
					$hours_str = $hours_int;
				}

				$time = $hours_str.':00';
			}else{
				$minute = (int)$minutes_array[0] + 1;
				$time = $hours.':'.$minute.'0';
			}
		}

		$result['short'] = $time;
		$result['long'] = $time.':00';
		return $result;
	}//~!

    /**
     * exist method
     *
     * Check if record with given foreign key id exist in database
     *
     * @param array $check
     * @param string $fkClass
     * @return boolean
     */
    public function exist($check, $fkClass) {

    	// Initial conditions //
    	$conditions = array(
    		'id' => $check
    	);

        // Add class if not exist //
        $this->{$fkClass} = ClassRegistry::init($fkClass);

    	// Check if deleted column exist //
    	if(in_array('deleted', array_keys($this->{$fkClass}->getColumnTypes()))) {
            // If exist, add condition //
    		$conditions['deleted'] = 0;
    	}

    	// Return true if exist //
        return $this->{$fkClass}->find('count', array(
            'conditions' => $conditions,
            'recursive' => -1
        ));

    }//~!

    /**
     * unique method
     *
     * Validation Rule
     *
     * Check if field or combination of fields are unique.
     * Work for all models with or without deleted field.
     *
     * @param array $check
     * @return boolean
     */
    public function unique($check, $fields) {

    	// Check for combination of fields //
    	if(!isset($fields['rule'])) {
    		foreach ($fields as $field) {
    			$check["{$field}"] = $this->data[$this->name]["{$field}"];
    		}
    	}

    	// Initial conditions //
    	$conditions = array(
            $check,
            'id <>' => $this->id
        );

    	// Check if deleted column exist //
    	if(in_array('deleted', array_keys($this->getColumnTypes()))) {
            // If exist, add condition //
    		$conditions['deleted'] = 0;
    	}

    	// Return true if not exist //
	    return !$this->find('count', array(
	        'conditions' => $conditions,
	        'recursive' => -1
	    ));

    }//~!

    /**
     * enumValueExists method
     *
     * Check if enum value is vaild
     *
     * @param array $check
     * @return boolean
     */
    public function enumValueExists($check) {

        foreach ($check as $key => $value) {

            // get column type
            $type = $this->schema($key)['type'];

            // return false if column type is not enum
            if (strpos($type, 'enum') !== 0) {
                return false;
            }

            // clean string
            $type = rtrim(ltrim($type, 'enum('), ')');

            // explode type (enum) values into array
            $enumValues = explode(',', $type);

            // trim '" from enum values
            $enumValues = array_map(function($enumValue) {
                return trim($enumValue, ' \'"');
            }, $enumValues);

            // if value is not in enumValues return false
            if (!in_array($value, $enumValues)) {
                return false;
            }
        }

        return true;
    }//~!

    /**
     * Returns first error from CakePHP's validationErrors array
     *
     * @since  28.03.2019
     * @param  array      $validationErrors
     * @return string
     */
    public function findValidationError( $validationErrors ) {

        if ( empty($validationErrors) &&  !($validationErrors = $this->validationErrors) ) {
            return "";
        }

        foreach ( $validationErrors as $validationError ) {
            if ( is_array($validationError) ) {
                return $this->findValidationError($validationError);
            } else {
                return $validationError;
            }
        }
    }//~!

}
