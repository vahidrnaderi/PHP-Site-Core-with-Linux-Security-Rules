<?php
class m_userMan extends masterModule{
/*
	private $moduleName = "userMan";
	public  $userTable = "user";
	public  $userSettings = "user_settings";
//	public  $userSettings = "userSettings";
	public  $groupTable = "group";
//	public  $groupMembersTable = "groupMembers";
	public  $groupMembersTable = "group_man_members";
	private $accessTable = "access";
	private $genderTable = "gender";
	private $countriesTable = "countries";
	private $stateTable = "state";
	private $cityTable = "city";
	private $religionTable = "religion";
	private $statusTable = "status";
	private $levelTable = "level";
	private $pattern;
*/
	private $moduleName = "userMan";
	public  $userTable = "user";
	public  $userSettings = 'user_settings';
	public  $groupTable = 'group_man_object';
	public  $groupMembersTable = 'group_man_members';
	private $accessTable = 'access';
	private $genderTable = 'gender';
	private $countriesTable = 'countries';
	private $stateTable = 'state';
	private $cityTable = 'city';
	private $religionTable = 'religion';
	private $statusTable = 'status';
	private $levelTable = 'level';
	private $pattern;
	
	public function __construct() {
		global $settings;
/*		
//		$this->moduleName = "userMan";
//		$this->userTable = $settings['user'];
//		$this->userTable = "user";
		$this->userSettings = $settings[userSettings];
		$this->groupTable = $settings[groupManObject];
//		$this->groupTable = "group";
		$this->groupMembersTable = $settings[groupManMembers];
		$this->accessTable = $settings[access];
		$this->genderTable = $settings[gender];
		$this->countriesTable = $settings[countries];
		$this->stateTable = $settings[state];
		$this->cityTable = $settings[city];
		$this->religionTable = $settings[religion];
		$this->statusTable = $settings[status];
		$this->levelTable = $settings[level];
		$this->pattern;
*/
// 		echo "<br><font style='color:white;'>";
// 		print_r($this);
// 		echo "<\font>";
	}

	function m_userMan(){

		$this->userTable = $this->tablePrefix . $this->userTable;
		$this->accessTable = $this->tablePrefix . $this->accessTable;
		$this->pattern = "dropDown"; //slide

	}

	public function m_signUp($values){
		global $system, $settings, $lang;

		$timeStamp = time();
//		if(strstr($values['userName'], '@')){
//			$filter = "`email` = '$values[userName]'";
//			$signUpFlag = 'email';
//		}else{
//			$filter = "`userName` = '$values[userName]'";
//			$signUpFlag = 'userName';
//		}
		if($system->dbm->db->count_records("`$this->userTable`", "`email` = '$values[email]'") == 0){
			//			if($values['password'] == $values['retypePassword']){
////1			if(strlen($values['password']) >= $settings['minCharPassword']){
				if($_POST['securityQuestion'] === $system->dbm->db->informer("`$settings[faqObject]`", "`id` = $_POST[securityId]", "answer")){
					$password =null; ////1 md5($values['password']);

					$system->dbm->db->insert("`$this->userTable`", "`active`, `timeStamp`, `owner`, `group`, `or`, `ow`, `ox`, `gr`, `gx`, `tr`, `tx`, `gid`, `password`, `email`, `userType`", "1, '$timeStamp', 1, 1, 1, 1, 1, 1, 1, 1, 1, 2, '$password', '$values[email]', '0'");  ////1 $values[userType]

					$id = $system->dbm->db->insert_id();

					@mkdir("home/$id/images", 0777, true);
					@mkdir("home/$id/_thumbs", 0777, true);
					@mkdir("home/$id/files", 0777, true);

					@fopen("home/$id/images/index.html", w);
					@fopen("home/$id/_thumbs/index.html", w);
					@fopen("home/$id/files/index.html", w);

					$this->m_login($id, 2);
					
					$message  = "$values[userName]<br>";
					$message .= "$values[email]<br>";
					$message .= "$values[mobile]<br>";
					$message .= "$values[address]<br>";	
					$message .= "$values[pNo]<br>";
					$message .= "$values[floor]<br>";
					$message .= "$values[unit]<br>";
					
					mail("$settings[infoMail], ", $lang['signUp'], $message, "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\nFrom: $values[email] <$values[email]>\nX-Mailer: PHP/" . phpversion());

					$system->watchDog->exception('s', $lang['add'], sprintf($lang['successfulDone'], $lang['userAdd'], $values['email']) . $lang['pleaseWait'], null, "setTimeout('location.href=\'/\';', 5000);");
				}else{
					$system->watchDog->exception("e", $lang['error'], $lang['securityAnswerIsIncorrect']);
				}
////1			}else{
////1				$system->watchDog->exception("e", $lang['error'], sprintf($lang['passwordIsTooShortMinIs'], $settings['minCharPassword']));
////1			}
			//			}else{
			//				$system->watchDog->exception("e", $lang['error'], $lang['passwordsNotMatch']);
			//			}
		}else{
			$system->watchDog->exception("e", $lang['error'], $lang['userNameExist']);
		}
	}

