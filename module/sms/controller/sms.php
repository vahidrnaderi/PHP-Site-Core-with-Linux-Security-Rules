<?php
class c_sms extends m_sms{

	public $active = 1;
	
	function c_sms(){
		
	}
	###########################
	# Category                #
	###########################
	// Add Category
	public function c_addCategory($name, $category){

		$this->m_addCategory($name, $category);
	}
	// Edit Category
	public function c_editCategory($values){
		
		$this->m_editCategory();
	}
	// Del Category
	public function c_delCategory($id){
		
		$this->m_delCategory($id);
	}
	// List Category
	public function c_listCategory($filter = null){
		
		$this->m_listCategory($filter);
	}
	// Activate Category
	public function c_activateCategory($id){
		
		$this->m_activateCategory($id);
	}
	// Deactive Category
	public function c_deactivateCategory($id){
		
		$this->m_deactivateCategory($id);
	}
	
	###########################
	# Object (SMS)            #
	###########################
	// Add Object
	public function c_addObject($to, $message, $category=null){

		$this->m_addObject($to, $message, $category);
	}
	// Edit Object
	public function c_editObject($values){
		
		$this->m_editObject();
	}
	// Del Object
	public function c_delObject($id){
		
		$this->m_delObject($id);
	}
	// List Object
	public function c_listObject($viewMode, $filter = null){
		
		$this->m_listObject($viewMode, $filter);
	}
	// Show Object
	public function c_showObject($id){
		$this->m_showObject($id);
	}
	// Activate Object
	public function c_activateObject($id){
		
		$this->m_activateObject($id);
	}
	// Deactive Object
	public function c_deactivateObject($id){
		
		$this->m_deactivateObject($id);
	}

}
?>