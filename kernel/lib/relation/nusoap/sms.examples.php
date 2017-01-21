<?php

require_once('sms.class.php');

	$uname = 'USERNAME'; // Your panel username
	$pass  = 'PASSWORD'; // Your panel password

	$gate = new sms_soap($uname, $pass);
	
	// Get user credit balance
	$resp = $gate->GetUserBalance();

	print_r($resp);
	
	// Single Send
	$resp = $gate->SendSMS('يک نمونه است', '+983000xxxx', '+989123456789', 'normal');
	
	print_r($resp);
	
	
	// Multiple Recieptors Send
	$resp = $gate->SendSMS('يک نمونه است', '+981000xxxx', array('+989123456789', '+989123456777'), 'normal');

	print_r($resp);
	
	
	// Multiple Messages & Recieptors Send
	$resp = $gate->SendSMS(array('يک نمونه است', 'این نمونه دو است'), '+9830006441', array('+989123456789', '+989123456777'), 'normal');

	print_r($resp);

	
	// Wap push Send
	
	$url   = 'www.google.com'; // Doesnt need http://
	$title = 'Google Search Engine';
	
	$resp = $gate->SendSMS("\n".$title."\n".$url, '+983000xxxx', array('+989123456789', '+989123456777'), 'wap');

	print_r($resp);
	
		// You cannot send multiple Wap messages to multiple recieptors,
		// you can only send a single wap message to multiple recieptors

	// Flash Single Send
	
	$resp = $gate->SendSMS('این یک پیام فوری است', '+983000xxxx', '+989123456789', 'flash');

	print_r($resp);
	
	
	// Flash Multiple Send
	
	$resp = $gate->SendSMS(array('این یک پیام فوری است', 'این پیام فوری دوم است'), '+983000xxxx', array('+989123456789', '+989123456777'), 'flash');

	print_r($resp);
	
	
?>