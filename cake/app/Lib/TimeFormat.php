<?php

/**
 * Custom class with functions for handling time
 */
class TimeFormat {

	/**
		* convert minutes in hours and minutes
		* @author Boris Urosevic
		* @param int $min
		* @return string
	*/
	function showTimeInHoursAndMinutes($min){
		if(($min/60)>=1){
			$hours = (int)($min/60) . ' sat';
			if($hours>1 && $hours<5)
				$hours = (int)($min/60) . ' sata';
			if($hours>4)
				$hours = (int)($min/60) . ' sati';
		}else{ $hours = ''; }

		if($min%60>0){
			$minutes = ($min%60).' minut';
			if($minutes>1)
				$minutes = ($min%60).' minuta';
		}else{ $minutes = '0 minuta'; }

		$time = $hours.' '.$minutes;
		return $time;
	}//~!

	/**
		* create a roman numeral from a number
		* @param int $num
		* @return string
	*/
	function romanNumerals($num)
	{
		$n = intval($num);
		$res = '';

		/*** roman_numerals array  ***/
		$roman_numerals = array(
				'M'  => 1000,
				'CM' => 900,
				'D'  => 500,
				'CD' => 400,
				'C'  => 100,
				'XC' => 90,
				'L'  => 50,
				'XL' => 40,
				'X'  => 10,
				'IX' => 9,
				'V'  => 5,
				'IV' => 4,
				'I'  => 1);

		foreach ($roman_numerals as $roman => $number)
		{
			/*** divide to get  matches ***/
			$matches = intval($n / $number);

			/*** assign the roman char * $matches ***/
			$res .= str_repeat($roman, $matches);

			/*** substract from the number ***/
			$n = $n % $number;
		}

		/*** return the res ***/
		return $res;
	}//~!

	/**
	 * Calculate minutes between two dates
	 * @author Boris Urosevic
	 * @param string - from date
	 * @param string - to date
	 * @param int - minutes
	*/
	function minutesBetweenTwoDates($date1, $date2){
		$timestamp1 = strtotime($date1);
		$timestamp2 = strtotime($date2);

		$minutes = intval(($timestamp2 - $timestamp1) / 60);

		return $minutes;
	}//~!

	/**
	 * Validate date format
	 * @param string - date
	 * @param format to check
	 * @return boolean
	*/
	function validateDate($date, $format = 'Y-m-d H:i:s'){
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}//~!

	/**
	 * Show time in years and months
	 * @author Boris Urosevic - boris.urosevic@mikroe.com
	 * @since 08.09.2017
	 * @param int $days - number of days
	 * @return string $result in format nYxM where n is number of years and x is number of months
	*/
	public function convertDaysInYearsAndMonths($days){
		if ( $days < 0 ){
			return '0M';
		}

		$years = floor( $days / 365 );
		$months = floor( ($days / 365 - $years) * 12 );

		$result = $years < 1 ? $months.'M' : $years.'Y'.$months.'M';

		return $result;
	}//~!

	/**
	 * Get array of all months
	 * @author Boris Urosevic - boris.urosevic@mikroe.com
	 * @since 24.06.2019
	 * @return array $months
	*/
	public function getMonthList(){
		$months = array(
			'01' => 'Januar',
			'02' => 'Februar',
			'03' => 'Mart',
			'04' => 'April',
			'05' => 'Maj',
			'06' => 'Jun',
			'07' => 'Jul',
			'08' => 'Avgust',
			'09' => 'Septembar',
			'10' => 'Oktobar',
			'11' => 'Novembar',
			'12' => 'Decembar',
		);

		return $months;
	}//~!

	/**
	 * Calculate days from today to date_due
	 * @author Boris Urosevic <boris.urosevic@mikroe.com>
	 * @param string - Date due
	 * @return string - days (~ number of weeks)
	*/
	public function dateDueToDays($date_due = null){
		$datediff = strtotime($date_due)-time();
		$days = (string)round($datediff/(60*60*24));
		$weeks = (string)round($datediff/(60*60*24)/7);
		$result = $days . 'd (~ '.$weeks.' ned)';

		return $result;
	}//~!

