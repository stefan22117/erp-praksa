<?php
class SearchComponent extends Component{

	/**
	 * stavlja '%' izmedju svake reci umesto razmaka
	 * @param string
	 * @return string
	 * @throws nothing
	*/
	public function formatSearchString($term){
		$term = trim($term);
		$words = explode(" ", $term);
		$value = '';
		foreach($words as $word){
			$value .= $word.'%';
		}

		return '%'.$value;
	}//~!

}
?>