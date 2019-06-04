<?php
class acl extends system{

	public $table = "access";

	function acl(){
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> acl.php-> acl()\n");

		$this->table = $this->tablePrefix . $this->table;
	}

	public function access($filter, $pmod=null){
		global $system, $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> acl.php-> access($filter, $pmod)\n");

		if(!empty($this->table)){
//			$system->dbm->db->select("`owner`, `group`, `mor`, `mow`, `mox`, `mgr`, `mgw`, `mgx`, `mtr`, `mtw`, `mtx`, `mur`, `muw`, `mux`", "`$this->table`", $filter);
//			print "SELECT `owner`, `group`, `mor`, `mow`, `mox`, `mgr`, `mgw`, `mgx`, `mtr`, `mtw`, `mtx`, `mur`, `muw`, `mux` FROM `$this->table` WHERE $filter";
		    $acc = mysqli_fetch_array(mysqli_query($system->dbm->db->dbhandler, "SELECT `owner`, `group`, `mor`, `mow`, `mox`, `mgr`, `mgw`, `mgx`, `mtr`, `mtw`, `mtx`, `mur`, `muw`, `mux` FROM `$this->table` WHERE $filter"));
//			$acc = $system->dbm->db->fetch_array();
//			print_r($acc);
//			print "<br>";
//			print "<br>Owner=$acc[owner]";
//			print "<br>Group=$acc[group]";
//			print "<br>Uid=$_SESSION[uid]";
//			print "<br>Gid=$_SESSION[gid]";
			if($acc[owner] == $_SESSION[uid]){
//				print "ACC1";
				switch($pmod){
					default:
					case "---":
//						print 1;
						return false;
						break;
					case "r--":
//						print 2;
						return ($acc['mor'] == 1 ? true : false);
						break;
					case "rw-":
//						print 3;
						return ($acc['mor'] == 1 && $acc['mow'] == 1 ? true : false);
						break;
					case "r-x":
//						print 4;
						return ($acc['mor'] == 1 && $acc['mox'] == 1 ? true : false);
						break;
					case "rwx":
//						print 5;
						return ($acc['mor'] == 1 && $acc['mow'] == 1 && $acc['mox'] == 1 ? true : false);
						break;
				}
			}elseif(in_array($acc['group'], explode(',', $_SESSION['gid']))){
//				print "ACC2";
				switch($pmod){
					default:
					case "---":
//						print 6;
						return false;
						break;
					case "r--":
//						print 7;
						return ($acc['mgr'] == 1 ? true : false);
						break;
					case "rw-":
//						print 8;
						return ($acc['mgr'] == 1 && $acc['mgw'] == 1 ? true : false);
						break;
					case "r-x":
//						print 9;
						return ($acc['mgr'] == 1 && $acc['mgx'] == 1 ? true : false);
						break;
					case "rwx":
//						print 10;
						return ($acc['mgr'] == 1 && $acc['mgw'] == 1 && $acc['mgx'] == 1 ? true : false);
						break;
				}
			}elseif($_SESSION['uid'] != 2 && $_SESSION['gid'] != 3){
//				print "ACC3";
				switch($pmod){
					default:
					case "---":
//						print 11;
						return false;
						break;
					case "r--":
//						print 12;
						return ($acc['mtr'] == 1 ? true : false);
						break;
					case "rw-":
//						print 13;
						return ($acc['mtr'] == 1 && $acc['mtw'] == 1 ? true : false);
						break;
					case "r-x":
//						print 14;
						return ($acc['mtr'] == 1 && $acc['mtx'] == 1 ? true : false);
						break;
					case "rwx":
//						print 15;
						return ($acc['mtr'] == 1 && $acc['mtw'] == 1 && $acc['mtx'] == 1 ? true : false);
						break;
				}
			}elseif($_SESSION['uid'] == 2 && $_SESSION['gid'] == 3){
//				print "ACC4";
				switch($pmod){
					default:
					case "---":
//						print 16;
						return false;
						break;
					case "r--":
//						print 17;
						return ($acc['mur'] == 1 ? true : false);
						break;
					case "rw-":
//						print 18;
						return ($acc['mur'] == 1 && $acc['muw'] == 1 ? true : false);
						break;
					case "r-x":
//						print 19;
						return ($acc['mur'] == 1 && $acc['mux'] == 1 ? true : false);
						break;
					case "rwx":
//						print 20;
						return ($acc['mur'] == 1 && $acc['muw'] == 1 && $acc['mux'] == 1 ? true : false);
						break;
				}
			}

		}else{
//			print "ACC5";
//			print 21;
			return false;
		}
	}
}
?>