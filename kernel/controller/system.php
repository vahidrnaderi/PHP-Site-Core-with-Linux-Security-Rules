<?php
class system{

	public $dbm;
	public $lang;
	public $security;
	public $tablePrefix;
	public $time;
	public $mail;
	public $module;
	public $relation;
	public $rss;
	public $seo;
	public $utility;
	public $watchDog;
	public $logger;
	public $xorg;
	public $debug;

	public function debug($fileName, $type, $message){
		global $settings;
		
		$localTime=date("e(P)= Y/m/d-H:i:s");
		
		if($settings['debug']=='on')
			switch ($type){
				case "str":
					$message = "\n#####################\n".$localTime."-->Start Debug: ".$message;
					break;
				case "chr":
					if($settings['chr']=='on')
						$message = $localTime."-->Chart: ".$message;
					break;
				case "chrF":
					if($settings['chrF']=='on')
						$message = $localTime."-->Chart Functions: ".$message;
					break;
				case "chrM":
					if($settings['chrM']=='on')
						$message = $localTime."-->Chart Functions: ".$message;
					break;
				case "chrE":
					if($settings['chrE']=='on')
						$message = $localTime."-->Error: ".$message;
					break;
				case "chrL":
					if($settings['chrL']=='on')
						$message = $localTime."-->Debug Log: ".$message;
					break;
				default:
					$message = $localTime."-->Fatal-Error: system.php=> Function: debug($fileName, $type, $message) -> No Debug's type definition.";
					break;
		}

// 		echo "<br\>1->". var_dump(file_exists($settings[debugFile]))."<br\>";	
// 		echo "<br\>";
//		echo "<br\>1->".var_dump(is_writable($settings[debugFile]))."<br\>";
// echo "<br/>2->$settings[debugFile]--> ***-->>debug($fileName, $type, $message)<br/>";		
		if($settings['debugMyFileWrite']=='on'){
// echo "<br\>3<br\>";	
			$myFile = fopen($settings['debugFile'], "a") or die("Unable to open file!");
//			echo "myfile->$myfile<br>";
			fwrite($myFile, "-----------$localTime************debug($fileName, $type, $message)\n");
			fclose($myFile);
		}
		
		
//			echo "message->$message<br>";
		$myFile = fopen($fileName, "a") or die("Unable to open file!");
//			echo "myfile->$myfile<br>";
		fwrite($myFile, $message);
		fclose($myFile);
	}
	
	public function system(){
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> system.php-> system()\n");
		

		$this->tablePrefix = $settings['tablePrefix'];


		/* logger sub system */
		$subSystem = $settings['libraryAddress'] . "/logger/" . "logger" . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->logger = new logger();
		}else{
			$this->run($subSystem, 'Off');
		}
		
