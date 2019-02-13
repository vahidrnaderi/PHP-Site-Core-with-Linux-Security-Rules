<?php 
class lang extends system{
	
	public $table = "lang";
	private $userSettings = "user_settings";
	
	function lang(){
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> lang.php-> lang()\n");
		
		$this->table = $this->tablePrefix . $this->table;
	}
	
	public function langMan(){
		global $system, $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> lang.php-> langMan()--SESSION['uid']==>$_SESSION[uid]\n");
		
		if(!empty($_SESSION['uid'])){
			if($system->dbm->db->informer("`$this->userSettings`", "`uid` = $_SESSION[uid] AND `name` = 'lang'", "value") != null){
				if($_SESSION['uid'] == 2 && $_SESSION['lang']){
					$langCode = $system->dbm->db->informer("`$this->userSettings`", "`uid` = $_SESSION[uid] AND `name` = 'lang'", "value");
				}elseif($_SESSION['uid'] != 2){
					$langCode = $system->dbm->db->informer("`$this->userSettings`", "`uid` = $_SESSION[uid] AND `name` = 'lang'", "value");
				}elseif($_SESSION['uid'] == 2 && empty($_SESSION['lang'])){
					$langCode = $settings['langs'];
				}
			}else{
				$langCode = $settings['langs'];
			}
		}else{
			$langCode = $settings['langs'];
		}
		$system->dbm->db->select("`code`, `translate`", "`$this->table`", "`langCode` = '$langCode'");
		while($row = $system->dbm->db->fetch_array()){
			$lang[$row['code']] = $row['translate'];
		}
		return $lang;
	}
	
}
?>