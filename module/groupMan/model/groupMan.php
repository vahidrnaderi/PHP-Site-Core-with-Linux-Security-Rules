<?php
class m_groupMan extends masterModule{

	public $tree;

	public function m_groupMan(){
		global $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> groupMan Module >> model/groupMan.php-> m_groupMan()\n");

	}
	###########################
	# Object (Group)          #
	###########################
	// Add Object
	public function m_addObject($values){
		global $system, $lang, $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> groupMan Module >> model/groupMan.php-> m_addObject($values)\n");

		$timeStamp = time();
		$system->dbm->db->insert("`$settings[groupManObject]`", "`active`, `timeStamp`, `owner`, `group`, `or`, `ow`, `ox`, `gr`, `gw`, `gx`, `tr`, `tx`, `ur`, `ux`, `name`, `description`", "1, $timeStamp, $_SESSION[uid], 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '$values[name]', '$values[description]'");

		$system->watchDog->exception("s", $lang[groupAdd], sprintf($lang[successfulDone], $lang[groupAdd], $values[name]));
	}
	// Edit Object
	public function m_editObject($values){
		global $system, $lang, $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> groupMan Module >> model/groupMan.php-> m_editObject($values)\n");

		$timeStamp = time();
		$system->dbm->db->update("`$settings[groupManObject]`", "`name` = '$values[name]', `description` = '$values[description]'", "`id` = $values[id]");

		$system->watchDog->exception("s", $lang[groupManEdit], sprintf($lang[successfulDone], $lang[groupEdit], $values[name]));
	}
	// Del Object
	public function m_delObject($id){
		global $system, $lang, $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> groupMan Module >> model/groupMan.php-> m_delObject($id)\n");

		$name = $system->dbm->db->informer("`$settings[groupManObject]`", "`id` = $id", "name");
		$system->dbm->db->delete("`$settings[groupManObject]`", "`id` = $id");
		$system->watchDog->exception("s", $lang[groupDel], sprintf($lang[successfulDone], $lang[delete], $name));
	}
	// List Object
	public function m_listObject($viewMode, $filter=null){
		global $system,$lang, $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> groupMan Module >> model/groupMan.php-> m_listObject($viewMode, $filter)\n");

		$filter = !empty($filter) ? $system->filterSplitter($filter) : null;
		$time = time();
		$system->xorg->pagination->paginateStart("groupMan", "c_listObject", "`active`, `id`, `name`, `description`", "`$settings[groupManObject]`", "", "`timeStamp` DESC", "", "", "", "", 10, 7);

		$count = 1;
		while ($row = $system->dbm->db->fetch_array()){
			$entityList[$count]['num'] = $count;
			$entityList[$count]['active'] = $row['active'];
			$entityList[$count]['id'] = $row['id'];
			$entityList[$count]['name'] = $row['name'];
			$entityList[$count]['description'] = $row['description'];
			$count++;
		}
		$system->xorg->smarty->assign("navigation", $system->xorg->pagination->renderFullNav());
		$system->xorg->smarty->assign("entityList", $entityList);
		return $system->xorg->smarty->display($settings['moduleAddress'] . "/" . $settings['moduleName'] . "/view/tpl/object/$viewMode" . $settings['ext4']);

	}
	// Activate Object
	public function m_activateObject(){
		global $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> groupMan Module >> model/groupMan.php-> m_activateObject()\n");

	}
	// Deactivate Object
	public function m_deactivateObject(){
		global $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> groupMan Module >> model/groupMan.php-> m_deactivateObject()\n");

	}
	// List group member
	public function m_listMembers($gid, $filter=null, $viewMode='list'){
		global $lang, $settings, $system;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> groupMan Module >> model/groupMan.php-> m_listMembers($gid, $filter, $viewMode)\n");

//		$filter = $system->filterSplitter($filter);

		$system->xorg->pagination->paginateStart("groupMan", "v_listMembers", "`id`, `active`, `timeStamp`, `userName`, `email`, `firstName`, `lastName`, `userPic`", "`$settings[userTable]`", "", "`timeStamp` DESC", "", "", "", "", 10, 7);
		$count = 1;
		while ($row = $system->dbm->db->fetch_array()){
			$entityList[$count][count] = $count;
			$entityList[$count][id] = $row[id];
			$entityList[$count][active] = $row[active] == 1 ? $lang[active] : $lang[notActive];
			$entityList[$count][timeStamp] = $system->time->iCal->dator($row[timeStamp]);
			$entityList[$count][userName] = $row[userName];
			$entityList[$count][email] = $row[email];
			$entityList[$count][firstName] = $row[firstName];
			$entityList[$count][lastName] = $row[lastName];
			$entityList[$count][isMember] = $system->dbm->db->count_records("`$settings[groupManMembers]`", "`gid` = $gid AND `uid` = $row[id]");
			$entityList[$count][gid] = $gid;
			$count++;
		}

		$system->xorg->smarty->assign("navigation", $system->xorg->pagination->renderFullNav());
		$system->xorg->smarty->assign("entityList", $entityList);
		return $system->xorg->smarty->display($settings['moduleAddress'] . "/" . $settings['moduleName'] . "/view/tpl/object/listMembers" . $settings['ext4']);

	}
	// Set a user to a group
	public function m_setMembers($gid, $uid){
		global $system, $settings, $lang;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> groupMan Module >> model/groupMan.php-> m_setMembers($gid, $uid)\n");

		$timeStamp = time();
		if($system->dbm->db->count_records("`$settings[groupManMembers]`", "`gid` = $gid AND `uid` = $uid") > 0){
			$system->dbm->db->delete("`$settings[groupManMembers]`", "`gid` = $gid AND `uid` = $uid");
			$system->dbm->db->update("`user`","`gid` = 2","`id` = $uid");
			$system->watchDog->exception('s', $lang[delFromGroup], sprintf($lang[successfulDone], $lang[delFromGroup], $system->dbm->db->informer("`$settings[groupManObject]`", "`id` = $gid", "name")));
		}else{
			$system->dbm->db->insert("`$settings[groupManMembers]`", "`active`, `timeStamp`, `owner`, `group`, `or`, `ow`, `ox`, `gr`, `gx`, `gid`, `uid`", "1, $timeStamp, 1, 1, 1, 1, 1, 1, 1, $gid, $uid");
			$system->dbm->db->update("`user`","`gid` = $gid","`id` = $uid");
			$system->watchDog->exception('s', $lang[addToGroup], sprintf($lang[successfulDone], $lang[addToGroup], $system->dbm->db->informer("`$settings[groupManObject]`", "`id` = $gid", "name")));
		}
	}
	
	public function m_userGroups($uid){
		global $system, $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> groupMan Module >> model/groupMan.php-> m_userGroups($uid)\n");

		$result = mysqli_query($system->dbm->db->dbhandler, "SELECT `gid` FROM `$settings[groupManMembers]` WHERE `uid` = $uid");
		while($row = mysqli_fetch_array($result)){
			$gids = $gids . ',' . $row['gid']; 
		}
		return $gids;
	}
	
	public function m_userGroup($uid){
		global $system, $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> groupMan Module >> model/groupMan.php-> m_userGroup($uid)\n");
	
		$gid = $system->dbm->db->informer("`$settings[groupManMembers]`", "`uid` = $uid", "`gid`");
		
		return $gid;
	}

}
?>