	public function m_userAdd($userName, $password){
		global $system, $lang, $settings;

		$timeStamp = time();
		$password = md5($password);
		$system->dbm->db->insert("`$this->userTable`", "`active`, `timeStamp`, `owner`, `group`, `or`, `ow`, `ox`, `gr`, `gx`, `tr`, `tx`, `userName`, `password`, `gid`", "1, $timeStamp, 1, 1, 1, 1, 1, 1, 1, 1, 1, '$userName', '$password', 2");

		$id = $system->dbm->db->insert_id();
		mkdir("home/$id/images", 0777, true);
		mkdir("home/$id/_thumbs", 0777, true);

		$system->watchDog->exception('s', $lang['add'], sprintf($lang['successfulDone'], $lang['userAdd'], $userName));
	}

	public function m_edit($values){
		global $system, $settings, $lang;
//print_r($values);
		$religion = (!empty($values['religion'])) ? ",`religion` = $values[religion]" : null;
		$financialStatus = (!empty($values['financialStatus'])) ? ",`financialStatus` = $values[financialStatus]" : null;
		$level = (!empty($values[level])) ? ",`level` = $values[level]" : null;

		$deActiveMobile = ($system->dbm->db->informer("`$this->userTable`", "`id` = $_SESSION[uid]", "mobile") != $values['mobile']) ? ",`showMobile` = 0 " : null;
//		echo ("<br>*** " . $system->dbm->db->informer("`$settings[userTable]`", "`id` = $_SESSION[uid]", "mobile"))."<br> != <br>**** " .$values['mobile'] ;
		if($values['selectUserType'] == 1){
//echo "yes";
			$system->dbm->db->update("`$this->userTable`", "`userType`='$values[userType]'", "`id` = $values[id]");
		}elseif($values['userType'] == 2){  // company Type
//			echo 2;
			$system->dbm->db->update("`$this->userTable`", "`userPic` = '$values[userPic]', `userName` = '$values[userName]', `coName` = '$values[coName]', `certificates` = '$values[certificates]', `coCapital` = '$values[coCapital]', `coCEO` = '$values[coCEO]', `coType` = '$values[coType]', `workField` = '$values[workField]', `regTime` = '$values[regTime]', `workDetails` = '$values[workDetails]', `nationalCode` = '$values[nationalCode]', `state` = $values[state], `issued` = $values[city], `region` = $values[region], `district` = '$values[district]', `zipcode` = '$values[zipcode]', `mobile` = '$values[mobile]' $deActiveMobile, `phone` = '$values[phone]', `address` = '$values[address]',`pNo`='$values[pNo]', `floor`='$values[floor]', `unit`='$values[unit]'", "`id` = $values[id]");			
		}elseif($values['userType'] == 1){   // Personal Type
//			echo 1;
			$system->dbm->db->update("`$this->userTable`", "`userPic` = '$values[userPic]',	`userName` = '$values[userName]',`firstName` = '$values[firstName]', `lastName` = '$values[lastName]', `fatherName` = '$values[fatherName]', `gender` = $values[gender], `idNumber` = '$values[idNumber]', `personalCode` = '$values[personalCode]', `state` = $values[state], `issued` = $values[city], `major` = '$values[major]', `proficiency` = '$values[proficiency]', `region` = $values[region],`district` = $values[district], `zipcode` = '$values[zipcode]', `nationality` = '$values[nationality]' $religion $financialStatus $level, `mobile` = '$values[mobile]' $deActiveMobile,`phone` = '$values[phone]',`address` = '$values[address]',`workField` = '$values[workField]',`workDetails` = '$values[workDetails]',`pNo`='$values[pNo]',`floor`='$values[floor]',`unit`='$values[unit]'", "`id` = $values[id]");
		}
		
		$system->watchDog->exception('s', $lang[userEdit], sprintf($lang[successfulDone], $lang[userEdit], $system->dbm->db->informer("`$this->userTable`", "`id` = $values[id]", "email")), '', "$('#content').farajax('loader', 'userMan/v_profile');$('#modalWindow').faraModal('closeModal', 'modalWindow');");
	}