		/* Database sub system */
		$subSystem = $settings['libraryAddress'] . "/dbm/" . "dbm" . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->dbm = new dbm($settings['type'], $settings['host'], $settings['user'], $settings['pass'], $settings['name']);
		}else{
			$this->run($subSystem, 'Off');
		}

		/* Feed sub system */
		$subSystem = $settings['libraryAddress'] . "/feed/" . "rss" . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->rss = new rss();
		}else{
			$this->run($subSystem, 'Off');
		}

		/* Lang sub system */
		$subSystem = $settings['libraryAddress'] . "/lang/" . "lang" . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->lang = new lang();
		}else{
			$this->run($subSystem, 'Off');
		}

		/* Mail sub system */
		$subSystem = $settings['libraryAddress'] . "/mail/" . "PHPMailerAutoload" . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->mail = new PHPMailer();
		}else{
			$this->run($subSystem, 'Off');
		}
		
		/* Module sub system */
		$subSystem = $settings['libraryAddress'] . "/module/" . "module" . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->module = new module();
		}else{
			$this->run($subSystem, 'Off');
		}

		/* Relation sub system */
		$subSystem = $settings['libraryAddress'] . "/relation/" . "relation" . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->relation = new relation();
		}else{
			$this->run($subSystem, 'Off');
		}

		/* SEO sub system */
		$subSystem = $settings['libraryAddress'] . "/seo/" . "seo" . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->seo = new seo();
		}else{
			$this->run($subSystem, 'Off');
		}

		/* Security sub system */
		$subSystem = $settings['libraryAddress'] . "/security/" . "security" . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->security = new security();
		}else{
			$this->run($subSystem, 'Off');
		}

		/* Time sub system */
		$subSystem = $settings['libraryAddress'] . "/time/" . "time" . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->time = new time();
		}else{
			$this->run($subSystem, 'Off');
		}

		/* Utility sub system */
		$subSystem = $settings['libraryAddress'] . "/utility/" . "utility" . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->utility = new utility();
		}else{
			$this->run($subSystem, 'Off');
		}

		/* WatchDog sub system */
		$subSystem = $settings['libraryAddress'] . "/watchDog/" . "watchDog" . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->watchDog = new watchDog();
		}else{
			$this->run($subSystem, 'Off');
		}

		/* Xorg sub system */
		$subSystem = $settings['libraryAddress'] . "/xorg/" . "xorg" . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->xorg = new xorg();
		}else{
			$this->run($subSystem, 'Off');
		}

	}

	public function run($subSystem, $status){
		global $settings;
		
		if($status == 1 || $status == 'On' || $status == 'on'){
			require_once($subSystem);
			$this->debug($settings['debugFile'], "chr", "Function=> system.php-> run(subSystem=> $subSystem , status is ON)\n");
		}elseif($status == 0 || $status == 'Off' || $status == 'off'){
			$this->debug($settings['debugFile'], "err", "Function=> system.php-> run(subSystem=> $subSystem , status is OFF)\n");
			die("\"$subSystem\" is Off");
		}
	}

	public function filterSplitter($string){
		global $settings;
		$this->debug($settings['debugFile'], "chrF", "	Function=> system.php-> filterSplitter($string)\n");
		
		if(strstr($string, ',') || strstr($string, '=')){
			$records = explode(",", $string);
			foreach ($records as $key => $record){
				if(!empty($record)){
					$string = explode("=", $record);
					if(isset($string[1])){
						$slices[$key]['name'] = $string[0];
						$slices[$key]['value'] = $string[1];
					}
				}
			}
//echo 'slices-> <br>';			
//print_r($slices);
//echo '<br>';
//			if ($slices[0][value] == "AND"){
//echo 44;				
				if(is_array($slices)){
					foreach ($slices as $key => $slice){
//						if ($slice[value] == "OR" || $slice[value] == "AND"){
							if($slice['value'] != ""){
								if(is_numeric($slice['value'])){
									if(strstr($slice['name'], ".")){
										$dotSlice = explode(".", $slice['name']);
//echo "<div style='direction:ltr;'>dotSlice1-> ";			
//print_r($dotSlice);
//echo '<br></div>';
//////*********  FOR search between two prices
										if($dotSlice[1]==basePrice1){
											$out .= " AND `$dotSlice[0]`.`basePrice` >= $slice[value]";
										}elseif($dotSlice[1]==basePrice2){
											$out .= " AND `$dotSlice[0]`.`basePrice` <= $slice[value]";
										}else{
											$out .= " AND `$dotSlice[0]`.`$dotSlice[1]` = $slice[value]";
										}
//echo "<div style='direction:ltr;'>out1-> ";			
//print_r($out);
//echo '<br></div>';
									}else{
										$out .= " AND `$slice[name]` = $slice[value]";
//echo "<div style='direction:ltr;'>out2-> ";			
//print_r($out);
//echo '<br></div>';
									}
								}else{
									if(strstr($slice[name], ".")){
										$dotSlice = explode(".", $slice[name]);
//echo "<div style='direction:ltr;'>dotSlice11-> ";			
//print_r($dotSlice);
//echo '<br></div>';
										$out .= " AND `$dotSlice[0]`.`$dotSlice[1]` LIKE '%$slice[value]%'";
//echo "<div style='direction:ltr;'>out11-> ";			
//print_r($out);
//echo '<br></div>';
									}else{
										$out .= " AND `$slice[name]` LIKE '%$slice[value]%'";
//echo "<div style='direction:ltr;'>out21-> ";			
//print_r($out);
//echo '<br></div>';
									}
								}
							}
//						}
					}
				}
//			}else{				
//echo 55;
//echo '<br>';				
//				if(is_array($slices)){
//					foreach ($slices as $key => $slice){
//echo '<br>101<br>';						
//echo $slice[value];
//echo '<br>';					
//						if ($slice[value] == "OR" || $slice[value] == "AND"){
//echo '<br>111<br>';			
//echo 'OKKKKKK!!!!!!!';	
//echo '<br>';							
//						}else{
//							if($slice[value] != ""){
//echo '<br>222<br>';			
//echo $slice[value];	
//echo '<br>';								
//								if(is_numeric($slice[value])){
//									if(strstr($slice[name], ".")){
//										$dotSlice = explode(".", $slice[name]);
//										$out .= " OR `$dotSlice[0]`.`$dotSlice[1]` = $slice[value]";
//									}else{
//										$out .= " OR `$slice[name]` = $slice[value]";
//									}
//								}else{
//									if(strstr($slice[name], ".")){
//										$dotSlice = explode(".", $slice[name]);
//										$out .= " OR `$dotSlice[0]`.`$dotSlice[1]` LIKE '%$slice[value]%'";
//									}else{
//										$out .= " OR `$slice[name]` LIKE '%$slice[value]%'";
//									}
//								}
//							}
//						}
//					}
//				}
//			}
//echo '<br>';			
//echo $out;	
//echo '<br>';			
			return $out;
		}
		return null;
	}
	
public function filterSplit($string){
		global $settings;
		$this->debug($settings['debugFile'], "chrF", "	Function=> system.php-> filterSplit($string)\n");

		if(strstr($string, ',') || strstr($string, '=')){
			$records = explode(",", $string);
			foreach ($records as $key => $record){
				if(!empty($record)){
					$string = explode("=", $record);
					if(isset($string[1])){
						$slices[$key]['name'] = $string[0];
						$slices[$key]['value'] = $string[1];
					}
				}
			}
			
				if(is_array($slices)){
					foreach ($slices as $key => $slice){
						if($slice['value'] != ""){
							if(is_numeric($slice['value'])){
								if(strstr($slice['name'], ".")){
									$dotSlice = explode(".", $slice['name']);
									$out .= "`$dotSlice[0]`.`$dotSlice[1]` = $slice[value]";
								}else{
									$out .= "`$slice[name]` = $slice[value]";
								}
							}else{
								if(strstr($slice['name'], ".")){
									$dotSlice = explode(".", $slice['name']);
									$out .= "`$dotSlice[0]`.`$dotSlice[1]` LIKE '%$slice[value]%'";
								}else{
									$out .= "`$slice[name]` LIKE '%$slice[value]%'";
								}
							}
						}
					}
				}
		
			return $out;
		}
		return null;
	}
}
?>