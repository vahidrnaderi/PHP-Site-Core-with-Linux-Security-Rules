<?php
################################
# Smarty Debug in localhost    #
################################
// set path to Smarty directory *nix style
define('SMARTY_DIR', '/home/vahid/public_html/m.idealmartir/kernel/lib/xorg/smarty/');

// shows debug console only on localhost ie
// http://localhost/script.php?foo=bar&SMARTY_DEBUG
//$smarty->$debug_tpl= 'debug.tpl';
//$smarty->debugging = true; // the default is false
//$smarty->debugging_ctrl = ($_SERVER['SERVER_NAME'] == 'm.idealMartir2') ? 'URL' : 'NONE';

################################
# language                    #
################################
$system->xorg->smarty->assign("defLanguage", 'fa');

################################
# Keywords                     #
################################
require_once "module/keywords/model/keywords.php";
$keywords = new m_keywords();
$system->xorg->smarty->assign("jQCloud", $keywords->m_listObject('showList'));

################################
# Calendar           ???       #
################################
$system->xorg->smarty->assign("dateJalali", $system->time->iCal->dator(time(), 2, 'jalali'));
$system->xorg->smarty->assign("dateGregorian", 'date("j F Y")');

################################
# showTime          ???       #
################################
$system->xorg->smarty->assign("showTimeFile1", $system->xorg->smarty->fetch($settings['commonTpl'] . "showTimeFile1". $settings['ext2']));
$system->xorg->smarty->assign("showTimeFile", $system->xorg->smarty->fetch($settings['commonTpl'] . "showTimeFile". $settings['ext4']));

################################
# Google Analytic              #
################################
//$system->xorg->smarty->assign("googleAnalytic", $system->xorg->smarty->fetch($settings[customiseTpl] . "googleAnalytic". $settings['ext4']));

################################
# Login                        #
################################
require_once "module/userMan/model/userMan.php";
$userMan = new m_userMan();
$system->xorg->smarty->assign("login", $userMan->m_loginContent(), TRUE);
$system->xorg->smarty->assign("loginTitle", $userMan->m_loginContentTitle(), TRUE);

################################
# loginGate                    #
################################
$system->xorg->smarty->assign("loginGate", $system->xorg->smarty->fetch($settings['customiseTpl'] . 'loginGate' . $settings['ext4']));

################################
# Main                         #
################################
$thumbnail = $system->seo->thumbnail();
if(strstr($thumbnail, 'http')){
	$system->xorg->smarty->assign("thumbnail", $thumbnail);
}else{
	$system->xorg->smarty->assign("thumbnail", 'http://' . $settings['domainName'] . '/' . $thumbnail);
}

//echo "<br> **** env.php line 173 --> 2 **** <br>";
$system->xorg->smarty->assign("keywords", $system->seo->metaKeywordMaker('show'));
$system->xorg->smarty->assign("description", $system->seo->metaDescriptionMaker());
$system->xorg->smarty->assign("title", $settings['siteName'] . ' - ' . $system->seo->titleMaker('best'));
$system->xorg->smarty->assign("mainContents", $content, TRUE);

################################
# Contact Form                 #
################################
$system->xorg->smarty->assign("contactUs", $system->xorg->smarty->fetch($settings['customiseTpl'] . 'sendMessage' . $settings['ext4']));

################################
# Copyright                    #
################################
$system->xorg->smarty->assign("copyright", $system->xorg->smarty->fetch($settings['commonTpl'] . 'copyright' . $settings['ext4']));


################################
# Browser                      #
################################
$browser = $system->utility->browserDetector->whatBrowser();
$sysVar[browserType] = $browser[browsertype];
$sysVar[browserVersion] = $browser[version];
$sysVar[platform] = $browser[platform];


$system->logger->logIt("lod", "The site loaded");
?>