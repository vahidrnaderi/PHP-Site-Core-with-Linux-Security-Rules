<?php

class m_mta extends masterModule{
	
	private $queue;
	private $smtp;
	private $receiver;

	private $to;
	private $subject;
	private $message;
	private $headers;
	private $from;
	private $replayTo;
	private $returnPath;
	private $cc;
	private $bcc;
	private $xMailer;
	private $xPriority;
	private $xMSMailPriority;
	private $mimeVersion;
	private $charset;
	private $contentType;
	private $boundary;
	private $attachFile;
	private $path = './attach/';
	private $maxAllowedAttachsize = 100000000;
	private $allowedExtensions = array("jpg", "jpeg", "gif", "bmp", "zip");


	function m_mta(){

	}

	public function m_attach(){

		$file = $this->path . "/" . $this->attachFile;
		$fileSize = filesize($file);
		if($fileSize < $this->maxAllowedAttachsize){
			$handle = fopen($file, "r");
			$content = fread($handle, $fileSize);
			fclose($handle);
			$content = chunk_split(base64_encode($content));
			$name = basename($file);

			$this->headers .= "MIME-Version: $this->mimeVersion\r\n";
			$this->headers .= "Content-Type: multipart/mixed; boundary=\"".$this->boundary."\"\r\n\r\n";
			$this->headers .= "This is a multi-part message in MIME format.\r\n";
			$this->headers .= "--".$this->boundary."\r\n";
			$this->headers .= "Content-type:text/html; charset=$this->charset\r\n";
			$this->headers .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
			$this->headers .= $this->message."\r\n\r\n";
			$this->headers .= "--".$this->boundary."\r\n";
			$this->headers .= "Content-Type: application/octet-stream; name=\"".$this->attachFile."\"\r\n";
			$this->headers .= "Content-Transfer-Encoding: base64\r\n";
			$this->headers .= "Content-Disposition: attachment; filename=\"".$this->attachFile."\"\r\n\r\n";
			$this->headers .= $content."\r\n\r\n";
			$this->headers .= "--".$this->boundary."--";
		}else{
			print "max size error";
		}
	}

	public function m_mail($fromEmail, $fromName, $toEmail, $toName, $subject, $message, $attachFile=null, $bcc=null, $cc=null){
		global $system, $settings, $lang;

		// Mandatory headers
		$this->xMailer = 'PHP-' . phpversion();
		$this->mimeVersion = '1.0';
		$this->charset = 'UTF-8';
		$this->boundary = md5(uniqid(time()));

		// general headers
		$this->from = "From: $fromName <$fromEmail>\r\n";
		$this->replayTo = "Repaly-To: $fromName <$fromEmail>\r\n";
		$this->returnPath = "Return-Path: $fromEmail\r\n";

		if(is_array($toEmail)){
			$toEmail = implode(", ", $toEmail);
			$this->to = "To: $toEmail\r\n";
		}else{
			$this->to = "To: $toName <$toEmail>\r\n";
		}

		if(is_array($bcc)){
			$bcc = implode(", ", $bcc);
			$this->bcc = "Bcc:$bcc\r\n";
		}else{
			$this->bcc = "Bcc:$bcc\r\n";
		}

		if(is_array($cc)){
			$cc =  implode(", ", $cc);
			$this->cc = "Cc:$cc\r\n";
		}else{
			$this->cc = "Cc:$cc\r\n";
		}

		$this->xPriority = "X-Priority: 1\r\n";
		$this->xMSMailPriority = "X-MSMail-Priority: High\r\n";
		$this->contentType = "Content-type:text/html; charset=$this->charset\r\n";
		$this->xMailer = "X-Mailer: $this->xMailer\r\n";
		$this->subject = $subject;
		$this->message = $message;
		$this->attachFile = $attachFile;
		$this->headers  = $this->from;
		$this->headers .= $this->replayTo;
		$this->headers .= $this->returnPath;
		$this->headers .= $this->to;
		$this->headers .= $this->bcc;
		$this->headers .= $this->cc;
		$this->headers .= $this->xPriority;
		$this->headers .= $this->xMSMailPriority;
		$this->headers .= $this->xMailer;

		if($this->attachFile){
			$this->m_attach();
			if (mail($toEmail, $this->subject, null, $this->headers)) {
				//				print "True(A)";
			} else {
				//				print "false(A)";
			}
		}else{
			$this->headers .= $this->contentType;
			if (mail($toEmail, $this->subject, $this->message, $this->headers)) {
				//				print "true(M)";
			} else {
				//				print "false(M)";
			}
		}
	}

	public function m_massMail($fromEmail, $fromName, $toEmail, $toName, $subject, $message, $filter=null, $attachFile=null){
		global $system, $settings, $lang;

		$filter = $system->filterSplitter($filter);

		$loadTime = time()-3600;
		$lastSendTime = $system->dbm->db->findMax($settings['massMail'], 'lastSendTime');

		if($loadTime > $lastSendTime){
			$system->dbm->db->select("`email`", $settings['massMail'], "1 $filter", "`lastSendTime` ASC", "", "", "0,9");
				
			while($row = $system->dbm->db->fetch_array()){
				$bcc[] = $row['email'];
			}
				
			//			print "------BCC:" . print_r($bcc);
				
			$this->m_mail($fromEmail, $fromName, $toEmail, $toName, $subject, $message, null, $bcc);
				
			$time = time();
			foreach ($bcc as $b){
				$system->dbm->db->update("`$settings[massMail]`", "`lastSendTime` = $time, `count` = `count`+1, `lastContent` = '$subject'", "`email` = '$b'");
			}
		}
	}





