<?php
class c_watchDog extends m_watchDog{

	public $active = 1;


	function c_watchDog(){
		
	}

	// List Poll
	public function c_list($filter = null){
		$this->m_list($filter);
	}
	
}
?>