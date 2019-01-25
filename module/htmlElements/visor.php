<?php
Define("htmlElementsConfFile", "$settings[moduleAddress]/$sysVar[op]/config/config");
$config = htmlElementsConfFile . ".php";

if(file_exists($config)){
	require_once ($config);

	/* Model */
	$subSystem = $settings['moduleAddress'] . "/" . $settings['moduleName'] . "/" . $settings['modelAddress'] . "/" . $settings['moduleName'] . $settings['ext2'];
	if(file_exists($subSystem)){
		require_once ($subSystem);
		$m_htmlElements = new m_htmlElements();
		$system->run($subSystem, 'On');
	}else{
		$system->run($subSystem, 'Off');
	}

	/* Controller */
	$subSystem = $settings['moduleAddress'] . "/" . $settings['moduleName'] . "/" . $settings['moduleController'] . "/" . $settings['moduleName'] . $settings['ext2'];
	if(file_exists($subSystem)){
		require_once ($subSystem);
		$c_htmlElements = new c_htmlElements();
		$system->run($subSystem, 'On');
	}else{
		$system->run($subSystem, 'Off');
	}


}else{
	die("Hierarchy sub system is down !");
}
?>