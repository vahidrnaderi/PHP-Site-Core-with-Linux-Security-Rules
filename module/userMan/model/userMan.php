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
	public  $userTable = "usrs_ID";
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
		system::debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/userMan.php-> __construct()\n");
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
		global $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/userMan.php-> m_userMan()\n");

		$this->userTable = $this->tablePrefix . $this->userTable;
		$this->accessTable = $this->tablePrefix . $this->accessTable;
		$this->pattern = "dropDown"; //slide

	}

	public function m_signUp($values){
		global $system, $settings, $lang;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/userMan.php-> m_signUp(".print_r($values,true).")\n");
		
		$timeStamp = time();
		if(strstr($values['userName'], '@')){
			$filter = "`email` = '$values[userName]'";
			$signUpFlag = 'email';
		}else/** if((substr($values['userName'],0,2)== '09' ) && strlen($values['userName'])==11)**/{
			/**$filter = "`mobile` = '$values[userName]'";
			$signUpFlag = 'mobile';
		}else if((substr($values['userName'],0,2)!= '09') || ((substr($values['userName'],0,2)== '09' ) && strlen($values['userName'])!=11) ){**/
			$filter = "`userName` = '$values[userName]'";
			$signUpFlag = 'userName';
		}/**else {
			$signUpFlag = 'nothing';
		}**/
//echo "signUpFlag==>$signUpFlag ******</br> values['userName']==>$values[userName]";
		if ($signUpFlag != 'nothing'){
			if($system->dbm->db->count_records("`$this->userTable`", "`$signUpFlag` = '$values[userName]'") == 0){
//			if($values['password'] == $values['retypePassword']){
	////1			if(strlen($values['password']) >= $settings['minCharPassword']){
					if($_POST['securityQuestion'] === $system->dbm->db->informer("`$settings[faqObject]`", "`id` = $_POST[securityId]", "answer")){
						$password =$values['password'];//null; ////1 md5($values['password']);
	
						$system->dbm->db->insert("`$this->userTable`", "`active`, `timeStamp`, `owner`, `group`, `or`, `ow`, `ox`, `gr`, `gx`, `tr`, `tx`, `gid`, `password`, `$signUpFlag`, `userType`", "1, '$timeStamp', 1, 1, 1, 1, 1, 1, 1, 1, 1, 2, '$password', '$values[userName]', '0'");  ////1 $values[userType]
	
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
						if ($_SESSION['fromBasket']==0){
//$_SESSION['afterSignUp'] = true;
							$system->watchDog->exception('s', $lang['add'], sprintf($lang['successfulDone'], $lang['userAdd'], $values['userName']) . $lang['pleaseWait'], null, "setTimeout('location.href=\'/\';', 5000);");
						}else{
//							echo sprintf($lang['successfulDone'], $lang['userAdd'], $values['userName'])."</br></br>";
							// این بخش طوری ادیت شود که بعد از ثبت نام پیام ثبت نام تکمیل شد بیاید و بدون رفرش صفحه همه بخشهای مورد نیاز بعد از لوگ این درست شوند
							
// 							$system->watchDog->exception('s', $lang['add'], sprintf($lang['successfulDone'], $lang['userAdd'], $values['userName']) , null, "setTimeout('$(\'#modalWindow\').faraModal(\'closeModal\', \'modalWindow\');', 2500);");
							
						}
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
		}else{
			$system->watchDog->exception("e", $lang['error'], $lang['nothing']);
		}
	}

	public function m_userAdd($userName, $password){
		global $system, $lang, $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/userMan.php-> m_userAdd($userName, $password)\n");

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
		system::debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/userMan.php-> m_edit($values)\n");
		
		$timeStamp = time();
		
		$values['birthDate'] = (!empty($values['birthDate'])) ? '$values[birthDate]' : '0';
		$values['sendFrom'] = (!empty($values['sendFrom'])) ? $values['sendFrom'] : 1;
		$values['priority'] = (!empty($values['priority'])) ? $values['priority'] : 1;
		$values['addressType'] = (!empty($values['addressType'])) ? $values['addressType'] : 0;
		
//print_r($values);
		$religion = (!empty($values['religion'])) ? "$values[religion]" : null;
		$financialStatus = (!empty($values['financialStatus'])) ? "$values[financialStatus]" : null;
		$level = (!empty($values[level])) ? "$values[level]" : null;

		$deActiveMobile = ($system->dbm->db->informer("`$this->userTable`", "`id` = $_SESSION[uid]", "mobile") != $values['mobile']) ? ",`showMobile` = 0 " : null;
//		echo ("<br>*** " . $system->dbm->db->informer("`$settings[userTable]`", "`id` = $_SESSION[uid]", "mobile"))."<br> != <br>**** " .$values['mobile'] ;

		$userInfo = $system->dbm->db->informer("`$this->userTable`", "`id` = '$values[id]'");
		
		if($values['selectUserType'] == 1){
//echo "yes";
			$system->dbm->db->update("`$this->userTable`", "`userType`='$values[userType]'", "`id` = $values[id]");
		}elseif($values['userType'] == 2){  // company Type
//			echo 2;
		    $system->dbm->db->update("`$this->userTable`", "`userName` = '$values[userName]', `mobile` = '$values[mobile]' $deActiveMobile, `email` = '$values[email]', `completed` = 1", "`id` = $values[id]");
		    if ($system->dbm->db->count_records("$settings[usrsCo]", "`owner` = '$values[id]'")==0){
		        $system->dbm->db->insert("`$settings[usrsCo]`", "`active`, `timeStamp`, `owner`     , `group`        , `or`, `ow`, `ox`, `gr`, `gx`, `tr`, `tx`, `gid`           , `userPic`          , `coType`         ,  `coName`         , `nationalCode`         , `idNumber`       , `regTime`         , `country`         , `issued`       , `nationality`       , `workField`       , `workDetails`         , `coCapital`         , `certificates`         , `financialStatus` ",
		                                                         "  1     , '$timeStamp', $values[id], $userInfo[gid]   , 1    , 1  , 1    , 1  , 1    , 1  , 1   , $userInfo[gid]    , '$values[userPic]' , '$values[coType]',  '$values[coName]', '$values[nationalCode]', $values[idNumber], '$values[regTime]', '$values[country]', '$values[city]', $values[nationality], $values[workField], '$values[workDetails]', '$values[coCapital]', '$values[certificates]',  $financialStatus "); 
		    }else{
		        $system->dbm->db->update("`$settings[usrsCo]`", "`owner` = '$values[id]', `group` = '$userInfo[gid]', `gid` = '$userInfo[gid]', `userPic` = '$values[userPic]', `coType` = '$values[coType]', `coName` = '$values[coName]', `nationalCode` = '$values[nationalCode]', `idNumber` = '$values[idNumber]', `regTime` = '$values[regTime]', `issued` = '$values[city]', `nationality` = '$values[nationality]', `workField` = '$values[workField]', `workDetails` = '$values[workDetails]', `coCapital` = '$values[coCapital]', `country` = '$values[country]', `certificates` = $values[certificates], `financialStatus` = $values[financialStatus]", "`owner` = $values[id]");
		    }
		    if ($system->dbm->db->count_records("$settings[usrsAddress]", "`owner` = '$values[id]'")==0){
		        $system->dbm->db->insert("`$settings[usrsAddress]`", "`active`, `timeStamp` , `owner`    , `group`           , `or` , `ow`, `ox` , `gr`, `gx` , `tr`, `tx` , `gid`       , `addressType`          , `sendFrom`         , `priority`          , `country`       , `state`       , `city`     , `region`         , `district`       , `zipcode`         , `phone`         , `mainStreet`         , `address`         , `alley`         , `pNo`         , `floor`         , `unit`         ",
		                                                              "  1     , '$timeStamp', $values[id], $userInfo[gid]      , 1    , 1   , 1    , 1   , 1    , 1   , 1    , $userInfo[gid],  $values[addressType], $values[sendFrom], $values[priority] , $values[country], $values[state], $values[city], $values[region], $values[district], '$values[zipcode]', '$values[phone]', '$values[mainStreet]', '$values[address]', '$values[alley]', '$values[pNo]', '$values[floor]', '$values[unit]'");
		    }else{
		        $system->dbm->db->update("`$settings[usrsAddress]`", "`owner` = '$values[id]', `group` = '$userInfo[gid]', `gid` = '$userInfo[gid]', `addressType` = '$values[addressType]', `sendFrom` = '$values[sendFrom]', `priority` = '$values[priority]', `country` = '$values[country]', `state` = '$values[state]', `city` = $values[city], `region` = $values[region],`district` = $values[district], `zipcode` = '$values[zipcode]', `phone` = '$values[phone]', `mainStreet` = '$values[mainStreet]', `address` = '$values[address]', `alley` = '$values[alley]', `pNo`='$values[pNo]', `floor`='$values[floor]', `unit`='$values[unit]'", "`owner` = $values[id]");
		    }
		    if ($system->dbm->db->count_records("$settings[usrsBankAccounts]", "`owner` = '$values[id]'")==0){
		        $system->dbm->db->insert("`$settings[usrsBankAccounts]`", "`active`, `timeStamp`, `owner`     , `group`           , `or`, `ow`, `ox`, `gr`, `gx`, `tr`, `tx`, `gid`       , `priority`         ,	`bankName`        , `bankBranch`         , `branchCode`         , `accountNo`         , `Sheba`          , `cartNo`         , `ownerName`         , `bankPhone`         ",
		                                                                    "  1     , '$timeStamp', $values[id], $userInfo[gid]      , 1   , 1   , 1   , 1   , 1   , 1   , 1   , $userInfo[gid], '$values[priority]', '$values[bankName]', '$values[bankBranch]', '$values[branchCode]', '$values[accountNo]', ' $values[Sheba]', '$values[cartNo]', '$values[ownerName]', '$values[bankPhone]'");
		    }else{
		        $system->dbm->db->update("`$settings[usrsBankAccounts]`", "`owner` = '$values[id]', `group` = '$userInfo[gid]', `gid` = '$userInfo[gid]', `priority` = '$values[priority]', `bankName` = '$values[bankName]', `bankBranch` = '$values[bankBranch]', `branchCode` = '$values[branchCode]', `accountNo` = '$values[accountNo]', `Sheba` = $values[Sheba], `cartNo` = '$values[cartNo]', `ownerName` = '$values[ownerName]', `bankPhone` = '$values[bankPhone]'", "`owner` = $values[id]");
		    }
		    if ($system->dbm->db->count_records("$settings[usrsTell]", "`owner` = '$values[id]'")==0){
		        $system->dbm->db->insert("`$settings[usrsTell]`", "`active`, `timeStamp`, `owner`    , `group`            , `or`, `ow`, `ox`, `gr`, `gx`, `tr`, `tx`, `gid`        , `telType`         ,	`telPriority`       , `phone`         ",
		                                                           "  1     , '$timeStamp', $values[id], $userInfo[gid]      , 1    , 1  , 1    , 1  , 1    , 1  , 1    , $userInfo[gid], '$values[telType]', '$values[telPriority]', '$values[phone]'");
		    }else{
		        $system->dbm->db->update("`$settings[usrsTell]`", "`owner` = '$values[id]', `group` = '$userInfo[gid]', `gid` = '$userInfo[gid]', `telType` = '$values[telType]',	`telPriority` = '$values[telPriority]', `phone` = '$values[phone]'", "`owner` = $values[id]");
		    }
		    $fullName = '$lang[company] $values[coName] $values[coType]';
		    
		    
		}elseif($values['userType'] == 1){   // Personal Type
//			echo 1;
		    $system->dbm->db->update("`$this->userTable`", "`userName` = '$values[userName]', `mobile` = '$values[mobile]' $deActiveMobile, `email` = '$values[email]', `completed` = 1", "`id` = $values[id]");
    		
		    if ($system->dbm->db->count_records("$settings[usrsPersonal]", "`owner` = '$values[id]'")==0){
// 		        $system->dbm->db->insert("`$this->userTable`", "`active`, `timeStamp`, `owner`, `group`, `or`, `ow`, `ox`, `gr`, `gx`, `tr`, `tx`, `gid`, `password`, `$signUpFlag`, `userType`", "1, '$timeStamp', 1, 1, 1, 1, 1, 1, 1, 1, 1, 2, '$password', '$values[userName]', '0'");  ////1 $values[userType]
		        $system->dbm->db->insert("`$settings[usrsPersonal]`", "`active`, `timeStamp`, `owner`     , `group`        , `or`, `ow`, `ox`, `gr`, `gx`, `tr`, `tx`, `gid`           , `firstName`          , `lastName`         ,  `userPic`         , `fatherName`         , `gender`       , `idNumber`         , `personalCode`         , `state`       , `issued`     , `major`         , `proficiency`         , `nationality`         , `religion` , `financialStatus`, `level` , `workField`         , `workDetails`         , `birthDate`         , `employer`         ", 
		                                                               "  1     , '$timeStamp', $values[id], $userInfo[gid]   , 1    , 1  , 1    , 1  , 1    , 1  , 1   , $userInfo[gid]    , '$values[firstName]' , '$values[lastName]',  '$values[userPic]', '$values[fatherName]', $values[gender], '$values[idNumber]', '$values[personalCode]', $values[state], $values[city], '$values[major]', '$values[proficiency]', '$values[nationality]', $religion  , $financialStatus , $level  , '$values[workField]', '$values[workDetails]', $values[birthDate], '$values[employer]'");    		    
    		}else{
    		    $system->dbm->db->update("`$settings[usrsPersonal]`", "`owner` = '$values[id]', `group` = '$userInfo[gid]', `gid` = '$userInfo[gid]', `userPic` = '$values[userPic]', `firstName` = '$values[firstName]', `lastName` = '$values[lastName]', `fatherName` = '$values[fatherName]', `gender` = $values[gender], `idNumber` = '$values[idNumber]', `personalCode` = '$values[personalCode]', `state` = $values[state], `issued` = $values[city], `major` = '$values[major]', `proficiency` = '$values[proficiency]', `nationality` = '$values[nationality]' , `religion` = $religion , `financialStatus` = $financialStatus , `level` = $level, `workField` = '$values[workField]', `workDetails` = '$values[workDetails]', `birthDate`='$values[birthDate]', `employer`='$values[employer]'", "`owner` = $values[id]");
    		}
    		if ($system->dbm->db->count_records("$settings[usrsAddress]", "`owner` = '$values[id]'")==0){
    		    $system->dbm->db->insert("`$settings[usrsAddress]`", "`active`, `timeStamp` , `owner`    , `group`           , `or` , `ow`, `ox` , `gr`, `gx` , `tr`, `tx` , `gid`       , `addressType`          , `sendFrom`         , `priority`          , `country`       , `state`       , `city`     , `region`         , `district`       , `zipcode`         , `phone`         , `mainStreet`         , `address`         , `alley`         , `pNo`         , `floor`         , `unit`         ",
    		                                                          "  1     , '$timeStamp', $values[id], $userInfo[gid]      , 1    , 1   , 1    , 1   , 1    , 1   , 1    , $userInfo[gid],  $values[addressType], $values[sendFrom], $values[priority] , $values[country], $values[state], $values[city], $values[region], $values[district], '$values[zipcode]', '$values[phone]', '$values[mainStreet]', '$values[address]', '$values[alley]', '$values[pNo]', '$values[floor]', '$values[unit]'");
    		}else{
    		    $system->dbm->db->update("`$settings[usrsAddress]`", "`owner` = '$values[id]', `group` = '$userInfo[gid]', `gid` = '$userInfo[gid]', `addressType` = '$values[addressType]', `sendFrom` = '$values[sendFrom]', `priority` = '$values[priority]', `country` = '$values[country]', `state` = '$values[state]', `city` = $values[city], `region` = $values[region],`district` = $values[district], `zipcode` = '$values[zipcode]', `phone` = '$values[phone]', `mainStreet` = '$values[mainStreet]', `address` = '$values[address]', `alley` = '$values[alley]', `pNo`='$values[pNo]', `floor`='$values[floor]', `unit`='$values[unit]'", "`owner` = $values[id]");
    		}
    		if ($system->dbm->db->count_records("$settings[usrsBankAccounts]", "`owner` = '$values[id]'")==0){
    		    $system->dbm->db->insert("`$settings[usrsBankAccounts]`", "`active`, `timeStamp`, `owner`     , `group`           , `or`, `ow`, `ox`, `gr`, `gx`, `tr`, `tx`, `gid`       , `priority`         ,	`bankName`        , `bankBranch`         , `branchCode`         , `accountNo`         , `Sheba`          , `cartNo`         , `ownerName`         , `bankPhone`         ",
    		                                                                "  1     , '$timeStamp', $values[id], $userInfo[gid]      , 1   , 1   , 1   , 1   , 1   , 1   , 1   , $userInfo[gid], '$values[priority]', '$values[bankName]', '$values[bankBranch]', '$values[branchCode]', '$values[accountNo]', ' $values[Sheba]', '$values[cartNo]', '$values[ownerName]', '$values[bankPhone]'");
    		}else{
    		    $system->dbm->db->update("`$settings[usrsBankAccounts]`", "`owner` = '$values[id]', `group` = '$userInfo[gid]', `gid` = '$userInfo[gid]', `priority` = '$values[priority]', `bankName` = '$values[bankName]', `bankBranch` = '$values[bankBranch]', `branchCode` = '$values[branchCode]', `accountNo` = '$values[accountNo]', `Sheba` = $values[Sheba], `cartNo` = '$values[cartNo]', `ownerName` = '$values[ownerName]', `bankPhone` = '$values[bankPhone]'", "`owner` = $values[id]");
    		}
    		if ($system->dbm->db->count_records("$settings[usrsTell]", "`owner` = '$values[id]'")==0){
    		    $system->dbm->db->insert("`$settings[usrsTell]`", "`active`, `timeStamp`, `owner`    , `group`            , `or`, `ow`, `ox`, `gr`, `gx`, `tr`, `tx`, `gid`        , `telType`         ,	`telPriority`       , `phone`         ",
    		                                                       "  1     , '$timeStamp', $values[id], $userInfo[gid]      , 1    , 1  , 1    , 1  , 1    , 1  , 1    , $userInfo[gid], '$values[telType]', '$values[telPriority]', '$values[phone]'");
    		}else{
    		    $system->dbm->db->update("`$settings[usrsTell]`", "`owner` = '$values[id]', `group` = '$userInfo[gid]', `gid` = '$userInfo[gid]', `telType` = '$values[telType]',	`telPriority` = '$values[telPriority]', `phone` = '$values[phone]'", "`owner` = $values[id]");
    		}
		    $fullName = '$values[firstName] $values[lastName]';
		}
		
		
		$rs = $system->dbm->db->select("*", $settings[eDeliveryObject], "`owner` = '$values[id]'");
		while ($row = $system->dbm->db->fetch_array()){
			$system->dbm->db->update($settings[eDeliveryObject], "`timeStamp` = $timeStamp, `receiver` = '$fullName', `mobile` = '$values[mobile]', `phone` = '$values[phone]', `country` = '$values[country]', `state` = $values[state], `city` = $values[city], `region` = $values[region],`district` = $values[district], `address` = '$values[address]', `pNo`='$values[pNo]', `floor`='$values[floor]', `unit`='$values[unit]', `zipcode` = '$values[zipcode]'", "`id` = '$row[id]' AND `status` = `choiced`");
		}
		
		
		if ($_SESSION['fromBasket']==0){
			$system->watchDog->exception('s', $lang[userEdit], sprintf($lang[successfulDone], $lang[userEdit], $system->dbm->db->informer("`$this->userTable`", "`id` = $values[id]", "email")), '', "$('#content').farajax('loader', 'userMan/v_profile');$('#modalWindow').faraModal('closeModal', 'modalWindow');");
		}else{
			$system->watchDog->exception('s', $lang[userEdit], sprintf($lang[successfulDone], $lang[userEdit], $system->dbm->db->informer("`$this->userTable`", "`id` = $values[id]", "email")), '', "$('#register').farajax('loader', 'userMan/v_profile');$('#modalWindow').faraModal('closeModal', 'modalWindow');");
		}
	}

	public function m_userDel($filter){
		global $lang, $settings, $system;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/userMan.php-> m_userDel($filter)\n");

		$filter = $system->filterSplitter($filter);

		$userName = $system->dbm->db->informer("$this->userTable", "1 $filter", "userName");
		$system->dbm->db->delete("`$this->userTable`", "1 $filter");

		$system->watchDog->exception("s", $lang[delete], sprintf($lang[successfulDone], $lang[delete], $userName));
	}

	public function m_userList($filter=null, $viewMode='show'){
		global $lang, $settings, $system;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/userMan.php-> m_userList($filter, $viewMode)-- GET[filter]  --> $_GET[filter] -- fromBasket --> $_SESSION[fromBasket] or $fromBasket -- fromCPanel  --> $_SESSION[fromCPanel] or $fromCPanel \n");
		
//echo "</br>filter ==>".$filter."</br>";
		$filter = $system->filterSplitter($filter);

		$system->dbm->db->select("*", "`$settings[faqObject]`", "", "rand()", "", "", "0,1");
		$system->xorg->smarty->assign("securityQuestion", $row = $system->dbm->db->fetch_array());
		
// 		$system->xorg->pagination->paginateStart("userMan", "c_userList", "`id`, `active`, `timeStamp`, `userType`, `firstName`, `coName`, `coType`, `coCEO`, `coCapital`, `certificates`, `workField`, `regTime`, `workDetails`, `lastName`, `fatherName`, `userName`, `userPic`, `gender`, `idNumber`, `personalCode`, `nationalCode`, `country`, `state`, `issued`, `region`, `district`, `zipcode`, `address`, `pNo`, `floor`, `unit`, `nationality`, `religion`, `financialStatus`, `level`, `mobile`, `showMobile`, `phone`, `email`, `showEmail`, `major`, `proficiency`", "`$this->userTable`", "1 $filter", "`timeStamp` ASC");
		
		
		
		
		$userType = $system->dbm->db->informer($settings['usrsID'], "`id` = $_SESSION[uid]", "userType");
		$profileCompleted = $system->dbm->db->informer($settings['usrsID'], "`id` = $_SESSION[uid]", "completed");
		
// 		if ($userType==0 || $profileCompleted==0){
		
// 		}
		
		if ($profileCompleted==1){
    		if ($userType==1) {
    		   
//     		    $userTables = "usrs_ID AS `base`, usrs_Personal AS pers, usrs_Tel AS tel, usrs_Address AS addr";
    		    
    		    
    		    $userTables = "usrs_ID AS `base` 
                                    inner JOIN (usrs_Personal AS pers, usrs_Tel AS tel, usrs_Address AS addr)
    		                        ON (`base`.`id` = pers.id AND `base`.`id` = tel.owner AND `base`.`id` = addr.owner)";
    		    $system->xorg->pagination->paginateStart("userMan", "c_userList", "`base`.`id`, `base`.`active`, `base`.`timeStamp`, `base`.`userType`, pers.`firstName`, pers.`issued`, pers.`workField`, pers.`workDetails`, pers.`lastName`, `base`.`userName`, pers.`userPic`, pers.`gender`, pers.`personalCode`, addr.`country`, addr.`state`, addr.`city`, addr.`region`, addr.`district`, addr.`zipcode`, addr.`address`, addr.`pNo`, addr.`floor`, addr.`unit`, pers.`nationality`, pers.`religion`, pers.`financialStatus`, pers.`level`, `base`.`mobile`, `base`.`showMobile`, tel.`phone`, `base`.`email`, `base`.`showEmail`, pers.`major`, pers.`proficiency`, `base`.`completed`", "$userTables", "tel.telPriority=1 AND addr.priority=1 $filter", "`timeStamp` ASC");
    		    
    		}elseif ($userType==2){
    		   
//     		    $userTables = "usrs_ID AS `base`, usrs_Co AS co, usrs_Tel AS tel, usrs_Address AS addr";

    		    
    		    $userTables = "usrs_ID AS `base`
                                    inner JOIN (usrs_Co AS co, usrs_Tel AS tel, usrs_Address AS addr)
    		                        ON (`base`.`id` = co.id AND `base`.`id` = tel.owner AND `base`.`id` = addr.owner)";
    		    $system->xorg->pagination->paginateStart("userMan", "c_userList", "`base`.`id`, `base`.`active`, `base`.`timeStamp`, `base`.`userType`, co.`coName`, co.`issued`,  co.`coType`, co.`coCapital`, co.`certificates`, co.`regTime`, co.`workDetails`, `base`.`userName`, co.`userPic`, co.`nationalCode`, addr.`country`, addr.`state`, addr.`city`, addr.`region`, addr.`district`, addr.`zipcode`, addr.`address`, addr.`pNo`, addr.`floor`, addr.`unit`, co.`nationality`,  co.`financialStatus`, `base`.`mobile`, `base`.`showMobile`, tel.`phone`, `base`.`email`, `base`.`showEmail`, `base`.`completed`", "$userTables", "tel.telPriority=1 AND addr.priority=1 $filter", "`timeStamp` ASC");
    		    
    		}
		}else{
		    $userTables = "usrs_ID AS `base`";
		    $system->xorg->pagination->paginateStart("userMan", "c_userList", "`base`.`id`, `base`.`active`, `base`.`timeStamp`, `base`.`userType`, `base`.`userName`, `base`.`mobile`, `base`.`showMobile`, `base`.`email`, `base`.`showEmail`, `base`.`completed`", "$userTables", "1 $filter", "`timeStamp` ASC");		    
		}
		
		$count = 1;
		while ($row = $system->dbm->db->fetch_array()){
			$userList[$count][count] = $count;
			$userList[$count][id] = $row['id'];
			$userList[$count][active] = $row['active'] == 1 ? $lang['active'] : $lang['notActive'];
			$userList[$count][timeStamp] = $system->time->iCal->dator($row['timeStamp']);
			$userList[$count][userType] = $row['userType'];
			$userList[$count][userName] = $row['userName'];
			$userList[$count][mobile] = $row['mobile'];
			$userList[$count][showMobile] = $row['showMobile'];
			$userList[$count][phone] = $row['phone'];
			$userList[$count][email] = $row['email'];
			$userList[$count][showEmail] = $row['showEmail'];
			$userList[$count][completed] = $row['completed'];
			
			if ($row['completed']==1){
			    $userList[$count][workField] = $row['workField'];
			    $userList[$count][workDetails] = $row['workDetails'];
			    $imgTmp = explode(",", $row['userPic']);
			    $row[userPic] = $imgTmp[0];
			    $userList[$count][userPic] = (empty($row['userPic']) ? "theme/$settings[theme]/img/defaultUserPic.jpg" : "$row[userPic]");
			    $userList[$count][financialStatusId] = $row['financialStatus'];
			    $userList[$count][financialStatus] = $system->dbm->db->informer("`$this->statusTable`", "`id` = $row[financialStatus]", 'name');			    
			    $userList[$count][religionId] = $row['religion'];
			    $userList[$count][religion] = $system->dbm->db->informer("`$this->religionTable`", "`id` = $row[religion]", 'name');
			    $userList[$count][nationalCode] = $row['nationalCode'];
			    $userList[$count][nationalityId] = $row['nationality'];
			    $userList[$count][nationality] = $system->dbm->db->informer("`$this->countriesTable`", "`id` = $row[nationality]", 'name');
			    $userList[$count][issuedId] = $row['issued'];
			    $userList[$count][issued] = $system->dbm->db->informer("`$this->cityTable`", "`id` = $row[issued]", 'name');
			    $userList[$count][cityId] = $row['city'];
			    $userList[$count][city] = $system->dbm->db->informer("`$this->cityTable`", "`id` = $row[city]", 'name');
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
			    
    			if ($row['userType'] == 1){
    			    $userList[$count][firstName] = $row['firstName'];
    			    $userList[$count][lastName] = $row['lastName'];
    			    $userList[$count][fatherName] = $row['fatherName'];
    			    $userList[$count][genderId] = $row['gender'];
    			    $userList[$count][gender] = $system->dbm->db->informer("`$this->genderTable`", "`id` = $row[gender]", 'name');
    			    $userList[$count][idNumber] = $row['idNumber'];
    			    $userList[$count][personalCode] = $row['personalCode'];
    			    $userList[$count][levelId] = $row['level'];
    			    $userList[$count][level] = $system->dbm->db->informer("`$this->levelTable`", "`id` = $row[level]", 'name');
    			    $userList[$count][major] = $row['major'];
    			    $userList[$count][proficiency] = $row['proficiency'];
    			}elseif ($row['userType'] == 2){
    			    $userList[$count][coName] = $row['coName'];
    			    $userList[$count][coType] = $row['coType'];
    			    $userList[$count][coCapital] = $row['coCapital'];
    			    $userList[$count][coCEO] = $row['coCEO'];
    			    $userList[$count][certificates] = $row['certificates'];
    			    $userList[$count][regTime] = $row['regTime'];
    			}
			}
			$count++;
		}

		$userList[1]['fromBasket'] = $_SESSION['fromBasket'];
		
// 		while ($row = $system->dbm->db->fetch_array()){
// 		    $userList[$count][count] = $count;
// 		    $userList[$count][id] = $row['id'];
// 		    $userList[$count][active] = $row['active'] == 1 ? $lang['active'] : $lang['notActive'];
// 		    $userList[$count][timeStamp] = $system->time->iCal->dator($row['timeStamp']);
// 		    $userList[$count][userType] = $row['userType'];
// 		    $userList[$count][firstName] = $row['firstName'];
// 		    $userList[$count][coName] = $row['coName'];
// 		    $userList[$count][coType] = $row['coType'];
// 		    $userList[$count][coCapital] = $row['coCapital'];
// 		    $userList[$count][coCEO] = $row['coCEO'];
// 		    $userList[$count][certificates] = $row['certificates'];
// 		    $userList[$count][workField] = $row['workField'];
// 		    $userList[$count][regTime] = $row['regTime'];
// 		    $userList[$count][workDetails] = $row['workDetails'];
// 		    $userList[$count][lastName] = $row['lastName'];
// 		    $userList[$count][fatherName] = $row['fatherName'];
// 		    $userList[$count][userName] = $row['userName'];
// 		    $imgTmp = explode(",", $row['userPic']);
// 		    $row[userPic] = $imgTmp[0];
// 		    $userList[$count][userPic] = (empty($row['userPic']) ? "theme/$settings[theme]/img/defaultUserPic.jpg" : "$row[userPic]");
// 		    $userList[$count][genderId] = $row['gender'];
// 		    $userList[$count][gender] = $system->dbm->db->informer("$settings[gender]", "`id` = $row[gender]", 'name');
// 		    $userList[$count][idNumber] = $row['idNumber'];
// 		    $userList[$count][nationalCode] = $row['nationalCode'];
// 		    $userList[$count][personalCode] = $row['personalCode'];
// 		    $userList[$count][nationalityId] = $row['nationality'];
// 		    $userList[$count][nationality] = $system->dbm->db->informer("`$this->countriesTable`", "`id` = $row[nationality]", 'name');
// 		    $userList[$count][issuedId] = $row['issued'];
// 		    $userList[$count][issued] = $system->dbm->db->informer("`$this->cityTable`", "`id` = $row[issued]", 'name');
// 		    $userList[$count][stateId] = $row['state'];
// 		    $userList[$count][state] = $system->dbm->db->informer("`$this->stateTable`","`id` =  $row[state]", 'name');
// 		    $userList[$count][regionId] = $row['region'];
// 		    $userList[$count][region] = $system->dbm->db->informer("`$settings[region]`","`id` =  $row[region]", 'name');
// 		    $userList[$count][districtId] = $row['district'];
// 		    $userList[$count][district] = $system->dbm->db->informer("`$settings[district]`","`id` =  $row[district]", 'name');
// 		    $userList[$count][address] = $row['address'];
// 		    $userList[$count][pNo] = $row['pNo'];
// 		    $userList[$count][floor] = $row['floor'];
// 		    $userList[$count][unit] = $row['unit'];
// 		    $userList[$count][zipcode] = $row['zipcode'];
// 		    $userList[$count][religionId] = $row['religion'];
// 		    $userList[$count][religion] = $system->dbm->db->informer("`$this->religionTable`", "`id` = $row[religion]", 'name');
// 		    $userList[$count][financialStatusId] = $row['financialStatus'];
// 		    $userList[$count][financialStatus] = $system->dbm->db->informer("`$this->statusTable`", "`id` = $row[financialStatus]", 'name');
// 		    $userList[$count][levelId] = $row['level'];
// 		    $userList[$count][level] = $system->dbm->db->informer("`$this->levelTable`", "`id` = $row[level]", 'name');
// 		    $userList[$count][mobile] = $row['mobile'];
// 		    $userList[$count][showMobile] = $row['showMobile'];
// 		    $userList[$count][phone] = $row['phone'];
// 		    $userList[$count][email] = $row['email'];
// 		    $userList[$count][major] = $row['major'];
// 		    $userList[$count][proficiency] = $row['proficiency'];
// 		    $userList[$count][showEmail] = $row['showEmail'];
// 		    $count++;
// 		}
		
		
//print_r($userList);
		$system->xorg->smarty->assign("navigation", $system->xorg->pagination->renderFullNav());
		$system->xorg->smarty->assign("userList", $userList);
		
		system::debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/userMan.php-> m_userList--->\n \$userList==> \n ".print_r($userList,true)." \$_SESSION==> \n ".print_r($_SESSION,true)." \n");		
		
		if($count > 2){
			$system->xorg->smarty->display($settings['moduleAddress'] . "/" . $this->moduleName . "/view/tpl/userList" . $settings['ext4']);
		}else{
			if($viewMode == 'show'){
				if ($_SESSION[uid] != 2){
					$system->xorg->smarty->display($settings['moduleAddress'] . "/" . $this->moduleName . "/view/tpl/profile" . $settings['ext4']);
				}else{
					$system->xorg->smarty->display($settings['moduleAddress'] . "/" . $this->moduleName . "/view/tpl/register" . $settings['ext4']);
				}
			}elseif($viewMode == 'edit' && $_SESSION[uid] != 2){
				$system->xorg->smarty->display($settings['moduleAddress'] . "/" . $this->moduleName . "/view/tpl/edit" . $settings['ext4']);
			}
		}			

	}

	public function m_login($uid, $gid){
		global $lang, $system, $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/userMan.php-> m_login($uid, $gid)\n");

		$system->security->session->manager($uid, $gid);
		$system->xorg->smarty->assign("uid", $uid);
		$system->xorg->smarty->assign("gid", $gid);

		$this->m_updateGustBasket($uid, $gid);
		
		$this->m_loginContent();
////		$this->m_loginContentTitle();

//$_SESSION['afterSignUp'] = true;
		
	}
	
	function m_updateGustBasket($uid, $gid){
		global $lang, $system, $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/userMan.php-> m_updateGustBasket($uid, $gid) *** SESSION[sessionId] = $_SESSION[sessionId] *** SESSION[uid] = $_SESSION[uid]\n");
		
		if($_SESSION['uid'] && $_SESSION['uid'] != 2){
			
			$timeStamp = time();
			
 			$rs = $system->dbm->db->select("id, objectId, count", $settings[basketObject], "`uid`= 2 AND `sessionId` = '$_SESSION[sessionId]'");
 			
//  			$row = $system->dbm->db->fetch_array();
//  			echo "</br>row==></br>";
//  			print_r ($rs);
//  			echo "</br>--> </br>";
//  			print_r ($system->dbm->db->num_fields());
//  			echo "</br>==>";
//  			print_r ($system->dbm->db->count_rows($rs));
//  			echo "</br>";
//  			print_r ($system->dbm->db->fetch_row());
//  			echo "</br>";
 			
			while ($row = $system->dbm->db->fetch_array()){
//			while ($row=mysqli_fetch_array(mysqli_query($system->dbm->db->dbhandler, "SELECT id FROM $settings[basketObject] WHERE `sessionId` = '$_SESSION[sessionId]'"))){
// 				echo "</br>row==></br>";
// 				print_r ($row);
// 				echo "</br>";
// 				system::debug($settings['debugFile'], "chrL", "	Module-Function=>  Module >> model/userMan.php-> \n row==> \n ".print_r ($row,true)."\n   m_updateGustBasket($uid, $gid) *** SESSION[sessionId] = $_SESSION[sessionId]\n");
				
// 				$objectRow = $system->dbm->db->select("id, count", $settings[basketObject], "`objectId` = '$row[objectId]' AND `uid` = '$_SESSION[uid]'");
				// در دو خط زیر اگر کاربر قبلا همین کالای خریداری شده با یوزر مهمان را خریداری کرده آنرا یافته و پاک میکنیم
				$objectRowId = $system->dbm->db->informer($settings[basketObject], "`objectId` = $row[objectId] AND `uid` = $_SESSION[uid]", "id");
// 				$objectRowCount = $system->dbm->db->informer($settings[basketObject], "`objectId` = $row[objectId] AND `uid` = $_SESSION[uid]", "count");
				$system->dbm->db->delete($settings[basketObject], "`id` = '$objectRowId'");
				
// 				$totalCount = $objectRowCount + $row[count];
				
// 				echo "</br>totalCount==>$totalCount = $objectRowCount + $row[count]</br>";
				
//				$system->dbm->db->update($settings[basketObject], "`timeStamp` = $timeStamp, `owner` = $uid, `uid` = $uid, `count` = $totalCount, `group` = $gid, `sessionId` = '$_SESSION[sessionId]'", "`id` = '$row[id]'");
				$system->dbm->db->update($settings[basketObject], "`timeStamp` = $timeStamp, `owner` = $uid, `uid` = $uid, `group` = $gid, `sessionId` = '$_SESSION[sessionId]'", "`id` = '$row[id]'");
			}
			
			$rs = $system->dbm->db->select("id, objectId, count", $settings[basketObject], "`uid` = '$_SESSION[uid]'");
				
// 			echo "</br>row==></br>";
// 			print_r ($rs);
// 			echo "</br>";
			
			while ($row = $system->dbm->db->fetch_array()){
				$system->dbm->db->update($settings[basketObject], "`sessionId` = '$_SESSION[sessionId]'", "`id` = '$row[id]'");
			}
			
			if ($system->dbm->db->informer("`$settings[usrsID]`","`id` = $uid","userType")==1) {
			    $resArray=$system->dbm->db->usrsQuery('pers');
			    $resArray[fullname]=$resArray['firstName']." ".$resArray['lastName'];
			    $userTypeFlag=1;
			}elseif ($system->dbm->db->informer("`$settings[usrsID]`","`id` = $uid","userType")==2){
			    $resArray=$system->dbm->db->usrsQuery('co');
			    $resArray[fullname]=$lang['company']." ".$resArray['coType']." ".$resArray['coName'];
			    $userTypeFlag=2;
			}elseif ($system->dbm->db->informer("`$settings[usrsID]`","`id` = $uid","userType")==0){
			    $userTypeFlag=0;
			}
			
// 			$resArray = mysqli_fetch_array(mysqli_query($system->dbm->db->dbhandler, "SELECT * FROM `user` WHERE `id` = $uid"));
			$rs = $system->dbm->db->select("id, objectId, count", $settings[eDeliveryObject], "`uid`= 2 AND `sessionId` = '$_SESSION[sessionId]'");
			
// 			echo "</br>row==></br>";
// 			print_r ($rs);
// 			echo "</br>";
			
			while ($row = $system->dbm->db->fetch_array()){
// 				echo "</br>row==></br>";
// 				print_r ($row);
// 				echo "</br>";

// 				$objectRow = $system->dbm->db->select("id, count", $settings[eDeliveryObject], "`objectId` = '$row[objectId]' AND `uid` = '$_SESSION[uid]'");
// 				$system->dbm->db->delete($settings[eDeliveryObject], "`id` = '$objectRow[id]'");
// 				$totalCount = $objectRow[count] + $row[count];
				
				
				$objectRowId = $system->dbm->db->informer($settings[eDeliveryObject], "`objectId` = $row[objectId] AND `uid` = $_SESSION[uid]", "id");
// 				$objectRowCount = $system->dbm->db->informer($settings[eDeliveryObject], "`objectId` = $row[objectId] AND `uid` = $_SESSION[uid]", "count");
				$system->dbm->db->delete($settings[eDeliveryObject], "`id` = '$objectRowId'");
				
// 				$totalCount = $objectRowCount + $row[count];
				
// 				echo "</br>totalCount==>$totalCount = $objectRowCount + $row[count]</br>";
				
// 				echo "</br>row==></br>";
// 				print_r ($resArray);
// 				echo "</br>mysqli_num_rows==> ".mysqli_num_rows($result);
// 				echo "</br>";
//				$system->dbm->db->update($settings[eDeliveryObject], "`timeStamp` = $timeStamp, `owner` = $uid, `group` = $gid, `uid` = $uid, `count` = $totalCount, `receiver` = '$resArray[firstName] $resArray[lastName]', `mobile` = '$resArray[mobile]', `state` = $resArray[state], `city` = $resArray[issued], `region` = $resArray[region], `district` = $resArray[district], `address` = '$resArray[address]', `pNo` = $resArray[pNo], `floor` = $resArray[floor], `unit` = $resArray[unit], `zipcode` = $resArray[zipcode], `sessionId` = '$_SESSION[sessionId]'", "`id` = '$row[id]'");
				if ($userTypeFlag==0){
				    $system->dbm->db->update($settings[eDeliveryObject], "`timeStamp` = $timeStamp, `owner` = $uid, `group` = $gid, `uid` = $uid, `sessionId` = '$_SESSION[sessionId]'", "`id` = '$row[id]'");
				}else{
				    $system->dbm->db->update($settings[eDeliveryObject], "`timeStamp` = $timeStamp, `owner` = $uid, `group` = $gid, `uid` = $uid, `receiver` = '$resArray[fullname]', `mobile` = '$resArray[mobile]', `country`='$resArray[country]', `state` = $resArray[state], `city` = $resArray[city], `region` = $resArray[region], `district` = $resArray[district], `address` = '$resArray[address]', `pNo` = $resArray[pNo], `floor` = $resArray[floor], `unit` = $resArray[unit], `zipcode` = $resArray[zipcode], `sessionId` = '$_SESSION[sessionId]'", "`id` = '$row[id]'");
				}
				
			}
			
			// زمان تحویل کلیه کالاهای جدید یا قدیمی موجود در سبد خرید مشتری را به دو روز بعد از خرید تغییر میدهد 
			$yearNumber = $system->time->iCal->dator($timeStamp, 'y');
			$monthNumber = $system->time->iCal->dator($timeStamp, 'm');
			$dayNumber = $system->time->iCal->dator($timeStamp, 'd') + 2;
			
			$rs = $system->dbm->db->select("id, objectId, count", $settings[eDeliveryObject], "`uid` = '$_SESSION[uid]'");
				
			while ($row = $system->dbm->db->fetch_array()){			
				$system->dbm->db->update($settings[eDeliveryObject], "`year` = $yearNumber, `month` = $monthNumber, `day` = $dayNumber, `sessionId` = '$_SESSION[sessionId]'", "`id` = '$row[id]'");
			}
			
// 			$basketObjects = mysqli_fetch_array($res);
			
			
			
// 			echo "</br>res==></br>";
// 			print_r ($res);
// 			echo "</br>";
			
// 			if (!empty ($entityList)){
// 				foreach ($entityList as $item){
// 					//		echo "1<br/>";
// 					$entityList[$item[num]][eDeliveryType]= $system->xorg->htmlElements->selectElement->select('eDeliveryType'+$entityList[$item[num]][id], $system->dbm->db->lookup("`$settings[eDeliveryType]`"), $entityList[$item[num]]['eDeliveryTypeSelected']);
// 					//		echo "2<br/>";
// 				}
// 			}
			
		}
	}
	
	function m_welcomeName($uid){
		global $lang, $system, $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/userMan.php-> m_welcomeName($uid)**  SESSION[uid]==>$_SESSION[uid] \n");
		
//			echo "uid=" . $_SESSION['uid'] . " t1";
// 		$res = mysqli_query($system->dbm->db->dbhandler, "SELECT `userType`, `gender`, `firstName`, `lastName`, `userName`, `userPic`, `coName`, `email` FROM `$this->userTable` WHERE `id` = $uid");
		$res = mysqli_query($system->dbm->db->dbhandler, "SELECT `userType`, `userName`, `email` FROM `$this->userTable` WHERE `id` = $uid");
		$profile = mysqli_fetch_array($res);
			
// 		if ($profile['userType']==0){
// 			$genderType = $lang['user'];
// //echo "<br> userType = Unknown";				
// 		}else{
// 			if ($profile['userType']==1){
			    
// 			    $personal = $system->dbm->db->informer("$settings[usrs_personal]", "`uid` = $uid");
// 			    if (!empty($personal['firstName']) || !empty($personal['lastName'])){
// 			        $showName = $personal['firstName'] . " " . $personal['lastName'];
// 			    }
// //echo "<br> userType = Person";
// 			    if($personal['gender'] == 1){
// //echo "<br> gender = mr";
// 					$genderType = $lang['mr'];
// 			    }elseif ($personal['gender'] == 2){
// //echo "<br> gender = ms";
// 					$genderType = $lang['ms'];
// 				}else{
// //echo "<br> gender = user";
// 					$genderType = $lang['user'];
// 				}
				
// 			}elseif ($profile['userType']==2){
// 			    $co = $system->dbm->db->informer("$settings[usrs_co]", "`uid` = $uid");
// //echo "<br> userType = company";
// 				$genderType = $lang['company'];
// 			}
// 		}
		
		switch ($profile['userType']){
		    case 1:
		        $personal = $system->dbm->db->informer("$settings[usrs_personal]", "`uid` = $uid");
		        switch ($personal['gender']){
		            case 1:
		                $genderType = $lang['mr'];
		                break;
		            case 2:
		                $genderType = $lang['ms'];
		                break;
		            default:
		                $genderType = $lang['user'];
		        }
		        break;
		    case 2:
		        $co = $system->dbm->db->informer("$settings[usrs_co]", "`uid` = $uid");
		        $genderType = $lang['company'];
		        break;
		    default:
		        $genderType = $lang['user'];
		}
		
// 		$showName = !empty($profile['firstName']) || !empty($profile['lastName']) ? $profile['firstName'] . " " . $profile['lastName'] : $profile['email'];
		if (!empty($personal['firstName']) || !empty($personal['lastName'])){
		    $showName = $personal['firstName'] . " " . $personal['lastName'];
		}elseif (!empty($profile['email'])){
		    $showName = $profile['email'];
		}else{
		    $showName = $profile['userName'];
		}
		$companyName = $co['coName']." ".$co['coType'];
		
		$welcomeName['genderType']=$genderType;
		$welcomeName['welcomeFullName']= $profile['userType']==2 ? $genderType." ".$companyName : $genderType." ".$showName;
		$welcomeName['welcomeName']= $profile['userType']==2 ? $companyName : $showName;
			
		return $welcomeName;
	}
			
	
	function m_loginContent(){
		global $lang, $system, $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/userMan.php-> m_loginContent() & SESSION[]-->".print_r($_SESSION,true)." \n");
		
		if($_SESSION['uid'] && $_SESSION['uid'] != 2){
//			echo "uid=" . $_SESSION['uid'] . " t1";
			
			$welcomeName= $this->m_welcomeName($_SESSION['uid']);
			
			if ($system->dbm->db->informer("`$settings[usrsID]`","`id` = $_SESSION[uid]","userType")==1) {
			    $profile=$system->dbm->db->usrsQuery('pers');
			    $profile[fullname]=$profile['firstName']." ".$profile['lastName'];
			}elseif ($system->dbm->db->informer("`$settings[usrsID]`","`id` = $_SESSION[uid]","userType")==2){
			    $profile=$system->dbm->db->usrsQuery('co');
			    $profile[fullname]=$lang['company']." ".$profile['coType']." ".$profile['coName'];
			}
			
			$imgTmp = explode(",", $profile['userPic']);
			$profile[userPic] = $imgTmp[0];
			$system->xorg->smarty->assign("userPic", empty($profile['userPic']) ? "theme/$settings[theme]/img/defaultUserPic.jpg" : $profile['userPic']);
			$system->xorg->smarty->assign("loginFlag", true);

			$system->xorg->smarty->assign("welcomeMessage", sprintf($lang['welcomeMessage'], $welcomeName['genderType'], "<b>$welcomeName[welcomeName]</b>"));
			if ($_SESSION['fromBasket']==1 AND $_SESSION['afterSignUp']==0){
///////////// نمایش انتخاب شخصیت حقیقی یا حقوقی	
				$system->xorg->smarty->assign("fromBasket", 1);
				m_userMan::m_userList("base.id=$_SESSION[uid]", 'show');
				$_SESSION['afterSignUp'] = 1;
			}else{
				if(file_exists($settings['commonTpl'] . $this->moduleName . '/welcomeMessage' . $settings['ext4'])){
//				echo " t11";
					return $system->xorg->smarty->display($settings['commonTpl'] . $this->moduleName . "/welcomeMessage" . $settings['ext4']);
				}else{
//				echo " t12";				
					return $system->xorg->smarty->display($settings['moduleAddress'] . "/" . $this->moduleName . "/view/tpl/welcomeMessage" . $settings['ext4']);
				}				
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
		system::debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/userMan.php-> m_loginContentTitle()\n");
		
		if($_SESSION['uid'] && $_SESSION['uid'] != 2){
//echo "<br> uid=" . $_SESSION['uid'] . " t1";
			
			$welcomeName= $this->m_welcomeName($_SESSION['uid']);
			
			
			if ($system->dbm->db->informer("`$settings[usrsID]`","`id` = $_SESSION[uid]","userType")==1) {
			    $profile=$system->dbm->db->usrsQuery('pers');
			    $profile[fullname]=$profile['firstName']." ".$profile['lastName'];
			}elseif ($system->dbm->db->informer("`$settings[usrsID]`","`id` = $_SESSION[uid]","userType")==2){
			    $profile=$system->dbm->db->usrsQuery('co');
			    $profile[fullname]=$lang['company']." ".$profile['coType']." ".$profile['coName'];
			}
			
			
			$imgTmp = explode(",", $profile['userPic']);
			$profile[userPic] = $imgTmp[0];
			$system->xorg->smarty->assign("userPic", empty($profile['userPic']) ? "theme/$settings[theme]/img/defaultUserPic.jpg" : $profile['userPic']);
			$system->xorg->smarty->assign("loginFlag", true);

			$system->xorg->smarty->assign("welcomeMessageTitle", "<b>$welcomeName[welcomeName]</b>");
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
		system::debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/userMan.php-> m_menu($pattern)\n");

		return $system->xorg->smarty->display($settings['commonTpl'] . 'menu' . $settings['ext4']);
	}

	public function m_logout(){
		global $system, $lang, $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/userMan.php->  m_logout()\n");
		
//echo "<br> m_logout --> $_SESSION[uid] <br>";
//echo "<br>userMan.php_line316   uid --> $_SESSION[uid] # $uid **** gid --> $_SESSION[gid] # $gid time-->".time()."</br> ";
		$uid = $_SESSION['uid'];
		$gid = $_SESSION['gid'];
//echo "<br>userMan.php_line319   uid --> $_SESSION[uid] # $uid **** gid --> $_SESSION[gid] # $gid time-->".time()."</br> ";
		$system->security->session->kill($_SESSION['uid']);
//echo "<br>userMan.php_line321   uid --> $_SESSION[uid] # $uid **** gid --> $_SESSION[gid] # $gid time-->".time()."</br> ";
//		$system->watchDog->exception("s", $lang['logout'], sprintf($lang['successfulDone'], $lang['logout'], $system->dbm->db->informer("`$this->userTable`", "`id` = $uid", 'email')));
// 		$welcomeMessage = $this->m_welcomeName($uid);
		$system->watchDog->exception("s", $lang['logout'], sprintf($lang['successfulDone'], $lang['logout'], $this->m_welcomeName($uid)['welcomeFullName']));
//echo "<br>userMan.php_line323   uid --> $_SESSION[uid] # $uid **** gid --> $_SESSION[gid] # $gid time-->".time()."</br> ";
	}

	public function m_setSettings($name, $value){
		global $system, $lang, $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/userMan.php-> m_setSettings($name, $value)\n");

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
		system::debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/userMan.php-> m_remember($userName,$mode)\n");

		if(isset($userName)){
			$code = rand(1111, 99999999);

			if(strstr($userName, '@')){
//				echo 'a';
				$to['email'] = $system->dbm->db->informer("`$settings[usrs_ID]`", "`email` = '$userName'", "email");
				$system->dbm->db->update("`$settings[usrs_ID]`", "`passReset` = '$code'", "`email` = '$userName'");
			}elseif(is_numeric($userName)){
//				echo 'b';
			    $to['mobile'] = $system->dbm->db->informer("`$settings[usrs_ID]`", "`mobile` = '$userName'", "mobile");
			    $system->dbm->db->update("`$settings[usrs_ID]`", "`passReset` = '$code'", "`id` = '$_SESSION[uid]'");
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
		system::debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/userMan.php-> m_emActivation($values)\n");

		if(isset($values['emailMobile'])){
// 			echo 3;
			if(strstr($values['emailMobile'], '@')){
// 				echo 4;
			    $passReset = $system->dbm->db->informer("`$settings[usrs_ID]`", "`id` = '$values[uid]'", "passReset");

				if(!empty($values['verificationCode'])){
// 					echo 6;
// 					echo "<div style='direction: ltr'>Post: $values[verificationCode] --- Reset: $passReset --- SESSION[uid]: $_SESSION[uid] --- uid: $values[uid]</div>";
					if($values['verificationCode'] == $passReset){
//						echo 7;
					    $system->dbm->db->update("`$settings[usrs_ID]`", "`showEmail` = 1, `passReset` = ''", "`id` = '$values[uid]'");
					}
				}
			}elseif(is_numeric($values['emailMobile'])){
// 				echo 0;
			    $passReset = $system->dbm->db->informer("`$settings[usrs_ID]`", "`id` = '$values[uid]'", "passReset");

				if(!empty($values['verificationCode'])){
// 					echo 8;
					if($values['verificationCode'] == $passReset){
// 						echo 9;
// 						echo 'mobile: ' . $system->dbm->db->informer("`$settings[userTable]`", "`id` = $_SESSION[uid]", "mobile") == '';
					    if($system->dbm->db->informer("`$settings[usrs_ID]`", "`id` = $values[uid]", "mobile") == '')
					        $system->dbm->db->update("`$settings[usrs_ID]`", "`mobile` = '$values[emailMobile]', `showMobile` = 1, `passReset` = ''", "`id` = '$values[uid]'");
						else
						    $system->dbm->db->update("`$settings[usrs_ID]`", "`showMobile` = 1, `passReset` = ''", "`id` = '$values[uid]'");
					}
				}
			}else{
				$system->watchDog->exception("e", $lang['error'], $lang['pleaseEnterValidData']);
			}
		}
		
		if ($_SESSION['fromBasket']==0){
			$system->watchDog->exception("s", $lang['successful'], $lang['successActivation'], '', "$('#content').farajax('loader', 'userMan/v_profile');$('#modalWindow').faraModal('closeModal', 'modalWindow');");
		}else{
			$system->watchDog->exception("s", $lang['successful'], $lang['successActivation'], '', "$('#register').farajax('loader', 'userMan/v_profile');$('#modalWindow').faraModal('closeModal', 'modalWindow');");
		}
	}

	public function m_resetPass($userName, $code, $newPassword){
		global $settings, $lang, $system;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/userMan.php-> m_resetPass($userName, $code, $newPassword)\n");

		$passReset = $system->dbm->db->informer("`$settings[usrs_ID]`", "`userName` = '$userName' OR `email` = '$userName'", "passReset");
		if($passReset){
			if($passReset == $code){
				$newPassword = md5($newPassword);
				$system->dbm->db->update("`$settings[usrs_ID]`", "`password` = '$newPassword', `passReset` = ''", "`userName` = '$userName' OR `email` = '$userName'");
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