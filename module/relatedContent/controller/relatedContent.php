<?php
class c_relatedContent extends m_relatedContent{

	public $active = 1;
	
	function c_relatedContent(){
		
	}
		
	###########################
	# Object (content)        #
	###########################
	// Related Content
	public function c_relatedURL($title){
		
		return $this->m_relatedURL($title);
	}
	
	// Related Keyword
	public function c_relatedKeyword(){
		
		$this->m_relatedKeyword();
	}
}
?>