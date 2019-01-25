<?php
Define("contactConfFile","$settings[moduleAddress]/$sysVar[op]/config/config");
$config = contactConfFile . ".php";

if(file_exists($config)){
	require_once ($config);

	/* Model */
	$subSystem = $settings['moduleAddress'] . "/" . $settings['moduleName'] . "/" . $settings['modelAddress'] . "/" . $settings['moduleName'] . $settings['ext2'];
	if(file_exists($subSystem)){
		require_once ($subSystem);
		$m_contact = new m_contact();
		$system->run($subSystem, 'On');
	}else{
		$system->run($subSystem, 'Off');
	}

	/* Controller */
	$subSystem = $settings['moduleAddress'] . "/" . $settings['moduleName'] . "/" . $settings['moduleController'] . "/" . $settings['moduleName'] . $settings['ext2'];
	if(file_exists($subSystem)){
		require_once ($subSystem);
		$c_contact = new c_contact();
		$system->run($subSystem, 'On');
	}else{
		$system->run($subSystem, 'Off');
	}


}else{
	die("<br>Contact sub system is down !<br>");
}
?>