<?php
Define("confFile","config/config");
$config = confFile . ".php";
$settings['domain'] = $domain = preg_replace("/^(.*\.)?([^.]*\..*)$/", "$2", $_SERVER['HTTP_HOST']);

if(file_exists($config)){
	require_once ($config);

	//	if($domain == $settings['domainName']){
	/* Base system */
	$subSystem = $settings['controllerAddress'] . "/system" . $settings['ext2'];
	if(file_exists($subSystem)){
		require_once ($subSystem);
		system::debug($settings['debugFile'], "str", "\n--------------------------------- \n Start of Loading Site. \n ---------------------------------\n");
		$system = new system();
		$system->run($subSystem, 'On');
	}else{
		$system->run($subSystem, 'Off');
	}

	/* License sub library */
	/*$subSystem = $settings['libraryAddress'] . "/security/license/" . "license" . $settings['ext2'];
	if(file_exists($subSystem)){
		$system->run($subSystem, 'On');
		$system->license = new license();
	}else{
		$system->run($subSystem, 'Off');
	}*/

	/* Master module */
	$subSystem = $settings['moduleAddress'] . "/masterModule" . $settings['ext2'];
	if(file_exists($subSystem)){
		$system->run($subSystem, 'On');
	}else{
		$system->run($subSystem, 'Off');
	}

	//	}else{
	//		/* Base system */
	//		$subSystem = $settings[controllerAddress] . "/system" . $settings[ext2];
	//		if(file_exists($subSystem)){
	//			require_once ($subSystem);;
	//			$system = new system();
	//			$system->run($subSystem, 'On');
	//		}else{
	//			$system->run($subSystem, 'Off');
	//		}
	//		die("<center>$lang[licenseError]</center>");
	//	}
}else{
	$system->debug($settings['debugFile'], "err", "Else=> superVisor.php-> Config file not exist\n");
	die("The system is down !");
}
?>
