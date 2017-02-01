<?php 
class lang extends system{
	
	public $table = "lang";
	private $userSettings = "user_settings";
	
	function lang(){
		
		$this->table = $this->tablePrefix . $this->table;
	}
	
	public function langMan(){
		global $system, $settings;
		
		if(!empty($_SESSION['uid'])){
			if($system->dbm->db->informer("`$this->userSettings`", "`uid` = $_SESSION[uid] AND `name` = 'lang'", "value") != null){
				if($_SESSION['uid'] == 2 && $_SESSION['lang']){
					$langCode = $system->dbm->db->informer("`$this->userSettings`", "`uid` = $_SESSION[uid] AND `name` = 'lang'", "value");
				}elseif($_SESSION['uid'] != 2){
					$langCode = $system->dbm->db->informer("`$this->userSettings`", "`uid` = $_SESSION[uid] AND `name` = 'lang'", "value");
				}elseif($_SESSION['uid'] == 2 && empty($_SESSION['lang'])){
					$langCode = $settings['lang'];
				}
			}else{
				$langCode = $settings['lang'];
			}
		}else{
			$langCode = $settings['lang'];
		}
		$system->dbm->db->select("`code`, `translate`", "`$this->table`", "`langCode` = '$langCode'");
		while($row = $system->dbm->db->fetch_array()){
			$lang[$row['code']] = $row['translate'];
		}
		return $lang;
	}
	
}
?>