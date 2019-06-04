<?php
ob_start("ob_gzhandler");
define("superVisor", "kernel/controller/superVisor");
date_default_timezone_set('Asia/Tehran');
if(file_exists(superVisor . ".php"))
require_once(superVisor . ".php");
$system->security->session->manager();

$settings['domain'] = $domain = preg_replace("/^(.*\.)?([^.]*\..*)$/", "$2", $_SERVER['HTTP_HOST']);
$system->xorg->smarty->assign("settings", $settings);
$system->xorg->smarty->assign("lang", $lang = $system->lang->langMan());
$system->xorg->smarty->assign("sysVar", $sysVar);

$system->module->loadModule();
$content = $system->xorg->smarty->fetchedVar;
$system->seo->seo($content);
$system->seo->scan();
require_once "module/relatedContent/model/relatedContent.php";
$relatedContent = new m_relatedContent();
$relatedContent->m_relatedURL($system->seo->titleMaker());
//echo "Title: " . $system->seo->titleMaker() . "<br>";

$content = $content	/* . $relatedContent->m_relatedURL($system->seo->titleMaker())*/;

//echo $relatedContent->m_relatedURL($system->seo->titleMaker());
if($_SERVER["HTTP_X_REQUESTED_WITH"] == 'XMLHttpRequest'){
	echo $content;
}else{
	if(file_exists("theme/$settings[theme]")){
		require_once "theme/$settings[theme]/env/env" . $settings['ext2'];
		echo $system->xorg->smarty->fetch($settings['commonTpl'] . 'main' . $settings['ext4']);
	}else{
		echo "Your theme not exist";
	}
}
//}else{
//	echo "ATU: Anti Trust Url!";
//}
//echo "Using ", memory_get_peak_usage(1)/1024, " KB of ram<br>";
?>