	public function m_userDel($filter){
		global $lang, $settings, $system;

		$filter = $system->filterSplitter($filter);

		$userName = $system->dbm->db->informer("$this->userTable", "1 $filter", "userName");
		$system->dbm->db->delete("`$this->userTable`", "1 $filter");

		$system->watchDog->exception("s", $lang[delete], sprintf($lang[successfulDone], $lang[delete], $userName));
	}

	public function m_userList($filter=null, $viewMode='show'){
		global $lang, $settings, $system;
//echo $filter;
		$filter = $system->filterSplitter($filter);

		$system->xorg->pagination->paginateStart("userMan", "c_userList", "`id`, `active`, `timeStamp`, `userType`, `firstName`, `coName`, `coType`, `coCEO`, `coCapital`, `certificates`, `workField`, `regTime`, `workDetails`, `lastName`, `fatherName`, `userName`, `userPic`, `gender`, `idNumber`, `personalCode`, `nationalCode`, `state`, `issued`, `region`, `district`, `zipcode`, `address`, `pNo`, `floor`, `unit`, `nationality`, `religion`, `financialStatus`, `level`, `mobile`, `showMobile`, `phone`, `email`, `showEmail`, `major`, `proficiency`", "`$this->userTable`", "1 $filter", "`timeStamp` ASC");

		$count = 1;
		while ($row = $system->dbm->db->fetch_array()){
			$userList[$count][count] = $count;
			$userList[$count][id] = $row['id'];
			$userList[$count][active] = $row['active'] == 1 ? $lang['active'] : $lang['notActive'];
			$userList[$count][timeStamp] = $system->time->iCal->dator($row['timeStamp']);
			$userList[$count][userType] = $row['userType'];
			$userList[$count][firstName] = $row['firstName'];
			$userList[$count][coName] = $row['coName'];
			$userList[$count][coType] = $row['coType'];
			$userList[$count][coCapital] = $row['coCapital'];
			$userList[$count][coCEO] = $row['coCEO'];
			$userList[$count][certificates] = $row['certificates'];
			$userList[$count][workField] = $row['workField'];
			$userList[$count][regTime] = $row['regTime'];
			$userList[$count][workDetails] = $row['workDetails'];
			$userList[$count][lastName] = $row['lastName'];
			$userList[$count][fatherName] = $row['fatherName'];
			$userList[$count][userName] = $row['userName'];
			$imgTmp = explode(",", $row['userPic']);
			$row[userPic] = $imgTmp[0];
			$userList[$count][userPic] = (empty($row['userPic']) ? "theme/$settings[theme]/img/defaultUserPic.jpg" : "$row[userPic]");
			$userList[$count][genderId] = $row['gender'];
			$userList[$count][gender] = $system->dbm->db->informer("`$this->genderTable`", "`id` = $row[gender]", 'name');
			$userList[$count][idNumber] = $row['idNumber'];
			$userList[$count][nationalCode] = $row['nationalCode'];
			$userList[$count][personalCode] = $row['personalCode'];
			$userList[$count][nationalityId] = $row['nationality'];
			$userList[$count][nationality] = $system->dbm->db->informer("`$this->countriesTable`", "`id` = $row[nationality]", 'name');
			$userList[$count][issuedId] = $row['issued'];
			$userList[$count][issued] = $system->dbm->db->informer("`$this->cityTable`", "`id` = $row[issued]", 'name');
			$userList[$count][stateId] = $row['state'];
			$userList[$count][state] = $system->dbm->db->informer("`$this->stateTable`","`id` =  $row[state]", 'name');
			$userList[$count][regionId] = $row['region'];
			$userList[$count][region] = $system->dbm->db->informer("`$settings[region]`","`id` =  $row[region]", 'name');
			$userList[$count][districtId] = $row['district'];
			$userList[$count][district] = $system->dbm->db->informer("`$settings[district]`","`id` =  $row[district]", 'name');
			$userList[$count][address] = $row['address'];
			$userList[$count][pNo] = $row['pNo'];
			$userList[$count][floor] = $row['floor'];
			$userList[$count][unit] = $row['unit'];
			$userList[$count][zipcode] = $row['zipcode'];
			$userList[$count][religionId] = $row['religion'];
			$userList[$count][religion] = $system->dbm->db->informer("`$this->religionTable`", "`id` = $row[religion]", 'name');
			$userList[$count][financialStatusId] = $row['financialStatus'];
			$userList[$count][financialStatus] = $system->dbm->db->informer("`$this->statusTable`", "`id` = $row[financialStatus]", 'name');
			$userList[$count][levelId] = $row['level'];
			$userList[$count][level] = $system->dbm->db->informer("`$this->levelTable`", "`id` = $row[level]", 'name');
			$userList[$count][mobile] = $row['mobile'];
			$userList[$count][showMobile] = $row['showMobile'];
			$userList[$count][phone] = $row['phone'];
			$userList[$count][email] = $row['email'];
			$userList[$count][major] = $row['major'];
			$userList[$count][proficiency] = $row['proficiency'];
			$userList[$count][showEmail] = $row['showEmail'];
			$count++;
		}
//print_r($userList);
		$system->xorg->smarty->assign("navigation", $system->xorg->pagination->renderFullNav());
		$system->xorg->smarty->assign("userList", $userList);
		if($count > 2){
			$system->xorg->smarty->display($settings['moduleAddress'] . "/" . $this->moduleName . "/view/tpl/userList" . $settings['ext4']);
		}else{
			if($viewMode == 'show'){
				$system->xorg->smarty->display($settings['moduleAddress'] . "/" . $this->moduleName . "/view/tpl/profile" . $settings['ext4']);
			}elseif($viewMode == 'edit'){
				$system->xorg->smarty->display($settings['moduleAddress'] . "/" . $this->moduleName . "/view/tpl/edit" . $settings['ext4']);
			}
		}

	}

