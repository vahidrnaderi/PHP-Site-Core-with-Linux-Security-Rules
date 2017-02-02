<?php
class c_logger extends m_logger{

	public $active = 1;


	function c_logger(){
		
	}

	// List Log
	public function c_list($filter = null){
		$this->m_list($filter);
	}
	
}
?>