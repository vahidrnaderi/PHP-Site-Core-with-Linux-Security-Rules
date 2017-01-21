<?php
class m_menu extends masterModule{

	function m_menu(){
	
	}

	public function m_addObject($name, $parent=null, $url=null, $icon=null){
		global $settings, $lang, $system; 
		
		$timeStamp = time();
		$system->dbm->db->insert("`$settings[menu]`", "`active`, `timeStamp`, `owner`, `group`, `or`, `ow`, `ox`, `gr`, `gw`, `gx`, `tr`, `tx`, `ur`, `ux`, `name`, `category`, `url`, `icon`", "1, $timeStamp, $_SESSION[uid], 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '$name', '$parent', '$url', '$icon'");
		$system->watchDog->exception("s", $lang['menuAdd'], sprintf($lang['successfulDone'], $lang['menuAdd'], $name));
	}
	
	public function m_editObject(){
		
	}
	
	public function m_delObject(){
		
	}
	
	public function m_listObject($filter=null){
		global $settings, $system, $lang;
		
		$filter = !empty($filter) ? $system->filterSplitter($filter) : null;
		$time = time();
		$system->xorg->pagination->paginateStart("menu", "c_showList", "`active`, `id`, `name`, `category`, `url`, `icon`", "`$settings[menu]`", "1 $filter", "`timeStamp` DESC", "", "", "", "", 10, 7);

		$count = 1;
		while ($row = $system->dbm->db->fetch_array()){
			$entityList[$count]['num'] = $count;
			$entityList[$count]['active'] = $row['active'];
			$entityList[$count]['id'] = $row['id'];
			$entityList[$count]['name'] = $row['name'];
			$entityList[$count]['category'] = $system->dbm->db->informer("$settings[menu]", "`id` = $row[category]", "name");
			$entityList[$count]['url'] = $row['url'];
			$entityList[$count]['icon'] = str_replace("/images/", "/_thumbs/Images/", $row['icon']);
			$count++;
		}
		$system->xorg->smarty->assign("navigation", $system->xorg->pagination->renderFullNav());
		$system->xorg->smarty->assign("entityList", $entityList);
		return $system->xorg->smarty->display($settings['moduleAddress'] . "/" . $settings['moduleName'] . "/view/tpl/object/list" . $settings['ext4']);
	}
	
	public function m_activateObject(){
		
	}
	
	public function m_deactivateObject(){
		
	}

}
?>