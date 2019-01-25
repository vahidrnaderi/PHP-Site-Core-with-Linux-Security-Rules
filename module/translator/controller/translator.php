<?php
class c_translator extends m_translator{

	public $active = 1;


	function c_translator(){

	}

	public function c_addPhrase($langCode, $code, $translate){
		$this->m_addPhrase($langCode, $code, $translate);
	}
	
	public function c_listPhrase($filter=null){

		$this->m_listPhrase($filter);
	}

	public function c_editPhrase($langId, $langCode, $code, $translate){
		global $system, $lang, $settings;

		if($system->dbm->db->count_records("`$this->langTable`", "`langCode` = '$langCode' AND `code` = '$code'") > 0){
			$this->m_editPhrase($langId, $translate);
		}else{
			$this->m_addPhrase($langCode, $code, $translate);
		}
	}

	public function c_delPhrase($id, $phrase=null){

		$this->m_delPhrase($id, $phrase);
	}

}
?>