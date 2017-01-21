<?php
class c_groupMan extends m_groupMan{

	public $active = 1;

	public function c_groupMan(){

	}
	###########################
	# Object (Group)          #
	###########################
	// Add Object
	public function c_addObject($values){
		global $system, $lang, $settings;

		if($system->dbm->db->count_records("`$settings[groupManObject]`", "`name` = '$values[name]'") == 0){
			$this->m_addObject($values, $show);
		}else{
			$system->watchDog->exception("w", $lang['warning'], $lang['groupExist']);
		}

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
	// List members
	public function c_listMembers($gid, $filter=null, $viewMode='list'){

		return $this->m_listMembers($gid, $filter, $viewMode);
	}
	// Set user to a group
	public function c_setMembers($gid, $uid){

		$this->m_setMembers($gid, $uid);
	}

}
?>