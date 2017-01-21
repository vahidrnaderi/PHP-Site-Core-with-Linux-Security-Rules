<?php

class arrayMan{

	function arrayMan(){

	}

	public function array_sort($array, $on, $order='desc'){
		$new_array = array();
		$sortable_array = array();

		if (count($array) > 0) {
			foreach ($array as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $k2 => $v2) {
						if ($k2 == $on) {
							$sortable_array[$k] = $v2;
						}
					}
				} else {
					$sortable_array[$k] = $v;
				}
			}

			switch($order){
				case 'asc':
					asort($sortable_array);
					break;
				case 'desc':
					arsort($sortable_array);
					break;
			}

			foreach($sortable_array as $k => $v) {
				$new_array[] = $array[$k];
			}
		}
		return $new_array;
	}

	public function parentFinder($table, $id){
		global $system;


		$system->dbm->db->select("`parent`", "`$table`", "`id` = $id");
		$row = $system->dbm->db->fetch_array();

		if(isset($row['parent'])){
			$parents[] = $id;
			$parents[] = $this->parentFinder($table, $row['parent']);
		}else{
			return 0;
		}

		return $parents;
	}

//	public function serializer($array){
//
//		$return = array();
//		array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
//		return $return;
//	}

	function serializer($array){
		$out = array();

		foreach ( $array as $key => $val ){
			$temp = array_values($val);
			$out[] = $temp[0];
		}
		return $out;
	}

}

?>