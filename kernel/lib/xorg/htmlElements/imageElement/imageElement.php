<?php 

class imageElement extends htmlElements{
	
	function imageElement(){
		
	}
	
	public function thumbnailLocator($imageAddress){
		
		return str_replace("/images/", "/_thumbs/Images/", $imageAddress);
	}
	
}

?>