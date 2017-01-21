<?php
class c_seo extends m_seo{

	public $active = 1;
	
	function c_seo(){
		
	}
		
	###########################
	# Object (words)          #
	###########################
	// List Object
	public function c_listObject($viewMode, $filter = null){
		
		$this->m_listObject($viewMode, $filter);
	}
	
	public function c_sitemapGenerate($url){
		
		$this->m_sitemapGenerate($url);
	}

}
?>