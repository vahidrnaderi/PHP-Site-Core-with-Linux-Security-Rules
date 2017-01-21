<?php

class module extends system{

	public $table = "module";

	function module(){

		$this->table = $this->tablePrefix . $this->table;
	}

	public function loadModule(){
		global $system, $settings, $lang, $sysVar;
		
		$sysVar[op] = empty($_GET[op]) ? $settings['defaultOp'] : $_GET[op];
		$sysVar[mode] = empty($_GET[mode]) ? $settings['defaultMode'] : $_GET[mode];
		
//		print "GOP: $_GET[op]<br>";
//		print "GMode: $_GET[mode]<br>";
//		
//		print "DOP: $settings[defaultOp]<br>";
//		print "DMode: $settings[defaultMode]<br>";
//		
//		print "OP: $sysVar[op]<br>";
//		print "Mode: $sysVar[mode]<br>";
				
		//if(in_array($_SERVER['HTTP_HOST'], $system->security->trustUrl->trustUrlList())){
		$sysVar[secure] = $system->security->validate->chControl();
			if($sysVar[op]){
				if($system->security->acl->access("`op` = '$sysVar[op]' AND `mode` = '$sysVar[mode]' AND `active` = '1'", 'r-x')){
					if(file_exists($settings['moduleAddress'] . "/" . $sysVar[op] . "/" . "index" . $settings['ext2']) && file_exists($settings['moduleAddress'] . "/" . $sysVar[op] . "/" . "visor" . $settings['ext2'])){
						require_once($settings['moduleAddress'] . "/" . $sysVar[op] . "/" . "visor" . $settings['ext2']);
						$system->xorg->smarty->assign("settings", $settings);
						require_once($settings['moduleAddress'] . "/" . $sysVar[op] . "/" . "index" . $settings['ext2']);
					}else{
						$system->xorg->smarty->fetchedVar = $system->xorg->smarty->fetch($settings['commonTpl'] . "404" . $settings['ext4']);
					}
				}else{
					$system->watchDog->exception("w", $lang[securityWarning], $lang[accDeny]);
				}
			}else{
				$system->xorg->smarty->fetchedVar = $system->xorg->smarty->fetch($settings['customiseTpl'] . "main" . $settings['ext4']);
			}
		/*else{
			print "here";
		}*/
		//}else{
		//	print $system->watchDog->exception("w", $lang[securityWarning], $lang[accDeny]);
		//}
	}

}

?>