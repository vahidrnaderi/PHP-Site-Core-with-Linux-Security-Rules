<?php

class trustUrl extends system{
	
	private $trustUrlTable = 'trust_url';
	
	function trustUrl(){
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> trustUrl.php-> trustUrl()\n");
		
		$this->trustUrlTable = $this->tablePrefix . $this->trustUrlTable;
	}
	
	public function trustUrlList(){
		global $system, $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> trustUrl.php-> trustUrlList()\n");
		
		$system->dbm->db->select("`url`", "`$this->trustUrlTable`");
		return $system->dbm->db->fetch_array();		
	}
	
}

?>