	public function m_login($uid, $gid){
		global $lang, $system;

		$system->security->session->manager($uid, $gid);
		$system->xorg->smarty->assign("uid", $uid);
		$system->xorg->smarty->assign("gid", $gid);

		$this->m_loginContent();
////		$this->m_loginContentTitle();
	}
	
	function m_loginContent(){
		global $lang, $system, $settings;
		
		if($_SESSION['uid'] && $_SESSION['uid'] != 2){
//			echo "uid=" . $_SESSION['uid'] . " t1";
			$res = mysql_query("SELECT `userType`, `gender`, `firstName`, `lastName`, `userName`, `userPic`, `coName`, `email` FROM `$this->userTable` WHERE `id` = $_SESSION[uid]");
			$profile = mysql_fetch_array($res);
			
			if ($profile['userType']==0){
				$genderType = $lang['user'];
//echo "<br> userType = Unknown";				
			}else{
				if ($profile['userType']==1){
//echo "<br> userType = Person";
					if($profile['gender'] == 1){
//echo "<br> gender = mr";
						$genderType = $lang['mr'];
					}elseif ($profile['gender'] == 2){
//echo "<br> gender = ms";
						$genderType = $lang['ms'];
					}else{
//echo "<br> gender = user";
						$genderType = $lang['user'];
					}
				}else{
//echo "<br> userType = company";
					$genderType = $lang['company'];
				}
			}
			$showName = !empty($profile['firstName']) || !empty($profile['lastName']) ? $profile['firstName'] . " " . $profile['lastName'] : $profile['email'];
			$companyName = $profile['coName'];
			$welcomeName= $profile['userType']==2 ? $companyName : $showName;
			$imgTmp = explode(",", $profile['userPic']);
			$profile[userPic] = $imgTmp[0];
			$system->xorg->smarty->assign("userPic", empty($profile['userPic']) ? "theme/$settings[theme]/img/defaultUserPic.jpg" : $profile['userPic']);
			$system->xorg->smarty->assign("loginFlag", true);

			$system->xorg->smarty->assign("welcomeMessage", sprintf($lang['welcomeMessage'], $genderType, "<b>$welcomeName</b>"));
			if(file_exists($settings['commonTpl'] . $this->moduleName . '/welcomeMessage' . $settings['ext4'])){
//				echo " t11";
				return $system->xorg->smarty->display($settings['commonTpl'] . $this->moduleName . "/welcomeMessage" . $settings['ext4']);
			}else{
//				echo " t12";				
				return $system->xorg->smarty->display($settings['moduleAddress'] . "/" . $this->moduleName . "/view/tpl/welcomeMessage" . $settings['ext4']);
			}
		}else{
//			echo "uid=" . $_SESSION['uid'] . " t2";
			$system->xorg->smarty->assign("welcomeMessage", sprintf($lang['welcomeMessage'], "", $lang['guest']));
			if(file_exists($settings['commonTpl'] . $this->moduleName . '/login' . $settings['ext4'])){
//				echo " t21";
				return $system->xorg->smarty->display($settings['commonTpl'] . $this->moduleName . '/login' . $settings['ext4']);
			}else{
//				echo " t22";				
				return $system->xorg->smarty->display($settings['moduleAddress'] . "/" . $this->moduleName . "/view/tpl/login" . $settings['ext4']);
			}
		}
	}