	/**
	 * Convert seconds to time (HH:mm:ss)
	 * @param int - Time stamp
	 * @return string - HH:mm:ss
	*/
    public function toTime($timestamp) {

    	$return_sign = '';

    	if ($timestamp < 0) {
    		$timestamp = abs($timestamp);
    		$return_sign = '-';
    	}

        $hours = str_pad( floor($timestamp/3600) , 2, '0', STR_PAD_LEFT);
        $minutes = str_pad( floor(($timestamp%3600)/60) , 2, '0', STR_PAD_LEFT);
        $seconds = str_pad( ($timestamp%3600)%60 , 2, '0', STR_PAD_LEFT);

        return $return_sign . $hours . ':' . $minutes . ':' . $seconds;
    }//~!

    /**
	 * Convert seconds to time (HH:mm)
	 *
	 * For example:
	 * - $timestamp = 1 	will be 00:01 instead of 00:00:01
	 * - $timestamp = 4553 	will be 01:16 instead of 01:15:23
	 *
	 * @param int - Time stamp
	 * @return string - HH:mm
	*/
    public function toMinutes($timestamp) {

    	$return_sign = '';

    	if ($timestamp < 0) {
    		$timestamp = abs($timestamp);
    		$return_sign = '-';
    	}


    	$seconds = ($timestamp % 3600) % 60;

    	$add_seconds = 60 - $seconds;

    	$full_minute_timestamp = $timestamp + $add_seconds;

    	$hours = str_pad(floor($full_minute_timestamp / 3600) , 2, '0', STR_PAD_LEFT);
        $minutes = str_pad(floor(($full_minute_timestamp % 3600) / 60) , 2, '0', STR_PAD_LEFT);

        return $return_sign . $hours . ':' . $minutes;
    }//~!

    /**
     * Converts time (H:m:i format) to seconds
     *
     * @see https://stackoverflow.com/questions/4834202/convert-time-in-hhmmss-format-to-seconds-only#answer-20874702
     *
     * @since  22.05.2020
     * @param  string     $time 00:00:00
     * @return int
     */
    public function timeToSec($time) {

        $seconds = 0;

        foreach (array_reverse(explode(':', $time)) as $k => $v) {

            $seconds += pow(60, $k) * $v;
        }

        return $seconds;
	}//~!

	/**
	 * Generate year calendar columns array
	 * @author Marko Jovanovic <marko@mikroe.com>
	 * @param int - Selected year
	 * @return array with months and dates
	*/
	public function generateYearColumnArray($year){
		//Generate columns
		$generated_columns = array();
		for ($month=1; $month <= 12 ; $month++) {
			//Set date range
			$date_from = date($year.'-'.str_pad($month, 2, '0', STR_PAD_LEFT).'-01');
			$date_to = date($year.'-'.str_pad($month, 2, '0', STR_PAD_LEFT).'-t');
			//Init objects
			$start = new DateTime($date_from);
			$end = new DateTime($date_to);
			// otherwise the  end date is excluded (bug?)
			$end->modify('+1 day');
			// create an iterateable period of date (P1D equates to 1 day)
			$period = new DatePeriod($start, new DateInterval('P1D'), $end);
			//Set current month
			$currentMonth = $start->format('M');
			//Init column
			$generated_columns[$currentMonth] = array();
			//Process period
			foreach($period as $dt) {
				//Set current day
				$currentDay = $dt->format('D');
				$generated_columns[$currentMonth][] = $currentDay;
			}
		}
		//Process columns
		$columns = $generated_columns['Jan'];
		unset($generated_columns['Jan']);
		foreach ($generated_columns as $month => $days) {
			//Set first day of month
			$first_day = $days[0];
			//Set compare columns
			$compare_columns = $columns;
			$column_count = count($compare_columns);
			for ($cc=0; $cc < $column_count; $cc++) {
				//Set compare day
				$compare_day = $compare_columns[$cc];
				if($compare_day == $first_day){
					break;
				}
				unset($compare_columns[$cc]);
			}
			//Compare with days in current month
			$column_count = count($compare_columns);
			for ($lc=$cc, $dc=0; $lc < $column_count + $cc; $lc++, $dc++) {
				//Unset compare columns
				unset($compare_columns[$lc]);
				//Unset days
				if(!empty($days[$dc])) unset($days[$dc]);
			}
			//If there are any days leftovers merge it with columns
			if(!empty($days)){
				foreach ($days as $day) {
					$columns[] = $day;
				}
			}
		}
		//Return columns
		return $columns;
	}//~!


	
	/** 
	 * Get all weeks between two dates
	 * @author Boris Urosevic <boris.urosevic@mikroe.com>
	 * @since 19.06.2020
	 * @param string $date_from
	 * @param string $date_to
	 * @param $selected_years [optional]
	 * @return $array of intervals
	 */
	public function getWeekIntervals($date_from, $date_to, $selected_years = array()){
		if ( !empty( $selected_years ) ){
			$date_from = min($selected_years).date('-m-d', strtotime($date_from));
		}
		
		if ( !empty( $selected_years ) ){
			$date_to = max($selected_years).date('-m-d', strtotime($date_to));
		}

        $start_date = strtotime( $date_from );
        $end_date = strtotime( $date_to );

		$start_week = $this->getWeekOfDate( $date_from );
		$end_week = $this->getWeekOfDate( $date_to );

        $intervals = array();
        while( $start_date <= $end_date ){
			$week = $this->getWeekOfDate( $start_date );
			
			if( empty( $selected_years ) ){
				$intervals[] = array(
					(int)$week,
                    (int)date('Y', $start_date),
                    'w'.date('W/y', $start_date),
					'w'.$week,
					(int)date('m', $start_date),
                );
			}else if( $week >= $start_week && $week <= $end_week ){
				$intervals[] = array(
					(int)$week,
					(int)date('Y', $start_date),
					'w'.date('W/y', $start_date),
					'w'.$week,
					(int)date('m', $start_date),
				);
            }

            $start_date += strtotime('+1 week', 0);
		}

        return $intervals;
    }//~!

