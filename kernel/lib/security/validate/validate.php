<?php
class validate extends system{
	/*#######################################################
	 #      				Character Check 				#
	 ########################################################
	 #		Input Flag			# 		Export Flag			#
	 ########################################################
	 # nh => no html			# t => true					#
	 # h => html				# f => false				#
	 # s => string				#							#
	 # a => alfabet				#							#
	 # m => mail				#							#
	 # n => number				#							#
	 # mu => multilanguage		#							#
	 # ce => check empty		#							#
	 # nce => no check empty	#							#
	 #######################################################*/

	public $table = "access";

	function validate(){

		$this->table = $this->tablePrefix . $this->table;
	}

	public function chControl(){
		global $system, $lang;

		$variables = array(
		"GET" => $_GET,
		"POST" => $_POST,
		"COOCKIE" => $_COOCKIE,
//		"SERVER" => $_SERVER,
//		"SESSION" => $_SESSION
		);
		
//		print_r($_POST);
		
		foreach($variables as $method=>$methodValue){
//			print "Method Value: " . count($methodValue);
			if(count($methodValue) > 0){
				foreach($methodValue as $key=>$value){
					if(strstr($value, "::")){
//						print "name=$key, value=$value<br><br>";

						$valueArr = explode("::", $value);
//						print "<br><br><br><br>value0: $valueArr[0]";
						$validString = explode("<>", $valueArr[1]);

						$_POST[$key] = $valueArr[0];

						$validPoint[name] = $validString[0];
						$validPoint[type] = $validString[1];
						$validPoint[strip] = $validString[2];
						$validPoint[checkEmpty] = $validString[3];
						$validPoint[maxChar] = $validString[4];
						$validPoint[downLim] = $validString[5];
						$validPoint[upLim] = $validString[6];
						
						$validate = $this->validator($validPoint['name'], $valueArr[0], $validPoint['type'], $validPoint['strip'], $validPoint['checkEmpty'], $validPoint['maxChar'], $validPoint['downLim'], $validPoint['upLim']);
						if(!$validate[0])
						$ret[]= /*"<LI>" . */ $validate[1] /*. "<br>[Value: $valueArr[0]]<br>[Method: $method]<br>[Key: $key]<br>[ValidString: $valueArr[1]]" . "</LI>"*/;
					}//else{
//						if(is_array($value)){
//							foreach ($value as $key=>$val){
//								$validate = $this->validator($key, $val, 'String', 'h', 'nce', 45);
//								if(!$validate[0])
//								$ret[]= /*"<LI>" . */$validate[1] /*. "<br>[Value: $val]<br>[Method: $method]<br>[Key: $key]" ."</LI>"*/;
//							}
//						}else{
//							$validate = $this->validator($key, $value, 'String', 'h', 'ce', 45);
//							if(!$validate[0])
//							$ret[]= /*"<LI>" . */$validate[1] /*. "<br>[Value: $value]<br>[Method: $method]<br>[Key: $key]" ."</LI>"*/;
//						}
//					}
				}
			}
		}
		if(isset($ret)){
			$system->watchDog->exception("e", $lang[securityWarning], $ret);
		}
	}

