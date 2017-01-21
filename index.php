<?php
ob_start("ob_gzhandler");
define("superVisor", "kernel/controller/superVisor");
if(file_exists(superVisor . ".php"))
require_once(superVisor . ".php");
////echo "<br> **** 11 **** <br>";
//if($_SERVER['HTTP_REFERER'] == '' || in_array($_SERVER['HTTP_REFERER'], $system->security->trustUrl->trustUrlList())){
$system->security->session->manager();

$settings['domain'] = $domain = preg_replace("/^(.*\.)?([^.]*\..*)$/", "$2", $_SERVER['HTTP_HOST']);
////echo "<br> **** 12 **** <br>";
$system->xorg->smarty->assign("settings", $settings);
////echo "<br> **** 13 **** <br>";
$system->xorg->smarty->assign("lang", $lang = $system->lang->langMan());
////echo "<br> **** 14 **** <br>";
$system->xorg->smarty->assign("sysVar", $sysVar);
////echo "<br> **** 15 **** <br>";

$system->module->loadModule();
////echo "<br> **** 16 **** <br>";
$content = $system->xorg->smarty->fetchedVar;
////echo "<br> **** 17 **** <br>";
$system->seo->seo($content);
////echo "<br> **** 18 **** <br>";
//$system->seo->scan();
//require_once "module/relatedContent/model/relatedContent.php";
//$relatedContent = new m_relatedContent();
//$relatedContent->m_relatedURL($system->seo->titleMaker());
//echo "Title: " . $system->seo->titleMaker() . "<br>";
$content = $content/* . $relatedContent->m_relatedURL($system->seo->titleMaker())*/;
//echo $relatedContent->m_relatedURL($system->seo->titleMaker());
//print_r ($settings);
if($_SERVER["HTTP_X_REQUESTED_WITH"] == 'XMLHttpRequest'){
	echo $content;
}else{
////	echo $content;
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