	/** 
	 * Get all months between two dates
	 * @author Boris Urosevic <boris.urosevic@mikroe.com
	 * @since 19.06.2020
	 * @param string $date_from
	 * @param string $date_to
	 * @param $selected_years [optional]
	 * @return $array of intervals
	 */
    public function getMonthIntervals($date_from, $date_to, $selected_years = array()){
		if ( !empty( $selected_years ) ){
			$date_from = min($selected_years).date('-m-d', strtotime($date_from));
		}
		
		if ( !empty( $selected_years ) ){
			$date_to = max($selected_years).date('-m-d', strtotime($date_to));
		}

        $start_date = strtotime( $date_from );
        $end_date = strtotime( $date_to );

        $start_month = date('m', strtotime($date_from));
        $end_month = date('m', strtotime($date_to));

        $intervals = array();

        while ($start_date <= $end_date) {
            if( empty( $selected_years ) ){
                $interval = array(
                    (int)date('m', $start_date),
                    (int)date('Y', $start_date)
                );
            }else{
                if( date('m', $start_date) >= $start_month && date('m', $start_date) <= $end_month ){
                    $interval = array(
						(int)date('m', $start_date),
						(int)date('Y', $start_date)
					);
                }
            }

            if ( !array_key_exists($interval[0].$interval[1], $intervals) ){                
                $interval[] = date('M/y', $start_date);
				$interval[] = date('M', $start_date);
				$interval[] = 0;

                $intervals[$interval[0].$interval[1]] = $interval;
            }
            $start_date += strtotime('+1 day', 0);
		}

        return $intervals;
    }//~!