	function m_loginContentTitle(){
		global $lang, $system, $settings;
		
		if($_SESSION['uid'] && $_SESSION['uid'] != 2){
//echo "<br> uid=" . $_SESSION['uid'] . " t1";
			$res = mysql_query("SELECT `userType`, `gender`, `firstName`, `lastName`, `userName`, `userPic`, `coName`, `email` FROM `$this->userTable` WHERE `id` = $_SESSION[uid]");
			$profile = mysql_fetch_array($res);

			$showName = !empty($profile['firstName']) || !empty($profile['lastName']) ? $profile['firstName'] . " " . $profile['lastName'] : $profile['email'];
			$companyName = $profile['coName'];
			$welcomeName= $profile['userType']==2 ? $companyName : $showName;
			$imgTmp = explode(",", $profile['userPic']);
			$profile[userPic] = $imgTmp[0];
			$system->xorg->smarty->assign("userPic", empty($profile['userPic']) ? "theme/$settings[theme]/img/defaultUserPic.jpg" : $profile['userPic']);
			$system->xorg->smarty->assign("loginFlag", true);

			$system->xorg->smarty->assign("welcomeMessageTitle", "<b>$welcomeName</b>");
			if(file_exists($settings['customiseTpl'] . '/welcomeMessageTitle' . $settings['ext4'])){
//echo "<br> t11";
				return $system->xorg->smarty->display($settings['customiseTpl'] . "/welcomeMessageTitle" . $settings['ext4']);
			}else{
//echo "<br> t12";				
				return $system->xorg->smarty->display($settings['moduleAddress'] . "/" . $this->moduleName . "/view/tpl/welcomeMessageTitle" . $settings['ext4']);
			}
		}else{
//echo "<br>uid=" . $_SESSION['uid'] . " t2";
			$system->xorg->smarty->assign("welcomeMessageTitle", sprintf($lang['welcomeMessage'], "", $lang['user'] . " " . $lang['guest']));
			if(file_exists($settings['customiseTpl'] . "/welcomeMessageTitle" . $settings['ext4'])){
				return $system->xorg->smarty->display($settings['customiseTpl'] . "/welcomeMessageTitle" . $settings['ext4']);
			}
		}
	}
	
