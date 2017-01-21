<?php
class c_menu extends m_menu{

	private $active = 1;
	
	function c_menu(){
		
	}
	
	public function c_addObject($name, $parent=null, $url=null, $icon=null){
		
		$this->m_addObject($name, $parent, $url, $icon);
	}
	
	public function c_editObject(){
		
		$this->m_editObject();
	}
	
	public function c_delObject(){
		
		$this->m_delObject();
	}
	
	public function c_listObject($filter=null){
		
		return $this->m_listObject($filter);
	}
	
	public function c_activateObject(){
		
		$this->m_activateObject();
	}
	
	public function c_deactivateObject(){
		
		$this->m_deactivateObject();
	}
}
?>