	public function m_addMtaQueue ($subject, $message, $link, $image=null, $from=null, $fromName, $attachment=null){
		global $settings, $system;

		$from = (!empty($from)) ? $from : $settings['roboMail'];
		$fromName = (!empty($fromName)) ? $fromName : $settings['roboMail'];
		$timeStamp = time();
		
		$system->dbm->db->insert("`$settings[mtaQueue]`", "`active`, `timeStamp`, `owner`, `group`, `or`, `ox`, `gr`, `gx`, `tr`, `tx`, `ur`, `tx`, `from`, `fromName`, `subject`, `image`, `message`, `link`, `attachment`, `maxCount`", "1, $timeStamp, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '$from', '$fromName', '$subject', '$image', '$message', '$link', '$attachment', 500");
	}
	
	private function m_mtaQueueFind (){
		global $settings, $system;

		$system->dbm->db->select("`id`, `from`, `fromName`, `subject`, `image`, `message`, `link`", "`$settings[mtaQueue]`", "`count` < `maxCount`", "`count` ASC", "", "", "0,1");
		$this->queue = $system->dbm->db->fetch_array();
	}
	
	private function m_mtaReceiverFind (){
		global $settings, $system;
		
		$queueId = $this->queue['id'];
		$this->receiver = mysql_fetch_array(mysql_query("SELECT `id`, `firstName`, `lastName`, `email` FROM `$settings[humanResource]` WHERE `id` NOT IN(SELECT `humanResourceId` FROM `$settings[mtaLog]` WHERE `queueId` = $queueId) ORDER BY rand() LIMIT 0,1"), MYSQL_ASSOC);
	}
	
	private function m_mtaSmtpFind (){
		global $settings, $system;

		$time = time();
		$timeOffset = $time-$settings['timeSlice'];
		$timeDead = $time-900;
		
		$system->dbm->db->update("`$settings[mtaSmtp]`", "`status` = 'live'", "`status` = 'dead' AND `lastActivity` < $timeDead");
		$system->dbm->db->select("`id`, `host`, `port`, `auth`, `userName`, `password`, `secure`", "`$settings[mtaSmtp]`", "`status` = 'live' AND `lastActivity` < $timeOffset", "`lastActivity` DESC", "", "", "0,1");
		$this->smtp = $system->dbm->db->fetch_array();
	}

	public function m_mtaSend ($file){
		global $settings, $system;

		$this->m_mtaSmtpFind();
		$this->m_mtaQueueFind();
		
		if($this->smtp && $this->queue){
			$this->m_mtaReceiverFind();
			
			$system->mail->SMTPDebug = 0;
			$system->mail->isSMTP();
			$system->mail->Host = $this->smtp['host'];
			$system->mail->Port = $this->smtp['port'];
			$system->mail->SMTPAuth = true;
			$system->mail->Username = $this->smtp['userName'];
			$system->mail->Password = $this->smtp['password'];
			$system->mail->SMTPSecure = $this->smtp['secure'];
			$system->mail->CharSet = 'utf-8';
			
			$system->mail->From = (!empty($this->queue['from'])) ? $this->queue['from'] : $settings['roboMail'];
			$system->mail->FromName = (!empty($this->queue['fromName'])) ? $this->queue['fromName'] : $settings['domainName'];
//			$system->mail->SetFrom("digiseo.ir@gmail.com");
			$system->mail->Subject = $this->queue['subject'];
			
			$system->xorg->smarty->assign("subject", $this->queue['subject']);
			$system->xorg->smarty->assign("firstName", $this->receiver['firstName']);
			$system->xorg->smarty->assign("lastName", $this->receiver['lastName']);
			$system->xorg->smarty->assign("image", $this->queue['image']);
			$system->xorg->smarty->assign("message", $this->queue['message']);
			$system->xorg->smarty->assign("link", $this->queue['link']);
			$system->mail->Body = $system->xorg->smarty->fetch($settings['moduleAddress'] . "/mta/view/tpl/$file" . $settings['ext4']);
			$system->mail->AltBody = $this->queue['message'];
			
			$system->mail->addAddress($this->receiver['email'], $this->receiver['firstName'] . ' ' . $this->receiver['lastName']);
//			$system->mail->addBCC("s.a.hosseini@gmail.com");
			$system->mail->addReplyTo($settings['roboMail']);
			$system->mail->isHTML(true);
			
			if($system->mail->send()){
				$this->m_mtaLog();
//				echo 'SSSS';
			}else{
//				echo $system->mail->ErrorInfo;
				$smtpId = $this->smtp['id'];
				$time = time();
				$system->dbm->db->update("`$settings[mtaSmtp]`", "`status` = 'dead', `lastActivity` = $time", "`id` = $smtpId");
			}
		}else{
//			echo 'Live SMTP not found.';
		}
	}

	public function m_mtaLog (){
		global $settings, $system;
		
		$time = time();
		$queueId = $this->queue['id'];
		$receiverId = $this->receiver['id'];
		$smtpId = $this->smtp['id'];
		$system->dbm->db->insert("`$settings[mtaLog]`", "`active`, `timeStamp`, `owner`, `group`, `or`, `ox`, `queueId`, `humanResourceId`", "1, $time, 1, 1, 1, 1, $queueId, $receiverId");
		$system->dbm->db->update("`$settings[mtaQueue]`", "`count` = `count`+1", "`id` = $queueId");
		$system->dbm->db->update("`$settings[mtaSmtp]`", "`lastActivity` = $time", "`id` = $smtpId");
	}

}

?>