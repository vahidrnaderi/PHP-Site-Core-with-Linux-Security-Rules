<?php
class logger extends system{

	public $table;

	public function __construct() {
	    global $settings, $system;
		system::debug($settings['debugFile'], "chrF", "	Function=> logger.php-> __construct()\n");
		
		$this->table = $settings['logger'];
//		print "In BaseClass constructor\n";
	}

	function logger(){
	    global $settings, $system;
		system::debug($settings['debugFile'], "chrF", "	Function=> logger.php-> logger()\n");

		$this->table = $this->tablePrefix . $this->table;
	}

	public function logIt($type, $message){
		global $lang, $settings, $system, $sysVar;
		system::debug($settings['debugFile'], "chrF", "	Function=> logger.php-> logIt($type, $message)\n");
		
////	type= frm  -->  فیلدهای فرم
////	type= viw  -->  گردش در سایت
////	type= log  -->  login & log out
////	type= chn  -->  تغییرات
////	type= del  -->  delete
////	type= sch  -->  جستجو		
		
		
// echo "$message <br>";
		$clientInfo = $system->utility->browserDetector->whatBrowser();

		$op = mysqli_real_escape_string($system->dbm->db->dbhandler, $sysVar[op]);
		$mode = mysqli_real_escape_string($system->dbm->db->dbhandler, $sysVar[mode]);
		$agent = $clientInfo[browsertype];
		$version = $clientInfo[version];
		$ip = mysqli_real_escape_string($system->dbm->db->dbhandler, $_SERVER[REMOTE_ADDR]);
		$reffer = mysqli_real_escape_string($system->dbm->db->dbhandler, $_SERVER[HTTP_REFERER]);
		$host = mysqli_real_escape_string($system->dbm->db->dbhandler, gethostbyaddr($_SERVER[REMOTE_ADDR]));
		$os = $clientInfo[platform];

		if(!is_array($message)){
			$messageToDb = $message;
			$message = array($message);
		}else{
			foreach ($message as $mesg) {
				$messageToDb .= $mesg . "::";
			}
		}

		$timeStamp = time();
		//		$offsetTime = $timeStamp - 2592000;
		$title = mysqli_real_escape_string($system->dbm->db->dbhandler, $title);
		$messageToDb = mysqli_real_escape_string($system->dbm->db->dbhandler, $messageToDb);

		//		$system->dbm->db->delete("$this->table", "`timeStamp` < $offsetTime");
		$uid = !empty($_SESSION[uid]) ? $_SESSION[uid] : 2;
		$system->dbm->db->insert("`$this->table`", "`active`, `timeStamp`, `owner`, `group`, `or`, `ow`, `ox`, `gr`, `uid`, `agent`, `version`, `reffer`, `ip`, `host`, `os`, `op`, `mode`, `type`, `addressBar`, `message`", "1, $timeStamp, 1, 5, 1, 1, 1, 1	, $uid, '$agent', '$version', '$reffer', '$ip', '$host', '$os', '$op', '$mode', '$type', '$title', '$messageToDb'");

// echo "$messageToDb <br>";
// echo "$message <br>";
// print_r($message);

	}
}
?>