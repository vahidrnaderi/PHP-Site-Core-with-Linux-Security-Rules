<?php
Define("watchDogConfFile", "$settings[moduleAddress]/$sysVar[op]/config/config");
$config = watchDogConfFile . ".php";

if(file_exists($config)){
	require_once ($config);

	/* Model */
	$subSystem = $settings['moduleAddress'] . "/" . $settings['moduleName'] . "/" . $settings['modelAddress'] . "/" . $settings['moduleName'] . $settings['ext2'];
	if(file_exists($subSystem)){
		require_once ($subSystem);
		$m_watchDog = new m_watchDog();
		$system->run($subSystem, 'On');
	}else{
		$system->run($subSystem, 'Off');
	}

	/* Controller */
	$subSystem = $settings['moduleAddress'] . "/" . $settings['moduleName'] . "/" . $settings['moduleController'] . "/" . $settings['moduleName'] . $settings['ext2'];
	if(file_exists($subSystem)){
		require_once ($subSystem);
		$c_watchDog = new c_watchDog();
		$system->run($subSystem, 'On');
	}else{
		$system->run($subSystem, 'Off');
	}


}else{
	die("watchDog sub system is down !");
}
?>