	public function m_menu($pattern="dropDown"){
		global $system, $lang, $settings;

		return $system->xorg->smarty->display($settings['commonTpl'] . 'menu' . $settings['ext4']);
	}

	public function m_logout(){
		global $system, $lang, $settings;
//echo "<br> m_logout --> $_SESSION[uid] <br>";
//echo "<br>userMan.php_line316   uid --> $_SESSION[uid] # $uid **** gid --> $_SESSION[gid] # $gid time-->".time()."</br> ";
		$uid = $_SESSION['uid'];
		$gid = $_SESSION['gid'];
//echo "<br>userMan.php_line319   uid --> $_SESSION[uid] # $uid **** gid --> $_SESSION[gid] # $gid time-->".time()."</br> ";
		$system->security->session->kill($_SESSION['uid']);
//echo "<br>userMan.php_line321   uid --> $_SESSION[uid] # $uid **** gid --> $_SESSION[gid] # $gid time-->".time()."</br> ";
		$system->watchDog->exception("s", $lang['logout'], sprintf($lang['successfulDone'], $lang['logout'], $system->dbm->db->informer("`$this->userTable`", "`id` = $uid", 'email')));
//echo "<br>userMan.php_line323   uid --> $_SESSION[uid] # $uid **** gid --> $_SESSION[gid] # $gid time-->".time()."</br> ";
	}

	public function m_setSettings($name, $value){
		global $system, $lang, $settings;

		$timeStamp = time();
		if($system->dbm->db->count_records("`$this->userSettings`", "`uid` = $_SESSION[uid] AND `name` = '$name'") > 0){
			$system->dbm->db->update("`$this->userSettings`", "`value` = '$value'", "`uid` =  $_SESSION[uid] AND `name` = '$name'");
			$_SESSION['lang'] = $value;
		}else{
			$system->dbm->db->insert("`$this->userSettings`", "`active`, `timeStamp`, `owner`, `group`, `or`, `ow`, `ox`, `uid`, `name`, `value`", "1, $timeStamp, $_SESSION[uid], 1, 1, 1, 1, $_SESSION[uid], '$name', '$value'");
			$_SESSION['lang'] = $value;
		}
		if($_SESSION['uid'] == 2){
			$_SESSION['lang'] = $value;
		}
		require_once 'module/cPanel/model/cPanel.php';
		$cPanel =  new m_cPanel();
		$cPanel->m_emptyCache(false);
		$system->watchDog->exception("s", $lang['setSettings'], sprintf($lang['successfulDone'], $lang['setSettings'], $lang['$name']) . "<br>" . $lang[reloadPageForSetChanges], null, "setTimeout('location.href=\'/\';', 1000);");
	}

