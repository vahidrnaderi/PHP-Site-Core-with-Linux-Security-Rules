<?php

if(file_exists(visor . ".php"))
require_once(visor . ".php");

switch ($sysVar[mode]){
	case "v_sendMail":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> mta Module >> index.php-> v_sendMail\n");
		
		$system->xorg->smarty->display("theme/$settings[theme]/tpl/pages/order" . $settings['ext4']);
		break;
	case "c_sendMail":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> mta Module >> index.php-> c_sendMail\n");
		
		$to = "";//"behingroup@yahoo.com";
		$bcc = "vahidrnaderi@idealmart.com";//"s.a.hosseini@gmail.com";
		$toName = "";//"Behin Taraz";
		$subject = "درخواست محصول";
		$text = $system->xorg->smarty->display("$settings[templateDir]/$_POST[mailText]" . $settings['ext4']);
		mail($to, $subject, $text, "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\nFrom: $toName <$toName>\nX-Mailer: PHP/" . phpversion());
		$system->watchDog->exception("s", $lang[messageSent], sprintf($lang[successfulDone], $lang[messageSent], ""));
		//		$c_mta->c_sendMail($_POST[email], $_POST[name], $to, $bcc, $toName, $subject, $text, $html, $attmFiles);
		braek;
	case "c_massMail":
		$c_mta->c_massMail($settings['adminMail'], $settings['domainName'], $settings['infoMail'], $settings['domainName'], "Test1", $system->xorg->smarty->fetch($settings['templateDir'] . "pages/massMail" . $settings['ext4']));
		break;
	default:
		$system->xorg->smarty->display($settings['commonTpl'] . "404" . $settings['ext4']);
		break;
}

?>