<?php
class c_keywords extends m_keywords{

	public $active = 1;
	
	function c_keywords(){
		
	}
		
	###########################
	# Object (words)          #
	###########################
	// List Object
	public function c_listObject($viewMode, $filter = null){
		
		$this->m_listObject($viewMode, $filter);
	}

}
?>