	/** 
	 * Get all quarters between two dates
	 * @author Boris Urosevic <boris.urosevic@mikroe.com
	 * @since 19.06.2020
	 * @param string $date_from
	 * @param string $date_to
	 * @param $selected_years [optional]
	 * @return $array of intervals
	 */
    public function getQuarterIntervals($date_from, $date_to, $selected_years = array()){
		if ( !empty( $selected_years ) ){
			$date_from = min($selected_years).date('-m-d', strtotime($date_from));
		}
		
		if ( !empty( $selected_years ) ){
			$date_to = max($selected_years).date('-m-d', strtotime($date_to));
		}

        $start_date = strtotime( $date_from );
        $end_date = strtotime( $date_to );

        $start_quarter = ceil((float)date('m', strtotime($date_from))/3);
        $end_quarter = ceil((float)date('m', strtotime($date_to))/3);

        $intervals = array();

        while ($start_date <= $end_date) {
            if( empty( $selected_years ) ){
                $interval = array(
                    (int)ceil((float)date('m', $start_date)/3),
                    (int)date('Y', $start_date)
                );
            }else{
                if( ceil((float)date('m', $start_date)/3) >= $start_quarter && ceil((float)date('m', $start_date)/3) <= $end_quarter ){
                    $interval = array((int)ceil((float)date('m', $start_date)/3), (int)date('Y', $start_date));
                }
            }

            if ( !array_key_exists($interval[0].$interval[1], $intervals) ){
                $interval[] = 'Q'.ceil((float)date('m', $start_date)/3).'/'.date('y', $start_date);
				$interval[] = 'Q'.ceil((float)date('m', $start_date)/3);
				$interval[] = 0;

                $intervals[$interval[0].$interval[1]] = $interval;
            }
            $start_date += strtotime('+1 day', 0);
		}

        return $intervals;
    }//~!

	/** 
	 * Get all years between two dates
	 * @author Boris Urosevic <boris.urosevic@mikroe.com
	 * @since 19.06.2020
	 * @param string $date_from
	 * @param string $date_to
	 * @param $selected_years [optional]
	 * @return $array of intervals
	 */
    public function getYearIntervals($date_from, $date_to, $selected_years = array()){
		if ( !empty( $selected_years ) ){
			$date_from = min($selected_years).date('-m-d', strtotime($date_from));
		}
		
		if ( !empty( $selected_years ) ){
			$date_to = max($selected_years).date('-m-d', strtotime($date_to));
		}

        $start_date = strtotime( $date_from );
        $end_date = strtotime( $date_to );

        $intervals = array();

        $first_year = date('Y', strtotime($date_from));
        $last_year = date('Y', strtotime($date_to));
        $start_date = strtotime($first_year.'-'.date('m-d', strtotime($date_from)));

        while ($start_date <= $end_date) {
            $interval = array(
                (float)date('Y', $start_date),
                (float)date('Y', $start_date),
                (float)date('Y', $start_date),
				'Year',
				0,
            );

            if ( !in_array($interval, $intervals) && (date('Y', $start_date) >= $first_year && date('Y', $start_date) <= $last_year) ){
                $intervals[] = $interval;
            }

            $start_date += strtotime('+1 day', 0);
		}

        return $intervals;
	}//~!
	
	/**
	 * Get all dates between two dates by selected resolution
	 * @author Boris Urosevic <boris.urosevic@mikroe.com>
	 * @since 01.09.2020
	 * @param string $from  From date
	 * @param string $to  To date
	 * @param string $resolution  Resolution
	 * @return array
	 */
	public function getDatesByResolution($from, $to, $resolution){
        $start_date = $from;

        switch( $resolution ){
            case 'day':
                $add = '+1 day';
                break;
            case 'week':
                $add = '+1 week';
                break;
            case 'month':
                $add = '+1 month';
                break;
            case 'quarter':
                $add = '+3 month';
                break;
            case 'year':
                $add = '+1 year';
				break;
			default:
				$add = '+1 week';
        }

        while( $start_date < $to ){
            $intervals[] = $start_date;
            $start_date = date('Y-m-d', strtotime($add, strtotime($start_date)));
        }
        $intervals[] = $to;

        return $intervals;
	}//~!
	
	/**
	 * Get week of date in year
	 * If year start with week 53, set to be 1
	 * If year end with week 1, set to be 53
	 * @author Boris Urosevic <boris.urosevic@mikroe.com>
	 * @since 08.09.2020
	 * @param string $date  Date
	 * @return int $week 
	 */
	public function getWeekOfDate($date){
		if( is_string($date) ){
			$date = strtotime($date);
		}

		$month = date('n', $date);
		$week = date('W', $date);
		
		if( $month == 12 && $week == 1 ){
			$week = 53;
		}
		if( $month == 1 && $week > 51 ){
			$week = 1;
		}

		return $week;
	}//~!
}
?>