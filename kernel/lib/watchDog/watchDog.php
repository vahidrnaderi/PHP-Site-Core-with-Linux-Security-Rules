<?php
class watchDog extends system{

	public $table;

	public function __construct() {
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> watchDog.php-> __construct()\n");
		
		$this->table = $settings['watchDog'];
//		print "In BaseClass constructor\n";
	}

	function watchDog(){
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> watchDog.php-> watchDog()\n");

		$this->table = $this->tablePrefix . $this->table;
	}

	public function exceptionType($type){
		global $lang, $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> watchDog.php-> exceptionType($type)\n");

		switch($type){
			case "i":
				$out[code] = "INO: #";
				$out[title] = $lang[information];
				$out[icon] = "information.png";
				$out[color] = "#6EB1EF";
				break;
			case "s":
				$out[code] = "SNO: #";
				$out[title] = $lang[success];
				$out[icon] = "success.png";
				$out[color] = "#26BF0F";
				break;
			case "w":
				$out[code] = "WNO: #";
				$out[title] = $lang[warning];
				$out[icon] = "warning.png";
				$out[color] = "#FC680C";
				break;
			case "e":
				$out[code] = "ENO: #";
				$out[title] = $lang[error];
				$out[icon] = "error.png";
				$out[color] = "#FC0000";
				break;
			default:
			case "u":
				$out[code] = "UNO: #";
				$out[title] = $lang[unknown];
				$out[icon] = "unknown.png";
				$out[color] = "#E0B3EF";
				break;
		}
		return $out;
	}

	public function exception($type, $title, $message, $button=null, $command=null){
		global $lang, $settings, $system, $sysVar;
		system::debug($settings['debugFile'], "chrF", "	Function=> watchDog.php-> exception($type, $title, $message, $button, $command)\n");
// echo "$message <br>";
		$clientInfo = $system->utility->browserDetector->whatBrowser();

		$op = mysql_real_escape_string($sysVar[op]);
		$mode = mysql_real_escape_string($sysVar[mode]);
		$agent = $clientInfo[browsertype];
		$version = $clientInfo[version];
		$ip = mysql_real_escape_string($_SERVER[REMOTE_ADDR]);
		$reffer = mysql_real_escape_string($_SERVER[HTTP_REFERER]);
		$host = mysql_real_escape_string(gethostbyaddr($_SERVER[REMOTE_ADDR]));
		$os = $clientInfo[platform];

		if(!is_array($message)){
			$messageToDb = $message;
			$message = array($message);
		}else{
			foreach ($message as $mesg) {
				$messageToDb .= $mesg . "::";
			}
		}

		if(!is_array($button)){
			$button = array($button);
		}

		$exceptionType = $this->exceptionType($type);

		$timeStamp = time();
		//		$offsetTime = $timeStamp - 2592000;
		$title = mysql_real_escape_string($title);
		$messageToDb = mysql_real_escape_string($messageToDb);

		//		$system->dbm->db->delete("$this->table", "`timeStamp` < $offsetTime");
		$uid = !empty($_SESSION[uid]) ? $_SESSION[uid] : 2;
		$system->dbm->db->insert("`$this->table`", "`active`, `timeStamp`, `owner`, `group`, `or`, `ow`, `ox`, `gr`, `uid`, `agent`, `version`, `reffer`, `ip`, `host`, `os`, `op`, `mode`, `type`, `title`, `description`", "1, $timeStamp, 1, 5, 1, 1, 1, 1	, $uid, '$agent', '$version', '$reffer', '$ip', '$host', '$os', '$op', '$mode', '$type', '$title', '$messageToDb'");

		$system->xorg->smarty->assign("type", $type);
		$system->xorg->smarty->assign("code", $exceptionType[code] . $system->dbm->db->insert_id());
		$system->xorg->smarty->assign("icon", $exceptionType[icon]);
		$system->xorg->smarty->assign("color", $exceptionType[color]);
		$system->xorg->smarty->assign("title", $title);
		$system->xorg->smarty->assign("message", $message);
		$system->xorg->smarty->assign("button", $button);
// echo "$messageToDb <br>";
// echo "$message <br>";
// print_r($message);
		$system->xorg->smarty->assign("command", $command);
		if($_SERVER["HTTP_X_REQUESTED_WITH"] == 'XMLHttpRequest'){
//			echo " 1 ";
			system::debug($settings['debugFile'], "chrE", "	Function=> watchDog.php-> die and exception($type, $title, $message, $button, $command)\n");
			die($system->xorg->smarty->fetch($settings['commonTpl'] . "watchDog" . $settings['ext4']));
//			echo " 2 ";
		}else{
//			echo " 3 ";
			system::debug($settings['debugFile'], "chrE", "	Function=> watchDog.php-> die and exception($type, $title, $message, $button, $command)\n");
			$system->xorg->smarty->display($settings['commonTpl'] . "watchDog" . $settings['ext4']);
			
////			$system->xorg->smarty->assign("watchDogTest", $system->xorg->smarty->fetch($settings[commonTpl] . "watchDog" . $settings[ext4]));
			
			
//			$system->xorg->prompt->promptShow('p', $lang[logout],"خروج با موفقیت انجام شد");
//			echo " 4 ";
		}

		

	}
}
?>