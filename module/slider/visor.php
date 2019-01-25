<?php
Define("sliderConfFile", "$settings[moduleAddress]/$sysVar[op]/config/config");
$config = sliderConfFile . ".php";

if(file_exists($config)){
	require_once ($config);

	/* Model */
	$subSystem = $settings['moduleAddress'] . "/" . $settings['moduleName'] . "/" . $settings['modelAddress'] . "/" . $settings['moduleName'] . $settings['ext2'];
	if(file_exists($subSystem)){
		require_once ($subSystem);
		$m_slider = new m_slider();
		$system->run($subSystem, 'On');
	}else{
		$system->run($subSystem, 'Off');
	}

	/* Controller */
	$subSystem = $settings['moduleAddress'] . "/" . $settings['moduleName'] . "/" . $settings['moduleController'] . "/" . $settings['moduleName'] . $settings['ext2'];
	if(file_exists($subSystem)){
		require_once ($subSystem);
		$c_slider = new c_slider();
		$system->run($subSystem, 'On');
	}else{
		$system->run($subSystem, 'Off');
	}


}else{
	die("Slider sub system is down !");
}
?>