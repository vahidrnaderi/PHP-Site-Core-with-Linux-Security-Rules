<?php
class session extends system{

	public $gid;
	public $host;
	public $ip;
	public $reffer;
	public $agent;
	public $table = "session";
	public $uid;
	public $op;
	public $mode;
	
	function session(){
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> session.php-> session()\n");

		$this->table = $this->tablePrefix . $this->table;
		$this->ip = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
		$this->host = mysql_real_escape_string(gethostbyaddr($_SERVER['REMOTE_ADDR']));
		$this->reffer = mysql_real_escape_string($_SERVER['HTTP_REFERER']);
		$this->agent = mysql_real_escape_string($_SERVER['HTTP_USER_AGENT']);
		$this->op = $_POST['op'];
		$this->mode = $_POST['mode'];
////echo "<br>SESSION_table-->".$this->table." time-->".time()."</br>";
////echo "<br>SESSION_ip-->".$this->ip."</br>";
////echo "<br>SESSION_host-->".$this->host."</br>";
////echo "<br>SESSION_reffer-->".$this->reffer."</br>";
////echo "<br>SESSION_agent-->".$this->agent."</br>";
////echo "<br>SESSION_op-->".$this->op."</br>";
////echo "<br>SESSION_mode-->".$this->mode." time-->".time()."</br>";			
	}
	public function start($uid, $gid){
		global $system, $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> session.php-> start($uid, $gid)\n");

		$timeStamp = time();
		$_SESSION['uid'] = intval($uid);
		$_SESSION['gid'] = $gid;
		$_SESSION['timeStamp'] = time();
		$_SESSION['periodTime'] = 60 * rand(10, 30);
////echo "<br>SESSION_start time-->".time()."</br>";
		$system->dbm->db->insert("`$this->table`", "`active`, `timeStamp`, `owner`, `group`, `or`, `ow`, `ox`, `gr`, `uid`, `agent`, `reffer`, `ip`, `host`, `op`, `mode`", "1, $timeStamp, 1, 5, 1, 1, 1, 1, $_SESSION[uid], '$this->agent', '$this->reffer', '$this->ip', '$this->host', '$this->op', '$this->mode'");
	}
	public function kill($uid){
		global $system, $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> session.php-> kill($uid)\n");
		
////echo "<br>session.php_line44   uid --> $_SESSION[uid] # $uid **** gid --> $_SESSION[gid] # $gid time-->".time()."</br> ";
////echo "<br>kill-->".$uid. " time-->".time()."</br>";
		$system->dbm->db->delete("`$this->table`", "`uid` = $uid");
//echo "session_unset-->".session_unset()."</br>";
//echo "session_destroy-->".session_destroy()."</br>";
		session_unset();
/////echo "<br>session.php_line50   uid --> $_SESSION[uid] # $uid **** gid --> $_SESSION[gid] # $gid time-->".time()."</br> ";
		session_destroy();
/////echo "<br>session.php_line52   uid --> $_SESSION[uid] # $uid **** gid --> $_SESSION[gid] # $gid time-->".time()."</br> ";
		$this->manager();
	}
	public function check($uid){
		global $system, $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> session.php-> check($uid)\n");
		
////echo "<br>SESSION_check time-->".time()."</br>";
		if($system->dbm->db->count_records("`$this->table`", "`uid` = $uid") > 0 && session_is_registered('uid'))
		return true;
		else
		return false;
	}
	public function read($field=null){
		global $system, $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> session.php-> read($field)\n");
		
////echo "<br>SESSION_read time-->".time()."</br>";
		$system->dbm->db->select("*", "`$this->table`", "`ip` = '$_SERVER[REMOTE_ADDR]'");
		$sessData = $system->dbm->db->fetch_array();

		if(!empty($field)){
			return $sessData[$field];
		}else{
			return array(
				'uid', $_SESSION['uid'],
				'gid', $_SESSION['gid'],
				'ip', $sessData['ip'],
				'host', $sessData['host'],
				'time', $sessData['time'],
				'reffer', $sessData['reffer']
			);
		}
	}
	public function update($uid, $gid){
		global $system, $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> session.php-> update($uid, $gid)\n");
		
//echo "<br>SESSION_update time-->".time()."</br>";
		$timeStamp = time();
		$_SESSION['uid'] = intval($uid);
		$_SESSION['gid'] = $gid;
		$_SESSION['timeStamp'] = time();
////echo "<br>SESSION_update table--> $this->table ** time-->".time()."### uid => $_SESSION[uid] * gid => $_SESSION[gid] </br>";		
		$system->dbm->db->update("`$this->table`", "`timeStamp` = '$timeStamp', `op` = '$this->op', `mode` = '$this->mode'", "`uid` = $uid");
	}

	public function delete(){
		global $settings, $system;
		system::debug($settings['debugFile'], "chrF", "	Function=> session.php-> delete()\n");
		
////echo "<br>SESSION_delete time-->".time()."</br>";
		$timeStamp = time();
		$offsetTime = $timeStamp - $settings['sessionTimeOut'];
//echo "<br>delete=> $this->table  $timeStamp < $offsetTime <br>";
//print_r ($settings);
		$system->dbm->db->delete($this->table, "`timeStamp` < $offsetTime");
	}

	public function manager($uid=null, $gid=null){
		global $system, $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> session.php-> manager($uid, $gid)\n");
		
////echo "<br>SESSION_manager_start time-->".time()."</br>";
		session_start();
//echo "<br>### 1 </br>";
//print_r($this->read());
//echo "<br>";
////		$this->start();
//echo "<br>### 2 </br>";
//print_r($this->read());
//echo "<br>";
		$this->delete();
//echo "<br>### 3 </br>";
//print_r($this->read());
//echo "<br>";
		if(empty($_SESSION['uid'])){
			$uid = empty($uid) ? 2 : $uid;
			$gid = empty($gid) ? 3 : $gid;
			$this->start($uid, $gid);
		}else{
			$uid = empty($uid) ? $_SESSION['uid'] : $uid;
			$gid = empty($gid) ? $_SESSION['gid'] : $gid;
			$this->update($uid, $gid);
		}
//echo "<br>### 4 </br>";
//print_r($this->read());
/////echo "<br>SESSION_manager_uid --> $uid **** gid --> $gid time-->".time()."</br> ";
	}
}
?>
