<?php
class dbm extends system{

	public $db;
	public $host;
	public $name;
	public $pass;
	public $type;
	public $user;

	public function dbm($type, $host, $user, $pass, $name){
		global $settings;

		$this->host = $host;
		$this->name = $name;
		$this->pass = $pass;
		$this->type = $type;
		$this->user = $user;

		/* Mysql sub system */
		$subSystem = $settings['libraryAddress'] . "/dbm/mysql/" . "mysql" . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->db = new mysql($this->host, $this->user, $this->pass, $this->name);
		}else{
			$this->run($subSystem, 'Off');
		}

		switch($type){
			case "mssql":
				/* Mssql sub system */
				$subSystem = $settings['libraryAddress'] . "/dbm/mssql/" . "mssql" . $settings['ext2'];
				if(file_exists($subSystem)){
					$this->run($subSystem, 'On');
					$this->db = new mssql();
				}else{
					$this->run($subSystem, 'Off');
				}
				break;
			case "mysql":
				/* Mysql sub system */
				$subSystem = $settings['libraryAddress'] . "/dbm/mysql/" . "mysql" . $settings['ext2'];
				if(file_exists($subSystem)){
					$this->run($subSystem, 'On');
					$this->db = new mysql($this->host, $this->user, $this->pass, $this->name);
				}else{
					$this->run($subSystem, 'Off');
				}
				break;
			case "mysqli":
				/* Mysqli sub system */
				$subSystem = $settings['libraryAddress'] . "/dbm/mysqli/" . "mysqli" . $settings['ext2'];
				if(file_exists($subSystem)){
					$this->run($subSystem, 'On');
					$this->db = new mysqli();
				}else{
					$this->run($subSystem, 'Off');
				}
				break;
			case "pgsql":
				/* Pgsql sub system */
				$subSystem = $settings['libraryAddress'] . "/dbm/pgsql/" . "pgsql" . $settings['ext2'];
				if(file_exists($subSystem)){
					$this->run($subSystem, 'On');
					$this->db = new pgsql();
				}else{
					$this->run($subSystem, 'Off');
				}
				break;
			case "oracle":
				/* Oracle sub system */
				$subSystem = $settings['libraryAddress'] . "/dbm/oracle/" . "oracle" . $settings['ext2'];
				if(file_exists($subSystem)){
					$this->run($subSystem, 'On');
					$this->db = new oracle();
				}else{
					$this->run($subSystem, 'Off');
				}
				break;
			case "sybase":
				/* Sybase sub system */
				$subSystem = $settings['libraryAddress'] . "/dbm/sybase/" . "sybase" . $settings['ext2'];
				if(file_exists($subSystem)){
					$this->run($subSystem, 'On');
					$this->db = new sybase();
				}else{
					$this->run($subSystem, 'Off');
				}
				break;
		}
	}
}
?>