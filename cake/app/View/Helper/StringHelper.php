<?php

App::uses('AppHelper', 'View/Helper');

/**
 * StringHelper
 */
class StringHelper extends AppHelper {

	/**
	 * Splits string containing $keywords by order
	 *
	 * @author Stevan Knezevic - stevan.knezevic@mikroe.com
	 * @since  17.01.2019
	 * @param  string     $string           String to split - haystack
	 * @param  array      $keywords         Keywords to extract - needle
	 * @param  string     $replacement_char Character that isn't in any of keywords
	 * @return array                        Array of keywords extracted from string, ordered by their inital position
	 */
	public function splitStringByKeywords($string, $keywords, $replacement_char = '_') {

		$splits = array();
		// Sort array of operators by strlen in descending order
		usort($keywords,  function($a, $b) {
			return strlen($b) - strlen($a);
		});

		foreach ($keywords as $keyword) {

			// Check for operator in string, this will also be key of new array
			$key = strpos($string, $keyword);
			while ($key !== false) {
				$keyword_len = strlen($keyword);
				// Create replacment string
				$replacement_str = str_repeat($replacement_char, $keyword_len);
				// Replace this occurrence of $keyword and maintain orignal $string length
				$string = substr_replace($string, $replacement_str, $key, $keyword_len);
				// Store operator
				$splits[$key] = $keyword;
				// Check for operator in string
				$key = strpos($string, $keyword);
			}
		}

		// Sort array by keys
		ksort($splits);

		return $splits;
	}//~!

	/**
	 * Formats number with php's number_format function and default spearators
	 * Does not round a number
	 * Removes trailing zeros after $min_decimals places
	 *
	 * @todo Try to find better logic, for now number is stripped of decimals and then rebuilt
	 *
	 * @author Stevan Knezevic - stevan.knezevic@mikroe.com
	 * @since  10.04.2019
	 * @param  int|float  $number  		Number to format
	 * @param  int 		  $min_decimals Minumum decimal places, trailing zeros
	 * @return string     Human friendly formated number
	 */
	public function numberFormatTrim($number, $min_decimals = 0)	{

		// Separator for the decimal point
		$dec_point = '.';

		$number_pieces = explode($dec_point, $number);

		// set decimals, default is 0 for triming
		$decimals = isset($number_pieces[1]) ? $number_pieces[1] : '0';

		// number_format on clean number then append decimals
		$number_format = number_format($number_pieces[0]) . $dec_point . $decimals;

		// trim trailing zeros & trim decimal
		$number_format = rtrim(rtrim($number_format, '0'), $dec_point);

		// If number has to have trailing zeros, add them
		if ($min_decimals > 0) {

			$number_pieces = explode($dec_point, $number_format);

			// If nubmer does not have decimals at all just add them
			if (!isset($number_pieces[1])) {

				$number_format = $number_pieces[0] . '.' . str_repeat('0', $min_decimals);
			}
			// If nubmer does not have enough decimals, add missing
			elseif ( ($add_decimals = $min_decimals - strlen($number_pieces[1])) > 0 ) {

				$number_format = $number_pieces[0] . '.' . $number_pieces[1] . str_repeat('0', $add_decimals);
			}
		}

		return $number_format;
	}//~!
}
