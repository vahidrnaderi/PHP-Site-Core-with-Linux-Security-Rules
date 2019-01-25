<?php 

class imageGalleryElement extends htmlElements{
	
	function imageGalleryElement(){
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> imageGalleryElement.php-> imageGalleryElement()\n");
		
	}
	
	public function gallery($id){
		global $system, $lang, $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> imageGalleryElement.php-> gallery($id)\n");
		
		$system->dbm->db->select("`id`, `timeStamp`, `owner`, `name`, `category`, `url`", "`$settings[galleryObject]`", "`category` = $id");
	
		$count = 1;
		while ($row = $system->dbm->db->fetch_array()){
			$entityList[$count][num] = $count;
			$entityList[$count][active] = $row[active];
			$entityList[$count][id] = $row[id];
			$entityList[$count][timeStamp] = $row[timeStamp];
			$entityList[$count][name] = $row[name];
			$entityList[$count][categoryId] = $row[category];
			$entityList[$count][categoryName] = $system->dbm->db->informer("`$settings[galleryCategory]`", "`id` = $row[category]", 'name');
			$entityList[$count]['url'] = $row['url'];
			$count++;
		}
		
		$system->xorg->smarty->assign("entityList", $entityList);
		return $system->xorg->smarty->fetch($settings['libraryAddress'] . "/xorg/htmlElements/tpl/imageGallery" . $settings['ext4']);		
	}
	
}

?>