	public function m_remember($userName,$mode){
		global $settings, $lang, $system;

		if(isset($userName)){
			$code = rand(1111, 99999999);

			if(strstr($userName, '@')){
//				echo 'a';
				$to['email'] = $system->dbm->db->informer("`$settings[userTalbe]`", "`email` = '$userName'", "email");
				$system->dbm->db->update("`$settings[userTalbe]`", "`passReset` = '$code'", "`email` = '$userName'");
			}elseif(is_numeric($userName)){
//				echo 'b';
				$to['mobile'] = $system->dbm->db->informer("`$settings[userTalbe]`", "`mobile` = '$userName'", "mobile");
				$system->dbm->db->update("`$settings[userTalbe]`", "`passReset` = '$code'", "`id` = '$_SESSION[uid]'");
			}
		}

		if($to){
			if(!empty($to['mobile'])){
				require_once 'module/sms/config/config.php';
				require_once 'module/sms/model/sms.php';
				m_sms::m_addObject($to['mobile'], $code, '', false);
//				echo "SMS-> To: $to[mobile] Code: $code";
			}
			if(!empty($to['email'])){
				$system->mail->CharSet = 'utf-8';
					
				$system->mail->From = $settings['roboMail'];
				$system->mail->FromName = $settings['domainName'];
				$system->mail->Subject = $lang[$mode];

				$system->xorg->smarty->assign("subject", $lang[$mode]);
				$system->xorg->smarty->assign("message", 'Code: ' . $code);
				$system->mail->Body = $system->xorg->smarty->fetch($settings['moduleAddress'] . "/mta/view/tpl/message" . $settings['ext4']);

				$system->mail->addAddress($to['email']);
				$system->mail->addReplyTo($settings['roboMail']);
				$system->mail->isHTML(true);

				$system->mail->send();
//				echo "Email-> To: $to[email] Code: $code";
			}

			$system->watchDog->exception("s", $lang['successful'], $lang[$mode]);
		}else{
			$system->watchDog->exception("e", $lang['warning'], $lang['userNotExist']);
		}
	}

	public function m_emActivation($values){
		global $system, $lang, $settings;

		if(isset($values['emailMobile'])){
// 			echo 3;
			if(strstr($values['emailMobile'], '@')){
// 				echo 4;
				$passReset = $system->dbm->db->informer("`$settings[userTalbe]`", "`id` = '$values[uid]'", "passReset");

				if(!empty($values['verificationCode'])){
// 					echo 6;
// 					echo "<div style='direction: ltr'>Post: $values[verificationCode] --- Reset: $passReset --- SESSION[uid]: $_SESSION[uid] --- uid: $values[uid]</div>";
					if($values['verificationCode'] == $passReset){
//						echo 7;
						$system->dbm->db->update("`$settings[userTalbe]`", "`showEmail` = 1, `passReset` = ''", "`id` = '$values[uid]'");
					}
				}
			}elseif(is_numeric($values['emailMobile'])){
// 				echo 0;
				$passReset = $system->dbm->db->informer("`$settings[userTalbe]`", "`id` = '$values[uid]'", "passReset");

				if(!empty($values['verificationCode'])){
// 					echo 8;
					if($values['verificationCode'] == $passReset){
// 						echo 9;
// 						echo 'mobile: ' . $system->dbm->db->informer("`$settings[userTable]`", "`id` = $_SESSION[uid]", "mobile") == '';
						if($system->dbm->db->informer("`$settings[userTable]`", "`id` = $values[uid]", "mobile") == '')
						$system->dbm->db->update("`$settings[userTalbe]`", "`mobile` = '$values[emailMobile]', `showMobile` = 1, `passReset` = ''", "`id` = '$values[uid]'");
						else
						$system->dbm->db->update("`$settings[userTalbe]`", "`showMobile` = 1, `passReset` = ''", "`id` = '$values[uid]'");
					}
				}
			}else{
				$system->watchDog->exception("e", $lang['error'], $lang['pleaseEnterValidData']);
			}
		}
		$system->watchDog->exception("s", $lang['successful'], $lang['successActivation'], '', "$('#content').farajax('loader', 'userMan/v_profile');$('#modalWindow').faraModal('closeModal', 'modalWindow');");
	}

	public function m_resetPass($userName, $code, $newPassword){
		global $settings, $lang, $system;

		$passReset = $system->dbm->db->informer("`$settings[userTalbe]`", "`userName` = '$userName' OR `email` = '$userName'", "passReset");
		if($passReset){
			if($passReset == $code){
				$newPassword = md5($newPassword);
				$system->dbm->db->update("`$settings[userTalbe]`", "`password` = '$newPassword', `passReset` = ''", "`userName` = '$userName' OR `email` = '$userName'");
				$system->watchDog->exception("s", $lang['successful'], $lang['passwordSuccessfullyChanged']);
			}else{
				$system->watchDog->exception("e", $lang['warning'], $lang['codeIsIncorrect']);
			}
		}else{
			$system->watchDog->exception("e", $lang['warning'], $lang['userNotExist']);
		}
	}

}
?>