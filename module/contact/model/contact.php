<?php
class m_contact{

	private $moduleName = "contact";
	public $contactMessageTable = "contact_message";

	function m_contact(){
		global $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> contact Module >> model/contact.php-> m_contact()\n");

		$this->userTable = $this->tablePrefix . $this->userTable;

	}

	public function m_sendMessage($userName, $email, $subject, $message, $carbonCopy){
		global $system, $lang, $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> contact Module >> model/contact.php-> m_sendMessage($userName, $email, $subject, $message, $carbonCopy)\n");

		$timeStamp = time();
		$system->dbm->db->insert("`$this->contactMessageTable`", "`active`, `timeStamp`, `owner`, `group`, `or`, `ow`, `ox`, `gr`, `sender`, `email`, `subject`, `message`", "1, $timeStamp, 1, 8, 1, 1, 1, 1, '$userName', '$email', '$subject', '$message'");
		if($carbonCopy == 1){
			// CC for user
			mail($email, $subject, $message, "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\nFrom: $settings[infoMail] <$settings[infoMail]>\nX-Mailer: PHP/" . phpversion());
		}

		// To admin mail
		mail($settings['infoMail'], $subject, $message, "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\nFrom: $email <$email>\nX-Mailer: PHP/" . phpversion());

		$system->watchDog->exception("s", $lang[sendMessage], sprintf($lang[successfulDone], $lang[sendMessage], $subject));
	}
	// List Messages
	public function m_listMessage($filter = null){
		global $system, $lang, $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> contact Module >> model/contact.php-> m_listMessage($filter)\n");

		$filter = !empty($filter) ? $system->filterSplitter($filter) : null;
		$system->xorg->pagination->paginateStart("contact", "c_listMessage", "`id`, `timeStamp`, `active`, `sender`, `subject`, `email`, `message`", "`$this->contactMessageTable`", "1 $filter", "`timeStamp` DESC", "", "", "", "", 10, 7);

		$count = 1;
		while ($row = $system->dbm->db->fetch_array()){

			$entityList[$count][num] = $count;
			$entityList[$count][active] = $row[active];
			$entityList[$count][id] = $row[id];
			$entityList[$count][timeStamp] = $system->time->iCal->dator($row[timeStamp]);
			$entityList[$count][sender] = $row[sender];
			$entityList[$count][subject] = $row[subject];
			$entityList[$count][email] = $row[email];
			$entityList[$count][message] = $row[message];

			$count++;
		}
		$system->xorg->smarty->assign("navigation", $system->xorg->pagination->renderFullNav());
		$system->xorg->smarty->assign("entityList", $entityList);
		$system->xorg->smarty->display($settings['moduleAddress'] . "/" . $this->moduleName . "/view/tpl/list" . $settings['ext4']);
	}
	// Show Messages
	public function m_showMessage($id){
		global $system, $settings, $lang;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> contact Module >> model/contact.php-> m_showMessage($id)\n");

//		$filter = !empty($filter) ? $system->filterSplitter($filter) : null;
//		$system->xorg->pagination->paginateStart("article", "c_list", "`id`, `active`, `name`", "`$this->articleEntity`", "1 $filter", "`timeStamp` DESC", "", "", "", "", 10, 7);
		$system->dbm->db->select("*", "`$this->contactMessageTable`", "`id` = $id");

		$row = $system->dbm->db->fetch_array();

		$system->xorg->smarty->assign("message", $row);
		$system->xorg->smarty->display($settings['moduleAddress'] . "/" . $this->moduleName . "/view/tpl/show" . $settings['ext4']);
	}
}
?>