<?php
class m_directory extends masterModule{

	public function m_directory(){
		global $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> directory Module >> model/directory.php-> m_directory()\n");

	}
	###########################
	# Category                #
	###########################
	// Add Category
	public function m_addCategory($name, $category, $description=null){
		global $system, $lang, $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> directory Module >> model/directory.php-> m_addCategory($name, $category, $description)\n");

		$timeStamp = time();
		$system->dbm->db->insert("`$settings[directoryCategory]`", "`active`, `timeStamp`, `owner`, `group`, `or`, `ow`, `ox`, `gr`, `gw`, `gx`, `tr`, `tx`, `ur`, `ux`, `name`, `category`, `description`", "1, $timeStamp, $_SESSION[uid], 7, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '$name', $category, '$description'");
		$system->watchDog->exception("s", $lang['categoryAdd'], sprintf($lang['successfulDone'], $lang['categoryAdd'], $name));
	}
	// Edit Category
	public function m_editCategory($id, $name, $category=null, $description=null){
		global $system, $lang, $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> directory Module >> model/directory.php-> m_editCategory($id, $name, $category, $description)\n");

		$system->dbm->db->update("`$settings[directoryCategory]`", "`name` = '$name', `category` = '$category', `description` = '$description'", "`id` = $id");
		$system->watchDog->exception("s", $lang[editCategory], sprintf($lang[successfulDone], $lang[editCategory], $name));
	}
	// Del Category
	public function m_delCategory($id){
		global $system, $lang, $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> directory Module >> model/directory.php-> m_delCategory($id)\n");

		$name = $system->dbm->db->informer("`$settings[directoryCategory]`", "`id` = $id", "name");
		$system->dbm->db->delete("`$settings[directoryCategory]`", "`id` = $id");
		$system->watchDog->exception("s", $lang[categoryDel], sprintf($lang[successfulDone], $lang[delete], $name));
	}
	// List Category
	public function m_listCategory(){
		global $system,$lang, $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> directory Module >> model/directory.php-> m_listCategory()\n");

		$system->xorg->pagination->paginateStart("directory", "c_listCategory", "`active`, `id`, `name`, `category`, `description`", "`$settings[directoryCategory]`", "", "`timeStamp` DESC", "", "", "", "", 50, 7);

		$count = 1;
		while ($row = $system->dbm->db->fetch_array()){
			$entityList[$count][num] = $count;
			$entityList[$count][active] = $row[active];
			$entityList[$count][id] = $row[id];
			$entityList[$count][name] = $row[name];
			$entityList[$count][category] = $system->dbm->db->informer("`$settings[directoryCategory]`", "`id` = $row[category]", 'name');
			//			$entityList[$count][description] = $row[description];
			$count++;
		}
		$system->xorg->smarty->assign("navigation", $system->xorg->pagination->renderFullNav());
		$system->xorg->smarty->assign("entityList", $entityList);
		//		print_r($entityList);
		unset($entityList);
		$entityList = array();
		//		print "List";
		//		print_r($entityList);
		return $system->xorg->smarty->display($settings['moduleAddress'] . "/" . $settings['moduleName'] . "/view/tpl/category/list" . $settings['ext4']);

	}
	// Activate Category
	public function m_activateCategory(){
		global $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> directory Module >> model/directory.php-> m_activateCategory()\n");

	}
	// Deactivate Category
	public function m_deactivateCategory(){
		global $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> directory Module >> model/directory.php-> m_deactivateCategory()\n");

	}
	###########################
	# Object (directory)        #
	###########################
	// Add Object
	public function m_addObject($values, $show=false){
		global $system, $lang, $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> directory Module >> model/directory.php-> m_addObject($values, $show)\n");

		$timeStamp = time();
		$values[category] = empty($values[category]) ? 0 : $values[category];
		
		$system->dbm->db->insert("`$settings[directoryObject]`", "`active`, `timeStamp`, `owner`, `group`, `or`, `ow`, `ox`, `gr`, `gw`, `gx`, `tr`, `tx`, `ur`, `ux`, `name`, `description`, `category`, `url`", "1, $timeStamp, $_SESSION[uid], 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '$values[name]', '$values[description]', $values[category], '$values[contentPath]'");
//		$id = $system->dbm->db->insert_id();

		if($show == true)
		$system->watchDog->exception("s", $lang[directoryAdd], sprintf($lang[successfulDone], $lang[directoryAdd], $values[name]), '', "setTimeout('$(\'#content\').farajax(\'loader\', \'/directory/c_listObject\')', 3000);");
	}
	// Edit Object
	public function m_editObject($values){
		global $system, $lang, $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> directory Module >> model/directory.php-> m_editObject($values)\n");

		$timeStamp = time();
		$values[category] = empty($values[category]) ? 0 : $values[category];
				
		$system->dbm->db->update("`$settings[directoryObject]`", "`title` = '$values[title]', `brief` = '$brief', `description` = '$values[description]', `category` = $values[category], `startTime` = $values[startTime], `endTime` = $values[endTime], `resources` = '$values[resources]', `filePath` = '$values[filePath]', `contentType` = '$values[contentType]', `contentPath` = '$values[contentPath]'", "`id` = $values[id]");

		$system->watchDog->exception("s", $lang[directoryEdit], sprintf($lang[successfulDone], $lang[directoryEdit], $values[title]));
	}
	// Del Object
	public function m_delObject($id){
		global $system, $lang, $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> directory Module >> model/directory.php-> m_delObject($id)\n");

		$title = $system->dbm->db->informer("`$settings[directoryObject]`", "`id` = $id", "title");
		$system->dbm->db->delete("`$settings[directoryObject]`", "`id` = $id");
		$system->watchDog->exception("s", $lang[directoryDel], sprintf($lang[successfulDone], $lang[delete], $title));
	}
	// List Object
	public function m_listObject($viewMode, $filter=null){
		global $system,$lang, $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> directory Module >> model/directory.php-> m_listObject($viewMode, $filter)\n");

		$time = time();

		if(strstr($filter, '_')){
			$filter = explode("_", $filter);
			$id = $filter[0];
			$filter = "id=$id";
			$filterFlag = 1;
			$filter = !empty($filter) ? $system->filterSplitter($filter) : null;
			$system->dbm->db->update("`$settings[directoryObject]`", "`viewCount` = `viewCount`+1", "`id` = $id");
			$system->xorg->pagination->paginateStart("directory", "c_showListObject", "`active`, `id`, `name`, `category`, `description`, `url`", "`$settings[directoryObject]`", "");
		}else{
			$filter = !empty($filter) ? $system->filterSplitter($filter) : null;
			$system->xorg->pagination->paginateStart("directory", "c_showListObject", "`active`, `id`, `name`, `url`, `category`", "`$settings[directoryObject]`", "", "`timeStamp` DESC", "", "", "", "", 9, 7);
		}

		$count = 1;
		while ($row = $system->dbm->db->fetch_array()){
			$entityList[$count][num] = $count;
			$entityList[$count][active] = $row[active];
			$entityList[$count][id] = $row[id];
			$entityList[$count][name] = $row[name];
			$entityList[$count][url] = $row[url];
			$entityList[$count][category] = $system->dbm->db->informer("`$settings[directoryCategory]`", "`id` = $row[category]", 'name');
				
			if($filterFlag == 1){
				$entityList[$count][description] = $row[description];
			}
			$count++;
		}

		$system->xorg->smarty->assign("navigation", $system->xorg->pagination->renderFullNav());
		$system->xorg->smarty->assign("entityList", $entityList);
		return $system->xorg->smarty->display($settings['moduleAddress'] . "/directory/view/tpl/object/$viewMode" . $settings['ext4']);
	}
	// Activate Object
	public function m_activateObject(){
		global $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> directory Module >> model/directory.php-> m_activateObject()\n");

	}
	// Deactivate Object
	public function m_deactivateObject(){
		global $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> directory Module >> model/directory.php-> m_deactivateObject()\n");

	}
	// Rss Feed	
	public function m_rssFeed($filter=null){
		global $system, $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> directory Module >> model/directory.php-> m_rssFeed($filter)\n");
		
		$time = time();

		$system->dbm->db->select("`id`, `timeStamp`, `name`", "`$settings[directoryObject]`", "", "`timeStamp` DESC", "", "", "0,10");
		
		$count = 1;
		while ($row = $system->dbm->db->fetch_array()){
			$entityList[$count][num] = $count;
			$entityList[$count][id] = $row['id'];
			$entityList[$count][timeStamp] = $system->time->iCal->dator($row['timeStamp'], 2);
			$entityList[$count][title] = $row['title'];
			$entityList[$count][brief] = $row['brief'];
			$title = str_ireplace(' ', '-', trim($row['title']));
			$entityList[$count][link] = "/directory/c_showListObject/$row[id]_$title";
			$count++;
		}
		
//		print_r($entityList);
		header('Content-Type: application/xml; charset=utf-8');
		die($system->rss->create($entityList));
	}

}
?>