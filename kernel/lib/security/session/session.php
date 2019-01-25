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
	public $sessionId;
	
	function session(){
	    global $system, $settings;
	    system::debug($settings['debugFile'], "chrF", "	Function=> session.php-> session() -- system => ".print_r(get_class_methods($system),true)."\n");

	    $this->dbTemp = new mysqliDB($settings['host'], $settings['user'], $settings['pass'], $settings['name']);
		$this->table = $this->tablePrefix . $this->table;
		$this->ip = mysqli_real_escape_string($this->dbTemp->dbhandler, $_SERVER['REMOTE_ADDR']);
		$this->host = mysqli_real_escape_string($this->dbTemp->dbhandler, gethostbyaddr($_SERVER['REMOTE_ADDR']));
		$this->reffer = mysqli_real_escape_string($this->dbTemp->dbhandler, $_SERVER['HTTP_REFERER']);
		$this->agent = mysqli_real_escape_string($this->dbTemp->dbhandler, $_SERVER['HTTP_USER_AGENT']);
		$this->dbTemp->closeDB();
		$this->dbTemp=Null;
		
// 		$this->ip = $system->dbm->db->ip;
// 		$this->host = $system->dbm->db->host;
// 		$this->reffer = $system->dbm->db->reffer;
// 		$this->agent = $system->dbm->db->agent;
		
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
	public function start($uid, $gid, $sessionId){
		global $system, $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> session.php-> start($uid, $gid, $sessionId)\n");

		$timeStamp = time();
		$_SESSION['uid'] = intval($uid);
		$_SESSION['gid'] = $gid;
		$_SESSION['timeStamp'] = time();
		$_SESSION['periodTime'] = 60 * rand(10, 30);
		$_SESSION['sessionId'] = $sessionId;
		$_SESSION['test'] = "start";
////echo "<br>SESSION_start time-->".time()."</br>";
		$system->dbm->db->insert("`$this->table`", "`active`, `timeStamp`, `owner`, `group`, `or`, `ow`, `ox`, `gr`, `uid`, `agent`, `reffer`, `ip`, `host`, `op`, `mode`, `sessionId`", "1, $timeStamp, 1, $_SESSION[gid], 1, 1, 1, 1, $_SESSION[uid], '$this->agent', '$this->reffer', '$this->ip', '$this->host', '$this->op', '$this->mode', '$sessionId'");
	}
	public function kill($uid){
		global $system, $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> session.php-> kill($uid)\n");
		
////echo "<br>session.php_line44   uid --> $_SESSION[uid] # $uid **** gid --> $_SESSION[gid] # $gid time-->".time()."</br> ";
////echo "<br>kill-->".$uid. " time-->".time()."</br>";
		$system->dbm->db->delete("`$this->table`", "`uid` = $uid");
		$system->dbm->db->delete("`$this->table`", "`sessionId` = '$_SESSION[sessionId]'");
//echo "session_unset-->".session_unset()."</br>";
//echo "session_destroy-->".session_destroy()."</br>";
		session_unset();
/////echo "<br>session.php_line50   uid --> $_SESSION[uid] # $uid **** gid --> $_SESSION[gid] # $gid time-->".time()."</br> ";
		session_destroy();
/////echo "<br>session.php_line52   uid --> $_SESSION[uid] # $uid **** gid --> $_SESSION[gid] # $gid time-->".time()."</br> ";
//print_r($_SESSION);
//		system::debug($settings['debugFile'], "chrF", "	Function=> session.php-> kill($uid)".print_r($_SESSION,true)."\n");

//		session_write_close();
//		session_regenerate_id(true);
		
		$this->sessionId='';
		$_SESSION = array();
 		$this->manager(null,null,true);
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
	public function update($uid, $gid, $sessionId){
		global $system, $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> session.php-> update($uid, $gid, $sessionId)\n");
		
//echo "<br>SESSION_update time-->".time()."</br>";
		$timeStamp = time();
		$_SESSION['uid'] = intval($uid);
		$_SESSION['gid'] = $gid;
		$_SESSION['timeStamp'] = time();
////echo "<br>SESSION_update table--> $this->table ** time-->".time()."### uid => $_SESSION[uid] * gid => $_SESSION[gid] </br>";		
		$system->dbm->db->update("`$this->table`", "`timeStamp` = '$timeStamp', `op` = '$this->op', `mode` = '$this->mode', `uid` = '$_SESSION[uid]', `group` = '$_SESSION[gid]'", "`sessionId` = '$sessionId'");
	}

	public function delete(){
		global $settings, $system;
		$suid=$_SESSION['uid'];
		system::debug($settings['debugFile'], "chrF", "	Function=> session.php-> delete()-- SESSION['uid']-->$suid & uid=$uid\n");
		
////echo "<br>SESSION_delete time-->".time()."</br>";
		$timeStamp = time();
		$offsetTime = $timeStamp - $settings['sessionTimeOut'];
//echo "<br>delete=> $this->table  $timeStamp < $offsetTime <br>";
//print_r ($settings);
		$system->dbm->db->delete($this->table, "`timeStamp` < $offsetTime");
	}

	public function manager($uid=null, $gid=null, $newLogin=null){
		global $system, $settings;
		$suid=$_SESSION['uid'];
		
////echo "<br>SESSION_manager_start time-->".time()."</br>";
		session_start();
		if ($newLogin) session_regenerate_id(session_id());
		
		system::debug($settings['debugFile'], "chrF", "	Function=> session.php-> manager($uid,$gid,$newLogin) & SESSION[]-->".print_r($_SESSION,true)."  suid-->$suid \n");
// 		echo "</br>3->session_id()-->".session_id();
// 		echo "</br>3->SESSION['uid']-->".$_SESSION['uid']."</br>";
// 		print_r ($_SESSION);
//echo "<br>### 1 </br>";
//print_r($this->read());
//echo "<br>";
////		$this->start();
//echo "<br>### 2 </br>";
//print_r($this->read());
//echo "<br>";
		$this->delete();
// 		echo "</br>4->session_id()-->".session_id();
// 		echo "</br>4->SESSION['uid']-->".$_SESSION['uid'];
//echo "<br>### 3 </br>";
//print_r($this->read());
//echo "<br>";
		$this->sessionId = session_id();
// 		echo "sessionId count-->".$system->dbm->db->count_records("`$this->table`", "`sessionId` = '$this->sessionId'");
		
		if(empty($_SESSION['uid']) || ($system->dbm->db->informer("`user`", "`id` = $suid", "id") == null)){
//  			echo "</br>empty session--$this->sessionId</br>";
			$uid = empty($uid) ? 2 : $uid;
			$gid = empty($gid) ? 3 : $gid;
			if ($system->dbm->db->count_records("`$this->table`", "`sessionId` = '$this->sessionId'")==0){
//  				echo "</br>start";
				$this->start($uid, $gid, $this->sessionId);
			}			
		}else{
//  			echo "</br>not empty session--$this->sessionId</br>";
			$uid = empty($uid) ? $_SESSION['uid'] : $uid;
			$gid = empty($gid) ? $_SESSION['gid'] : $gid;
			if ($system->dbm->db->count_records("`$this->table`", "`sessionId` = '$this->sessionId'")==1){
//  				echo "</br>update";
				$this->update($uid, $gid, $this->sessionId);
			}elseif ($system->dbm->db->count_records("`$this->table`", "`sessionId` = '$this->sessionId'")==0){
//  				echo "</br>start";
				$this->start($uid, $gid, $this->sessionId);
			}	
		}
//echo "<br>### 4 </br>";
//print_r($this->read());
/////echo "<br>SESSION_manager_uid --> $uid **** gid --> $gid time-->".time()."</br> ";
	}
}
?>
