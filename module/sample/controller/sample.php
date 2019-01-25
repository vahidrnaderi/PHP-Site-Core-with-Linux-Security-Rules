<?php
class c_sample extends m_sample{

	public $active = 1;

	public function c_sample(){

	}
	###########################
	# Category                #
	###########################
	// Add Category
	public function c_addCategory($name, $category, $description=null){
		$category = empty($category) ? '0' : $category;
		$this->m_addCategory($name, $category, $description);
	}
	// Edit Category
	public function c_editCategory($id, $name, $category=null, $description=null){
		$this->m_editCategory($id, $name, $category, $description);
	}
	// Del Category
	public function c_delCategory($id){
		$this->m_delCategory($id);
	}
	// List Category
	public function c_listCategory(){
		return $this->m_listCategory();
	}
	// Activate Category
	public function c_activateCategory(){

	}
	// Deactivate Category
	public function c_deactivateCategory(){

	}
	###########################
	# Object (sample)        #
	###########################
	// Add Object
	public function c_addObject($values, $show=false){
		$this->m_addObject($values, $show);
	}
	// Edit Object
	public function c_editObject($values){
		$this->m_editObject($values);
	}
	// Del Object
	public function c_delObject($id, $name=null){
		$this->m_delObject($id, $name);
	}
	// List Object
	public function c_listObject($viewMode, $filter=null){
		return $this->m_listObject($viewMode, $filter);
	}
	// Activate Object
	public function c_activateObject(){

	}
	// Deactivate Object
	public function c_deactivateObject(){

	}
}
?>