<?php

class trustUrl extends system{
	
	private $trustUrlTable = 'trust_url';
	
	function trustUrl(){
		
		$this->trustUrlTable = $this->tablePrefix . $this->trustUrlTable;
	}
	
	public function trustUrlList(){
		global $system;
		
		$system->dbm->db->select("`url`", "`$this->trustUrlTable`");
		return $system->dbm->db->fetch_array();		
	}
	
}

?>