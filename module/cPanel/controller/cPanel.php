<?php
class c_cPanel extends m_cPanel{

	public $active = 1;
	
	function c_cPanel(){
		
	}

	// List cPanel
	public function c_list($filter = null){
		
		$this->m_list($filter);
	}
	
	// Empty cache
	public function c_emptyCache($show=null, $filter=null){
		
 		$this->m_emptyCache(null, $filter);
	}

}
?>