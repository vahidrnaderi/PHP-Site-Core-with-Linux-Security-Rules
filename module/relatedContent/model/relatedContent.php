<?php
class m_relatedContent extends masterModule{
	
	private $table = "seo";

	function m_relatedContent(){

	}

	###########################
	# Object (content)        #
	###########################
	// Relatd Content
	public function m_relatedURL($title){
		global $system, $settings;

		$title = mysql_real_escape_string($title);
		$system->dbm->db->select("`id`, `url`, `title`", "`$this->table`", "`domain` = '$settings[domain]' AND `title` <> '$title'");
		while($row = $system->dbm->db->fetch_array()){
			similar_text($title, $row[title], $percent);
			$percent = intval($percent);
			//			print "$title $percent% -> Id: $row[id] - Title: $row[title] - Url: $row[url]<br>";
			//			if($percent > 60){
			$sim[$percent][id] = $row[id];
			$row[url] = str_replace("op=", '', $row[url]);
			$row[url] = str_replace("&mode=", '/', $row[url]);
			$row[url] = str_replace("&filter=", '/', $row[url]);
			$row[url] = str_replace("&p=", '/', $row[url]);
			$row[url] = str_replace("&f=", '/', $row[url]);
			$sim[$percent][url] = $row[url];
			$sim[$percent][title] = $row[title];
			//			}
		}
		//		print_r($sim);
		if(count($sim) > 0){
			krsort($sim);
			$system->xorg->smarty->assign("relatedContent", array_slice($sim, 0, 5, true));
			return $system->xorg->smarty->fetch($settings['moduleAddress'] . "/relatedContent/view/tpl/object/relatedContent" . $settings['ext4']);
		}		
	}

	// Related keyword
	public function m_relatedKeyword(){
		global $system, $settings, $lang;
		
		$word = $system->utility->filter->queryString("word");		
		$system->xorg->pagination->paginateStart("relatedContent", "c_relatekeyword", "`active`, `id`, `url`, `title`, `text`", "`$this->table`", "`word1` = '$word' OR `word2` = '$word' OR `word3` = '$word' OR `word4` = '$word' OR `word5` = '$word' OR `word6` = '$word' OR `word7` = '$word' OR `word8` = '$word' OR `word9` = '$word' OR `word10` = '$word'", "`timeStamp` DESC", "", "", "", "", 20, 7);
		
		$count = 1;
		while ($row = $system->dbm->db->fetch_array()){
			$entityList[$count][num] = $count;
			$entityList[$count][active] = $row[active];
			$entityList[$count][id] = $row[id];
			$row[url] = str_replace("op=", '', $row[url]);
			$row[url] = str_replace("&mode=", '/', $row[url]);
			$row[url] = str_replace("&filter=", '/', $row[url]);
			$entityList[$count][url] = $row[url];
			$entityList[$count][title] = $row[title];
			$entityList[$count][text] = $row[text];
			$count++;
		}
		$system->xorg->smarty->assign("navigation", $system->xorg->pagination->renderFullNav());
		$system->xorg->smarty->assign("entityList", $entityList);
		$system->xorg->smarty->display($settings['moduleAddress'] . "/relatedContent/view/tpl/object/relatedKeyword" . $settings['ext4']);
	}
}
?>