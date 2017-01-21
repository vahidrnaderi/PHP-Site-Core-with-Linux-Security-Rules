<?php
class m_logger extends masterModule{

	public $moduleName = "logger";
	public $logger = "logger";

	function m_logger(){

	}

	// List Poll
	public function m_list($filter = null){
		global $system, $lang, $settings;

		$filter = !empty($filter) ? $system->filterSplitter($filter) : null;
		$system->xorg->pagination->paginateStart("logger", "c_list", "`timeStamp`, `owner`, `id`, `active`, `agent`, `version`, `ip`, `reffer`, `host`, `os`, `op`, `mode`, `addressBar`, `message`", "`$this->logger`", "1 $filter", "`timeStamp` DESC", "", "", "", "", 100, 7);

		$count = 1;
		while ($row = $system->dbm->db->fetch_array()){

			$entityList[$count][num] = $count;
			$entityList[$count][active] = $row[active];
			$entityList[$count][id] = $row[id];
			$entityList[$count][user] = $row[owner];
			$entityList[$count][agent] = $row[agent];
			$entityList[$count][version] = $row[version];
			$entityList[$count][ip] = $row[ip];
			$entityList[$count][reffer] = $row[reffer];
			$entityList[$count][host] = $row[host];
			$entityList[$count][os] = $row[os];
			$entityList[$count][op] = $row[op];
			$entityList[$count][mode] = $row[mode];
			$entityList[$count][addressBar] = $row[addressBar];
			$entityList[$count][message] = $row[message];
			$entityList[$count][timeStamp] =  $system->time->iCal->dator($row['timeStamp'], 2);

			$count++;
		}
		$system->xorg->smarty->assign("navigation", $system->xorg->pagination->renderFullNav());
		$system->xorg->smarty->assign("chart", $system->xorg->htmlElements->chartElement->bar("بازار بزرگ ایده آل", "ایده آل"));
		$system->xorg->smarty->assign("entityList", $entityList);
		$system->xorg->smarty->display($settings['moduleAddress'] . "/" . $this->moduleName . "/view/tpl/list" . $settings['ext4']);
	}

}
?>