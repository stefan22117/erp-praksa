<?php

if (!function_exists('max_number')) {

    /**
     * Returns greates numeric value given or null
     *
     * @author Stevan Knezevic - stevan.knezevic@mikroe.com
     * @since  03.12.2020
     * @param  mixed $value (mixed $value , mixed ...$values)
     * @return numeric value or null
     */
    function max_number() {

    	$args = func_get_args();
    	$args_number = func_num_args();

    	// At least 1 parameter is expected
		if ($args_number < 1) {
			trigger_error("max_number() expects at least 1 parameter, {$args_number} given", E_USER_WARNING);
			return null;
		}

		// Filter all numeric, null and empty string values
		$filtered_args = array_filter($args, function($value) {
			return is_numeric($value) || is_null($value) || $value === '';
		});

		// If all parameters are not numeric, null or an empty string
		if (count($filtered_args) !== $args_number) {

			// Get filtered out parameters
			$bad_values = array_diff_key($args, $filtered_args);
			trigger_error(
                'max_number() expects all parameters to be numeric, null or an empty string, ' . gettype(reset($bad_values)) . ' given',
                E_USER_WARNING
            );
			return null;
		}

		// Filter out all null and empty string values
		$filtered_args = array_filter($args, 'strlen');

		// If all parameters are null or an empty string - return null
		if (count($filtered_args) === 0) {
			return null;
		}

		// Return greates numeric value
		return max($filtered_args);
    }//~!
}
