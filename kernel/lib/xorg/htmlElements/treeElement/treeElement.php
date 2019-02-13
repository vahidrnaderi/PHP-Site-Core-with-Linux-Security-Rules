<?php 

class treeElement extends htmlElements{
	
	private $leaf;
	
	function treeElement(){
		global $system, $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> treeElement.php-> treeElement()\n");
		
	}
	
	public function treeTrace($table, $category=0, $level=0, $flag=0) {
		global $system, $lang, $settings;
		// به دلیل تکرار بالا کامنت شد
// 		system::debug($settings['debugFile'], "chrF", "	Function=> treeElement.php-> treeTrace($table, $category, $level, $flag)\n");
				
		$result = mysqli_query($system->dbm->db->dbhandler, "SELECT `id`, `category`, `name` FROM `$table` WHERE `category` = $category ORDER BY `name` ASC");
		if(!empty($result)){
			if($flag == 1)
			$this->leaf .= str_repeat("\t", $level+1) . "<ul>\n";
			while ($row = mysqli_fetch_array($result)){
			    $res = mysqli_query($system->dbm->db->dbhandler, "SELECT `id` FROM `$table` WHERE `category` = $row[id]");
				if(mysqli_num_rows($res) > 0){
					$this->leaf .= str_repeat("\t", $level+2) . "<li rel='$row[id]'>$row[name]\n";
					$this->treeTrace($table, $row['id'], $level+1, 1);
					$this->leaf .= str_repeat("\t", $level+2) . "</li>\n";
				}else{
					$this->leaf .= str_repeat("\t", $level+2) . "<li rel='$row[id]'>$row[name]</li>\n";
				}
			}
			if($flag == 1)
			$this->leaf .= str_repeat("\t", $level+1) . "</ul>\n";
		}
		return $this->leaf;	
	}
	
	public function tree($table, $category, $id, $class){
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> treeElement.php-> tree($table, $category, $id, $class)\n");
		
//		echo "table=".$table. "category=".$category. "id=".$id. "class=".$class;
		return "<ul id='$id' class='$class'>\n" . $this->treeTrace($table, $category) . "\n" . "</ul>";	
	}
	
	
}

?>