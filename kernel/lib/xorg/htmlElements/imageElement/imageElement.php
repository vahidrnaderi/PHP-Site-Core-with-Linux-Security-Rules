<?php 

class imageElement extends htmlElements{
	
	function imageElement(){
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> imageElement.php-> imageElement()\n");
		
	}
	
	public function thumbnailLocator($imageAddress){
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> imageElement.php-> thumbnailLocator($imageAddress)\n");
		
		return str_replace("/images/", "/_thumbs/Images/", $imageAddress);
	}
	
}

?>