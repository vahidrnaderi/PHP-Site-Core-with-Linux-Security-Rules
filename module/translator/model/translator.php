<?php
class m_translator extends masterModule{

	public $moduleName = "translator";
	public $langTable = "lang";
	public $langCodeTable = "lang_code";

	function m_translator(){

		$this->shopProfile = $this->tablePrefix . $this->langTable;
	}

	public function m_listPhrase($filter=null){
		global $system, $settings;

		$filter = !empty($filter) ? $system->filterSplitter($filter) : null;
		$system->xorg->pagination->paginateStart("translator", "c_listPhrase", "`base`.`id`, `base`.`active`, `base`.`timeStamp`, `$this->langCodeTable`.`code` as `langCode`, `base`.`code`, `translate`", "`$this->langTable` as `base`, `$this->langCodeTable`", "`base`.`langCode` = `$this->langCodeTable`.`id` $filter", "`base`.`code` ASC", "", "", "", "", 20, 7);

		$count = 1;
		while ($row = $system->dbm->db->fetch_array()){
			$entityList[$count][num] = $count;
			$entityList[$count][active] = $row[active];
			$entityList[$count][id] = $row[id];
			$entityList[$count][timeStamp] = $system->time->iCal->dator($row[timeStamp]);
			$entityList[$count][langCode] = $row[langCode];
			$entityList[$count][code] = $row[code];
			$entityList[$count][translate] = $row[translate];
			$count++;
		}
		$system->xorg->smarty->assign("navigation", $system->xorg->pagination->renderFullNav());
		$system->xorg->smarty->assign("entityList", $entityList);
		$system->xorg->smarty->display($settings['moduleAddress'] . "/" . $this->moduleName . "/view/tpl/listPhrase" . $settings['ext4']);

	}

	public function m_editPhrase($langId, $translate){
		global $system, $lang, $settings;

		$system->dbm->db->update("`$this->langTable`", "`translate` = '$translate'", "`id` = $langId");
		$system->watchDog->exception("s", $lang[editPhrase], sprintf($lang[successfulDone], $lang[editPhrase], $translate));
	}

	public function m_addPhrase($langCode, $code, $translate){
		global $system, $lang, $settings;

		if($system->dbm->db->count_records("`$this->langTable`", "`langCode` = '$langCode' AND `code` = '$code'") == 0){
			$timeStamp = time();
			$system->dbm->db->insert("`$this->langTable`", "`active`, `timeStamp`, `owner`, `group`, `or`, `ow`, `ox`, `gr`, `gw`, `gx`, `tr`, `ur`, `langCode`, `code`, `translate`", "1, $timeStamp, 1, 4, 1, 1, 1, 1, 1, 1, 1, 1, '$langCode', '$code', '$translate'");
			$system->watchDog->exception("s", $lang[addPhrase], sprintf($lang[successfulDone], $lang[addPhrase], $translate));
		}else{
			$system->watchDog->exception("w", $lang[addPhrase], $lang[thisPhraseIsExist]);
		}
	}

	public function m_delPhrase($id, $phrase){
		global $system, $lang, $settings;
		
		$system->dbm->db->delete("`$this->langTable`", "`id` = $id");
		$system->watchDog->exception("s", $lang[delPhrase], sprintf($lang[successfulDone], $lang[delPhrase], $phrase));
	}

}
?>