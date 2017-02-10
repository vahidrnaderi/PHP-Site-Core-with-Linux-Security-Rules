<?php
class logger extends system{

	public $table;

	public function __construct() {
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> logger.php-> __construct()\n");
		
		$this->table = $settings['logger'];
//		print "In BaseClass constructor\n";
	}

	function logger(){
		global $settings;
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

		$timeStamp = time();
		//		$offsetTime = $timeStamp - 2592000;
		$title = mysql_real_escape_string($title);
		$messageToDb = mysql_real_escape_string($messageToDb);

		//		$system->dbm->db->delete("$this->table", "`timeStamp` < $offsetTime");
		$uid = !empty($_SESSION[uid]) ? $_SESSION[uid] : 2;
		$system->dbm->db->insert("`$this->table`", "`active`, `timeStamp`, `owner`, `group`, `or`, `ow`, `ox`, `gr`, `uid`, `agent`, `version`, `reffer`, `ip`, `host`, `os`, `op`, `mode`, `type`, `addressBar`, `message`", "1, $timeStamp, 1, 5, 1, 1, 1, 1	, $uid, '$agent', '$version', '$reffer', '$ip', '$host', '$os', '$op', '$mode', '$type', '$title', '$messageToDb'");

// echo "$messageToDb <br>";
// echo "$message <br>";
// print_r($message);

	}
}
?>