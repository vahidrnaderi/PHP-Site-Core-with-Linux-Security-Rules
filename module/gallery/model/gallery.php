<?php
class m_gallery extends masterModule{

	public function m_gallery(){

	}
	###########################
	# Category                #
	###########################
	// Add Category
	public function m_addCategory($name, $category, $description=null){
		global $system, $lang, $settings;

		$timeStamp = time();
		$system->dbm->db->insert("`$settings[galleryCategory]`", "`active`, `timeStamp`, `owner`, `group`, `or`, `ow`, `ox`, `gr`, `gw`, `gx`, `tr`, `tx`, `ur`, `ux`, `name`, `category`, `description`", "1, $timeStamp, $_SESSION[uid], 7, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '$name', $category, '$description'");
		$system->watchDog->exception("s", $lang['categoryAdd'], sprintf($lang['successfulDone'], $lang['categoryAdd'], $name));
	}
	// Edit Category
	public function m_editCategory($id, $name, $category=null, $description=null){
		global $system, $lang, $settings;

		$system->dbm->db->update("`$settings[galleryCategory]`", "`name` = '$name', `category` = '$category', `description` = '$description'", "`id` = $id");
		$system->watchDog->exception("s", $lang[editCategory], sprintf($lang[successfulDone], $lang[editCategory], $name));
	}
	// Del Category
	public function m_delCategory($id){
		global $system, $lang, $settings;

		$name = $system->dbm->db->informer("`$settings[galleryCategory]`", "`id` = $id", "name");
		$system->dbm->db->delete("`$settings[galleryCategory]`", "`id` = $id");
		$system->watchDog->exception("s", $lang[categoryDel], sprintf($lang[successfulDone], $lang[delete], $name));
	}
	// List Category
	public function m_listCategory($viewMode='list'){
		global $system,$lang, $settings;

		$system->xorg->pagination->paginateStart("gallery", "c_listCategory", "`active`, `id`, `name`, `category`", "`$settings[galleryCategory]`", "", "`timeStamp` DESC", "", "", "", "", 50, 7);

		$count = 1;
		while ($row = $system->dbm->db->fetch_array()){
			$entityList[$count][num] = $count;
			$entityList[$count][active] = $row[active];
			$entityList[$count][id] = $row[id];
			$entityList[$count][name] = $row[name];
			$entityList[$count][category] = $row[category];
			$entityList[$count][url] = $system->dbm->db->informer("`$settings[galleryObject]`", "`category` = $row[category]", 'url');
			$count++;
		}
		
		$system->xorg->smarty->assign("navigation", $system->xorg->pagination->renderFullNav());
		$system->xorg->smarty->assign("entityList", $entityList);
		return $system->xorg->smarty->display($settings['moduleAddress'] . "/" . $settings['moduleName'] . "/view/tpl/category/$viewMode" . $settings['ext4']);

	}
	// Activate Category
	public function m_activateCategory(){

	}
	// Deactivate Category
	public function m_deactivateCategory(){

	}
	###########################
	# Object (gallery)        #
	###########################
	// Add Object
	public function m_addObject($values, $show=false){
		global $system, $lang, $settings;

		$timeStamp = time();
		$values[category] = empty($values[category]) ? 0 : $values[category];
		
		$system->dbm->db->insert("`$settings[galleryObject]`", "`active`, `timeStamp`, `owner`, `group`, `or`, `ow`, `ox`, `gr`, `gw`, `gx`, `tr`, `tx`, `ur`, `ux`, `name`, `description`, `category`, `url`", "1, $timeStamp, $_SESSION[uid], 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '$values[name]', '$values[description]', $values[category], '$values[contentPath]'");
//		$id = $system->dbm->db->insert_id();

		if($show == true)
		$system->watchDog->exception("s", $lang[galleryAdd], sprintf($lang[successfulDone], $lang[galleryAdd], $values[name]), '', "setTimeout('$(\'#content\').farajax(\'loader\', \'/gallery/c_listObject\')', 3000);");
	}
	// Edit Object
	public function m_editObject($values){
		global $system, $lang, $settings;

		$timeStamp = time();
		$values[category] = empty($values[category]) ? 0 : $values[category];
				
		$system->dbm->db->update("`$settings[galleryObject]`", "`title` = '$values[title]', `brief` = '$brief', `description` = '$values[description]', `category` = $values[category], `startTime` = $values[startTime], `endTime` = $values[endTime], `resources` = '$values[resources]', `filePath` = '$values[filePath]', `contentType` = '$values[contentType]', `contentPath` = '$values[contentPath]'", "`id` = $values[id]");

		$system->watchDog->exception("s", $lang[galleryEdit], sprintf($lang[successfulDone], $lang[galleryEdit], $values[title]));
	}
	// Del Object
	public function m_delObject($id){
		global $system, $lang, $settings;

		$title = $system->dbm->db->informer("`$settings[galleryObject]`", "`id` = $id", "title");
		$system->dbm->db->delete("`$settings[galleryObject]`", "`id` = $id");
		$system->watchDog->exception("s", $lang[galleryDel], sprintf($lang[successfulDone], $lang[delete], $title));
	}
	// List Object
	public function m_listObject($viewMode, $filter=null){
		global $system,$lang, $settings;

		$time = time();

		if(strstr($filter, '_')){
			$filter = explode("_", $filter);
			$category = $filter[0];
			$filter = "category=$category";
			$filterFlag = 1;
			$filter = !empty($filter) ? $system->filterSplitter($filter) : null;
			$system->dbm->db->update("`$settings[galleryCategory]`", "`viewCount` = `viewCount`+1", "`id` = $category");
			$system->xorg->pagination->paginateStart("gallery", "c_showListObject", "`active`, `id`, `name`, `category`, `description`, `url`", "`$settings[galleryObject]`", "1 $filter");
		}else{
			$filter = !empty($filter) ? $system->filterSplitter($filter) : null;
			$system->xorg->pagination->paginateStart("gallery", "c_showListObject", "`active`, `id`, `name`, `url`, `category`", "`$settings[galleryObject]`", "1 $filter", "`timeStamp` DESC", "", "", "", "", 9, 7);
		}

		$count = 1;
		while ($row = $system->dbm->db->fetch_array()){
			$entityList[$count][num] = $count;
			$entityList[$count][active] = $row[active];
			$entityList[$count][id] = $row[id];
			$entityList[$count][name] = $row[name];
			$entityList[$count][url] = $row[url];
			$entityList[$count][category] = $system->dbm->db->informer("`$settings[galleryCategory]`", "`id` = $row[category]", 'name');
				
			if($filterFlag == 1){
				$entityList[$count][description] = $row[description];
			}
			$count++;
		}

		$system->xorg->smarty->assign("navigation", $system->xorg->pagination->renderFullNav());
		$system->xorg->smarty->assign("entityList", $entityList);
		return $system->xorg->smarty->display($settings['moduleAddress'] . "/gallery/view/tpl/object/$viewMode" . $settings['ext4']);
	}
	// Activate Object
	public function m_activateObject(){

	}
	// Deactivate Object
	public function m_deactivateObject(){

	}
	// Rss Feed	
	public function m_rssFeed($filter=null){
		global $system, $settings;
		
		$time = time();

		$system->dbm->db->select("`id`, `timeStamp`, `name`", "`$settings[galleryObject]`", "", "`timeStamp` DESC", "", "", "0,10");
		
		$count = 1;
		while ($row = $system->dbm->db->fetch_array()){
			$entityList[$count][num] = $count;
			$entityList[$count][id] = $row['id'];
			$entityList[$count][timeStamp] = $system->time->iCal->dator($row['timeStamp'], 2);
			$entityList[$count][title] = $row['title'];
			$entityList[$count][brief] = $row['brief'];
			$title = str_ireplace(' ', '-', trim($row['title']));
			$entityList[$count][link] = "/gallery/c_showListObject/$row[id]_$title";
			$count++;
		}
		
//		print_r($entityList);
		header('Content-Type: application/xml; charset=utf-8');
		die($system->rss->create($entityList));
	}

}
?>