<?php
Define("pageLoaderConfFile", "$settings[moduleAddress]/$sysVar[op]/config/config");
$config = pageLoaderConfFile . ".php";

if(file_exists($config)){
	require_once ($config);

}else{
	die("Page Loader sub system is down !");
}
?>