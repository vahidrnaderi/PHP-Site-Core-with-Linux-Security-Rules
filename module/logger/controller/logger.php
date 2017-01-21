<?php
class c_watchDog extends m_logger{

	public $active = 1;


	function c_logger(){
		
	}

	// List Poll
	public function c_list($filter = null){
		$this->m_list($filter);
	}
	
}
?>