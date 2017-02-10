<?php

class license extends system{

	function license(){
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> license.php-> license()\n");
		
		$url = "http://localhost/costumers/$settings[domainName]";
		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		$result = curl_exec($curl);
		$ret = false;
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ($statusCode == 200) {

		}else{
			system::debug($settings['debugFile'], "err", "	Function=> license.php-> license()-- license not found.\n");
			die('Error');
		}
		curl_close($curl);
	}

}

?>