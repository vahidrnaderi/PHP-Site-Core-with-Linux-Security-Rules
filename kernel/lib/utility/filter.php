<?php 

class filter {
	
	function filter(){
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> filter.php-> filter()\n");
		
	}
	
	public function queryString($needle=null){
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> filter.php-> queryString($needle)\n");
				
		$string = $_GET[filter];
		$arr = array();
		
		if(strstr($string, ",")){
			$sections = explode(",", $string);
			foreach ($sections as $section){
				if(strstr($section, "=")){
					$values = explode("=", $section);
					$arr[$values[0]] = $values[1];
				}
			}
		}else{
			if(strstr($string, "=")){
				$values = explode("=", $string);
				$arr[$values[0]] = $values[1];
			}
		}
		
		if($needle){
			return $arr[$needle];
		}else{
			return $arr;
		}
		
	}
	
}

?>