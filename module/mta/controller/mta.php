<?php
class c_mta extends m_mta{

	public $active = 1;


	function c_mta(){
		
	}
	
	public function c_sendMail($from, $fromName, $to, $bcc, $toName, $subject, $text, $html=null, $attmFiles=null){
		$this->m_sendMail($from, $fromName, $to, $bcc, $toName, $subject, $text, $html, $attmFiles);
	}
	
	public function c_mail($fromEmail, $fromName, $toEmail, $toName, $subject, $message, $attachFile=null, $bcc=null, $cc=null) {
		$this->m_mail($fromEmail, $fromName, $toEmail, $toName, $subject, $message, $attachFile, $bcc, $cc);
	}
	
	public function c_massMail($fromEmail, $fromName, $toEmail, $toName, $subject, $message, $filter=null, $attachFile=null){
		$this->m_massMail($fromEmail, $fromName, $toEmail, $toName, $subject, $message, $filter, $attachFile);
	}

}
?>