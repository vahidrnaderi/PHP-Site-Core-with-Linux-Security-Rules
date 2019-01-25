<?php
class c_userMan extends m_userMan{

	public $active = 1;


	public function c_userMan(){

	}

	public function c_signUp($values){
		$this->m_signUp($values);
	}

	public function c_userAdd($userName, $password, $rePassword){
		global $system, $lang, $settings;

		if($system->dbm->db->count_records("`$this->userTable`", "`userName` = '$userName'") == 0){
			if($password == $rePassword){
				$this->m_userAdd($userName, $password);
			}else{
				$system->watchDog->exception('e', $lang[passwordsNotMatch], $lang[pleaseEnterMatchPasswords]);
			}
		}else{
			$system->watchDog->exception('e', $lang[userNameExist], $lang[plseaseSelectOtherUserName]);
		}

	}

	public function c_edit($values){
		
		$this->m_edit($values);
	}
	
	public function c_userDel($filter){
		$this->m_userDel($filter);
	}

	public function c_userList($filter=null, $viewMode='show'){
		if($_SESSION['uid'] == 1){
			return $this->m_userList($filter, $viewMode);
		}else{
			return $this->m_userList("id=$_SESSION[uid]", $viewMode);
		}
	}

	public function c_login($userName, $password){
		global $lang, $system;
// echo '<br>1';
		if ($password!=null){
			$passwordOrg=$password;
			$password = md5($password);
		}
		if($system->dbm->db->count_records("`$this->userTable`", "`active` = 1 AND `userName` = '$userName' AND (`password` = '$password' OR `password` = '$passwordOrg')") == 1 || $system->dbm->db->count_records("`$this->userTable`", "`active` = 1 AND `email` = '$userName' AND (`password` = '$password' OR `password` = '$passwordOrg')") == 1){
// echo "<br>2-> ".$this->userTable."<br>";				
			$result = mysqli_query($system->dbm->db->dbhandler, "SELECT `id`, `gid` FROM `$this->userTable` WHERE `userName` = '$userName' OR `email` = '$userName'");
			$profile = mysqli_fetch_array($result);
			require_once 'module/groupMan/model/groupMan.php';
			$gids = m_groupMan::m_userGroup($profile['id']);
			$profile['gid'] = $profile[gid] . $gids;
			$this->m_login($profile['id'], $profile['gid']);
		}else{
// echo "<br>3-> ".$this->userTable."<br>";
			$message = 'خطا در ورود با اطلاعات: userName='.$userName.' password='.$passwordOrg;
			$system->logger->logIt("log", $message);
			$system->watchDog->exception("e", $lang['error'], $lang['authenticationError']);
		}
	}

	public function c_loginContent(){
		return $this->m_loginContent();
	}
	
	public function c_loginContentTitle(){
		return $this->m_loginContentTitle();
	}

	public function c_menu(){
		$this->m_menu();
	}

	public function c_logout(){
		$this->m_logout();
	}

	public function c_setSettings($name, $value){
		$this->m_setSettings($name, $value);
	}
	
	public function c_remember($userMail,$mode){
		$this->m_remember($userMail,$mode);
	}
	
	public function c_emActivation($values){
		$this->m_emActivation($values);
	}
	
	public function c_resetPass($userName, $code, $newPassword){
		$this->m_resetPass($userName, $code, $newPassword);
	}

}
?>