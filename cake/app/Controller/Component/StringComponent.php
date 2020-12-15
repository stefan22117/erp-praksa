<?php
class StringComponent extends Component{

	/**
	 * Get substring between two strings PHP
	 * @param $string - main string, $start - start string, $end - end string
	 * @return string in between
	 * @throws nothing
	*/
	public function getStringBetween($string, $start, $end){
	    $string = " ".$string;
	    $ini = strpos($string,$start);
	    if ($ini == 0) return "";
	    $ini += strlen($start);
	    $len = strpos($string,$end,$ini) - $ini;
	    return substr($string,$ini,$len);
	}//~!

	/**
	 * Get substring between two strings PHP
	 * @param $params - redirect paramaters, $referer - referer string
	 * @return $options - array with redirect options
	 * @throws nothing
	*/
	public function redirectReffererParser($params, $referer){
		$options = $params;

        //Check for query
        $query_str = strstr($referer, '?');        
        $query = array();
        if(!empty($query_str)){
            $query_str = ltrim($query_str, '?');
            parse_str($query_str, $query);
        }                

        if(!empty($query)) {
        	$options['?'] = $query;        
        	$page_number = (int)$this->getStringBetween($referer, "page:", "?");
        }else{
        	$arr = explode('page:', $referer);        	
			if(!empty($arr[1])) $page_number = (int)$arr[1];
        }

        //Check for page number        
        if(!empty($page_number)){
            $options['page'] = $page_number;
            if(!empty($query)) {
            	$options['?']['page'] = $page_number;
            }
        }

	    return $options;
	}//~!

	/**
	 * Linking urls in text
	 * @param string
	 * @return string
	*/
	public function text_to_hyperlink($text){
		// The Regular Expression filter
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

		// Check if there is a url in the text
		if(preg_match($reg_exUrl, $text, $url)) {
			// make the urls hyper links
			$result = preg_replace($reg_exUrl, "<a href=".$url[0]." target='_blank'>{$url[0]}</a> ", $text);
		} else {
			// if no urls in the text just return the text
			$result = $text;
		}

		return $result;
	}//~!
	
	/**
	 * method for generating random string
	 * @param $length - number of random characters
	 * @return void
	 */
    public function generateRandomString($length = 5) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }//~!
}
?>