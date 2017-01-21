<?php
class m_cPanel extends masterModule{

	public $moduleName = "cPanel";

	function m_cPanel(){

	}

	// List cPanel
	public function m_list($filter = null){
		global $system, $lang, $settings;

		$system->dbm->db->select("`id`, `active`, `parent`, `op`, `mode`, `level`, `caption`, `viewIcon`, `viewInCP`", "`$settings[access]`", "`parent` = 0 AND `viewInCP` = 1 AND `active` = 1");

		$count = 1;
		while ($row = $system->dbm->db->fetch_array()){
			$entityList[$count][num] = $count;
			$entityList[$count][id] = $row[id];
			$entityList[$count][active] = $row[active];
			$entityList[$count]['parent'] = $row['parent'];
			$entityList[$count][op] = $row[op];
			$entityList[$count][mode] = $row[mode];
			$entityList[$count][level] = $row[level];
			$entityList[$count][caption] = $row[caption];
			$entityList[$count][viewIcon] = $row[viewIcon];
			$entityList[$count][viewInCP] = $row[viewInCP];

			$where = "`parent` = $row[id] AND `active` = 1 AND `viewInCP` = 1";
//			print "SELECT `id`, `active`, `parent`, `op`, `mode`, `level`, `caption`, `viewIcon`, `viewInCP` FROM `$settings[access]` WHERE `owner` = $_SESSION[uid] AND `or` = 1 AND $where OR `group` = $_SESSION[gid] AND `gr` = 1 AND $where OR `tr` = 1 AND $where";			
			$result = mysql_query("SELECT `id`, `active`, `parent`, `op`, `mode`, `level`, `caption`, `viewIcon`, `viewInCP` FROM `$settings[access]` WHERE `owner` = $_SESSION[uid] AND `or` = 1 AND $where OR `group` in ($_SESSION[gid]) AND `gr` = 1 AND $where OR `tr` = 1 AND $where");
			if($result){
				while($child = mysql_fetch_array($result)){
					if(!empty($child[caption])){
						$entityList[$count][child][$child[id]][id] = $child[id];
						$entityList[$count][child][$child[id]][active] = $child[active];
						$entityList[$count][child][$child[id]]['parent'] = $child['parent'];
						$entityList[$count][child][$child[id]][op] = $child[op];
						$entityList[$count][child][$child[id]][mode] = $child[mode];
						$entityList[$count][child][$child[id]][level] = $child[level];
						$entityList[$count][child][$child[id]][caption] = $child[caption];
						$entityList[$count][child][$child[id]][viewIcon] = $child[viewIcon];
						$entityList[$count][child][$child[id]][viewInCP] = $child[viewInCP];
					}
				}
			}
			$count++;
		}
//		print_r($entityList);
		$system->xorg->smarty->assign("entityList", $entityList);
		$system->xorg->smarty->display($settings['moduleAddress'] . "/" . $this->moduleName . "/view/tpl/list" . $settings['ext4']);
	}

	public function m_emptyCache($show=true, $filter=null){
		global $system, $lang;

		if(!empty($filter)){
			$system->utility->fileSystem->emptyDirectory($filter);
		}
		$system->utility->fileSystem->emptyDirectory("templates_c");
		$system->utility->fileSystem->emptyDirectory("tmp/cache");

		if($show)
		$system->watchDog->exception("s", $lang[successful], $lang[cacheRemovedSuccessful]);
	}

}
?>