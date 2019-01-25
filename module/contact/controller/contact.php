<?php
class c_contact extends m_contact{

	public $active = 1;
	

	public function c_contact(){

	}
	
	public function c_sendMessage($userName, $email, $subject, $message, $carbonCopy) {
		$this->m_sendMessage($userName, $email, $subject, $message, $carbonCopy);
	}
	
	public function c_listMessage(){
		$this->m_listMessage();
	}
	
	public function c_showMessage($id){
		$this->m_showMessage($id);
	}

}
?>