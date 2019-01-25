<?php
Define("cPanelConfFile", "$settings[moduleAddress]/$sysVar[op]/config/config");
$config = cPanelConfFile . ".php";

if(file_exists($config)){
	require_once ($config);

	/* Model */
	$subSystem = $settings['moduleAddress'] . "/" . $settings['moduleName'] . "/" . $settings['modelAddress'] . "/" . $settings['moduleName'] . $settings['ext2'];
	if(file_exists($subSystem)){
		require_once ($subSystem);
		$m_cPanel = new m_cPanel();
		$system->run($subSystem, 'On');
	}else{
		$system->run($subSystem, 'Off');
	}

	/* Controller */
	$subSystem = $settings['moduleAddress'] . "/" . $settings['moduleName'] . "/" . $settings['moduleController'] . "/" . $settings['moduleName'] . $settings['ext2'];
	if(file_exists($subSystem)){
		require_once ($subSystem);
		$c_cPanel = new c_cPanel();
		$system->run($subSystem, 'On');
	}else{
		$system->run($subSystem, 'Off');
	}


}else{
	die("Slider sub system is down !");
}
?>