	//Check Regular expression *****************
	public function eregor($value, $type){
		switch($type){
			case "String":
				return ereg("^[a-zA-Z0-9_\.\-]", $value) ? true : false;
				break;
			case "IP":
				return ereg("^([0-9]{1,3}\.){3}[0-9]{1,3}$",$value) ? true : false;
				break;
			case "URL":
				return ereg("^[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,4}$", $value) ? true : false;
				break;
			case "SSN":
				return ereg("^[0-9]{3}[- ][0-9]{2}[- ][0-9]{4}|[0-9]{9}$", $value) ? true : false;
				break;
			case "CC":
				return ereg("^([0-9]{4}[- ]){3}[0-9]{4}|[0-9]{16}$", $value) ? true : false;
				break;
			case "ISBN":
				return ereg("^[0-9]{9}[[0-9]|X|x]$", $value) ? true : false;
				break;
			case "Date":
				return ereg("^([0-9][0-2]|[0-9])\/([0-2][0-9]|3[01]|[0-9])\/[0-9]{4}|([0-9][0-2]|[0-9])-([0-2][0-9]|3[01]|[0-9])-[0-9]{4}$", $value) ? true : false;
				break;
			case "Zip":
				return ereg("^[0-9]{5}(-[0-9]{4})?$", $value) ? true : false;
				break;
			case "Phone":
				return ereg("^((\([0-9]{3}\) ?)|([0-9]{3}-))?[0-9]{3}-[0-9]{4}$", $value) ? true : false;
				break;
			case "HexColor":
				return ereg('^#?([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?$', $value) ? true : false;
				break;
			case "User":
				return ereg("^[a-zA-Z0-9_]{3,16}$", $value) ? true : false;
				break;
			case "Alphabet":
				return ereg("^[a-zA-Z]", $value) ? true : false;
				break;
			case "Mail":
				return ereg("^[-A-Za-z0-9_]+[-A-Za-z0-9_.]*[@]{1}[-A-Za-z0-9_]+[-A-Za-z0-9_.]*[.]{1}[A-Za-z]{2,5}$", $value) ? true : false;
				break;
			case "Number":
				return ereg("^[0-9]", $value) ? true : false;
				break;
			case "Integer":
				return is_int($value) ? true : false;
				break;
			case "Float":
				return is_float($value) ? true : false;
				break;
			case "Multi":
				return ereg("^'", $value) || ereg("'$", $value) ? false : true;
				break;
			default:
				return false;
				break;
		}
	}
	// Filter all bad words ********************
	public function wordFilter($word){
		global $lang, $system, $settings;

		$word = addslashes($word);
		$system->dbm->db->select("`word`", "`$settings[badWord]`", "`word` LIKE '$word'");
		$word = $system->dbm->db->fetch_row();

		return (!empty($word) ? true : false);
	}
	// Validate any string for HTML, regular expression and space
	public function validator($name, $value, $type, $strip, $checkEmpty, $maxChar, $downLim=null, $upLim=null){
		global $lang;

		$value = trim($value);		
		if($checkEmpty == 'ce'){
			if(!empty($value)){
				if(strlen($value) <= $maxChar){
					if(!empty($downLim) && !empty($upLim)){
						if($value >= $downLim && $value <= $upLim){
							if($this->eregor($value, $type)){
//								if(!$this->wordFilter($value)){
									return array(true, mysql_escape_string($value));
//								}else{
//									return array(false, sprintf($lang[yourWordIsBad], $name));
//								}
							}else{
								return array(false, sprintf($lang[yourPhraseHave], $name, $lang[illegalCharacter]));
							}
						}else{
							return array(false, sprintf($lang[yourPhraseMostBetween], $name, $downLim, $upLim));
						}
					}else{
						if($this->eregor($value, $type)){
//							if(!$this->wordFilter($value)){
								return array(true, mysql_escape_string($value));
//							}else{
//								return array(false, sprintf($lang[yourWordIsBad], $name));
//							}
						}else{
							return array(false, sprintf($lang[yourPhraseHave], $name, $lang[illegalCharacter]));
						}
					}
				}else{
					return array(false, sprintf($lang[yourPhraseIsBiggerThanMaxChar], $name, $maxChar));
				}
			}else{
				return array(false, sprintf($lang[yourPhraseIsEmpty], $name));
			}
		}elseif($checkEmpty == 'nce'){
			//print "<br>5";
			if(strlen($value) <= $maxChar){
				//print "<br>4";
				if(!empty($value) && !empty($downLim) && !empty($upLim)){
					//print "<br>3";
//					print "Value: " . $value;
					if($value >= $downLim && $value <= $upLim){
						//print "<br>2";
						if(!empty($value)){
							if($this->eregor($value, $type)){
								//print "<br>1<br>";
//								if(!$this->wordFilter($value)){
									return array(true, mysql_escape_string($value));
//								}else{
//									return array(false, sprintf($lang[yourWordIsBad], $name));
//								}
							}else{
								return array(false, sprintf($lang[yourPhraseHave], $name, $lang[illegalCharacter]));
							}
						}else{
							return array(true, mysql_escape_string($value));
						}
					}else{
						return array(false, sprintf($lang[yourPhraseMostBetween], $name, $downLim, $upLim));
					}
				}else{
					if(!empty($value)){
						if($this->eregor($value, $type)){
							//print "<br><br><br><br>er1";
//							if(!$this->wordFilter($value)){
								return array(true, mysql_escape_string($value));
//							}else{
//								return array(false, sprintf($lang[yourWordIsBad], $name));
//							}
						}else{
							//print "<br><br><br><br>er2";
							return array(false, sprintf($lang[yourPhraseHave], $name, $lang[illegalCharacter]));
						}
					}else{
						return array(true, mysql_escape_string($value));
					}
				}
			}else{
				return array(false, sprintf($lang[yourPhraseIsBiggerThanMaxChar], $name, $maxChar));
			}
		}
	}
}
?>
