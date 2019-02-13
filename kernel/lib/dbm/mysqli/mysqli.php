<?php
/**
 * MysqliDb Class
 *
 * @category  Database Access
 * @package   MysqliDb
 * @author    Jeffery Way <jeffrey@jeffrey-way.com>
 * @author    Josh Campbell <jcampbell@ajillion.com>
 * @author    Alexander V. Butenko <a.butenka@gmail.com>
 * @copyright Copyright (c) 2010-2016
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      http://github.com/joshcam/PHP-MySQLi-Database-Class 
 * @version   2.8-master
 */


//MysqliDb
class mysqliDB
{

    //#################################################### mysql class declaration ##############################################################
    public $dbhandler;
    public $db_result;
    public $db_affected_rows;
    
    public $saved_results=array();
    public $results_saved=0;
    
    
//     public $host;
//     public $ip;
//     public $reffer;
//     public $agent;
    
    
    //###################################################################################################################
    
    //######################################################## mysql class #####################################################################
    
    
    
       
    
    public function mysqliDB($host, $user, $passwd, $db, $port=NULL, $socket=NULL, $create=""){
        global $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> mysqliDB($host, $user, $passwd, $db, $port, $socket, $create)\n");
        
        $this->db_name=$db;
        $this->db_user=$user;
        $this->db_passwd=$passwd;
        $this->db_host=$host;
//         $this->db_link_ptr=NULL;

        
        $this->dbhandler=@mysqli_connect($host,$user,$passwd,$db,$port,$socket);
//         $this->db_link_ptr=@mysqli_connect($host,$user,$passwd,$db) or $this->error("",mysqli_error($this->dbhandler),mysqli_errno($this->dbhandler));
        if (mysqli_connect_errno())
        {
            $this->error("",mysqli_error($this->dbhandler),mysqli_errno($this->dbhandler));
//             $this->error("",mysqli_connect_error(),mysqli_connect_errno());
//             echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
//        $this->dbhandler=@mysqli_select_db($db);
       
        if (!$this->dbhandler) {
            if ($create=="1")  {
                // Create database
                $sql = "CREATE DATABASE $db";
                if (!$this->dbhandler->query($sql) === TRUE) {
//                    echo "Error creating database: " . $conn->error;
                    $this->error("impossible to create the database.",mysqli_error($this->dbhandler),mysqli_errno($this->dbhandler));
                }
//                @mysqli_create_db($db,$this->db_link_ptr) or $this->error("imposible crear la base de datos.",mysqli_error($this->dbhandler),mysqli_errno($this->dbhandler));;
//                 $this->dbhandler=@mysqli_select_db($this->dbhandler, $db);
            }
        }
        mysqli_set_charset($this->dbhandler, 'utf8');
        
//         $this->ip = mysqli_real_escape_string($this->dbhandler, $_SERVER['REMOTE_ADDR']);
//         $this->host = mysqli_real_escape_string($this->dbhandler, gethostbyaddr($_SERVER['REMOTE_ADDR']));
//         $this->reffer = mysqli_real_escape_string($this->dbhandler, $_SERVER['HTTP_REFERER']);
//         $this->agent = mysqli_real_escape_string($this->dbhandler, $_SERVER['HTTP_USER_AGENT']);
        
        
    }
   
    public function error($where="",$error,$errno) {
        global $system, $lang, $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> error($where,$error,$errno)\n");
        
        //		die($system->watchDog->exception("e", "$lang[dataBaseProblem] $errno", mysqli_real_escape_string("$error at $where")));
        echo "$where<br>";
        die($error."<br>".$errno);
        die();
    }
    
    public function error_msg() {
        global $settings;
        $error_msg = mysqli_error($this->dbhandler);
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> error_msg() = $error_msg\n");
        return $error_msg;
    }
    
    public function PushResults() {
        global $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> PushResults()\n");
        $this->saved_results[$this->results_saved]=array($this->db_result,$this->db_affected_rows);
        $this->results_saved++;
    }
    
    public function PopResults() {
        global $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> PopResults()\n");
        $this->results_saved--;
        $this->db_result=$this->saved_results[$this->results_saved][0];
        $this->db_affected_rows=$this->saved_results[$this->results_saved][1];
    }
    
    public function reselect_db($db){
        global $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> reselect_db($db)\n");
        $this->dbhandler=@mysqli_select_db($this->dbhandler, $db);
    }
    
    public function closeDB() {
        global $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> closeDB()\n");
        @mysqli_close($this->dbhandler);
    }
    
    public function create_table($tblName,$tblStruct) {
        global $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> create_table($tblName,$tblStruct)\n");
        if (is_array($tblStruct)) $theStruct=implode(",",$tblStruct); else $theStruct=$tblStruct;
        @mysqli_query("create table $tblName ($theStruct)") or $this->error("create table $tblName ($theStruct)",mysqli_error($this->dbhandler),mysqli_errno($this->dbhandler));
    }
    
    public function drop_table($tblName) {
        global $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> drop_table($tblName)\n");
        @mysqli_query("drop table if exists $tblName") or $this->error("drop table $tblName",mysqli_error($this->dbhandler),mysqli_errno($this->dbhandler));
    }
    
    public function raw_query($sql_stat) {
        global $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> raw_query($sql_stat)\n");
        $this->db_result=@mysqli_query($this->dbhandler, $sql_stat) or $this->error($sql_stat,mysqli_error($this->dbhandler),mysqli_errno($this->dbhandler));
        $this->db_affected_rows=@mysqli_num_rows($this->db_result);
    }
    
    
    public function mysqli_db_result($link,$recno,$field) {
        global $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> mysqli_db_result(link->".print_r($link,true).",$recno,$field)\n");
    
        if(/*$result*/$link->num_rows==0) return 'unknown';
            $link->data_seek($row);
            $ceva=$link->fetch_assoc();
            $rasp=$ceva[$field];
            return $rasp;
    }
    
    
    public function count_records($table,$filter="") {
        global $settings;
//         system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> count_records($table,$filter)\n");
        // 				print "</br><font style='color:yellow'>select count(*) as num from $table where $filter</br>";
        $res = @mysqli_query($this->dbhandler, "select count(*) as num from $table".(($filter!="")?" where $filter" : ""));
        $xx= $this->mysqli_db_result($res,0,"num");
// echo "<font style='color:red'>xx -> $xx</font></br>";

        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> count_records($table,$filter) = $xx \n");
        return $xx;
    }
    
    public function count_rows($result){
        global $settings;
        
        $count_rows = mysqli_num_rows($result);
        
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> count_rows(result) = $count_rows \n");//$result
        return $count_rows;
    }
    
    public function select($fields,$tables,$where="",$order_by="",$group_by="",$having="",$limit="", $distinct="") {
        global $system, $lang, $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> select(fields->[$fields]|tables->[$tables]|where->[$where]|$order_by|$group_by|$having|$limit|$distinct) -- SESSION[fromBasket]-->$_SESSION[fromBasket]\n");
        
        // echo "<br>select=> $tables<br>";
        if($distinct == 1){
            $sql_stat=" SELECT DISTINCT $fields FROM $tables ";
        }else{
            $sql_stat=" SELECT $fields FROM $tables ";
        }
        
        //		if ($_SESSION[uid] != 1){
        if(strstr($tables, ",")){
            if(!empty($where)){
                //echo "<br> **** 1 **** <br>";
                $sql_stat.="WHERE `base`.`owner` = $_SESSION[uid] AND `base`.`or` = 1 AND $where OR `base`.`group` IN ($_SESSION[gid]) AND `base`.`gr` = 1 AND $where OR `base`.`tr` = 1 AND $where OR `base`.`ur` = 1 AND $where ";
            }else{
                //echo "<br> **** 2 **** <br>";
                $sql_stat.="WHERE `base`.`owner` = $_SESSION[uid] AND `base`.`or` = 1 OR `base`.`group` IN ($_SESSION[gid]) AND `base`.`gr` = 1 OR `base`.`tr` = 1 OR `base`.`ur` = 1 ";
            }
        }else{
            //echo "<br> **** 3 **** <br>";
            if(!empty($where)){
//                 if ($_SESSION[fromBasket]==0){
//                     $sql_stat.="WHERE `owner` = $_SESSION[uid] AND `or` = 1 AND $where OR `group` IN ($_SESSION[gid]) AND `gr` = 1 AND $where OR `tr` = 1 AND $where OR `ur` = 1 AND $where ";
//                 }else{
                    $sql_stat.="WHERE `owner` = 2 AND `or` = 1 AND $where OR `group` IN ($_SESSION[gid]) AND `gr` = 1 AND $where OR `tr` = 1 AND $where OR `ur` = 1 AND $where ";
//                 }
            }else{
                //echo "<br> **** 4 **** <br>";
                $sql_stat.="WHERE `owner` = $_SESSION[uid] AND `or` = 1 OR `group` IN ($_SESSION[gid]) AND `gr` = 1 OR `tr` = 1 OR `ur` = 1 ";
            }
        }
        //			print $sql_stat;
        //		}else{
        //			$sql_stat.= "WHERE $where";
        //		}
            
        if (!empty($group_by)) $sql_stat.="GROUP By $group_by ";
        if (!empty($order_by)) $sql_stat.="ORDER BY $order_by ";
        if (!empty($having)) $sql_stat.="HAVING $having ";
        if (!empty($limit)) $sql_stat.="LIMIT $limit ";
        
        //*****
        //echo "<br><br>sql_stat--> ". $sql_stat . "<br><br>";
        ////		system::debug($settings['debugFile'], "chrF", "\n sql_stat--> $sql_stat \n");
        
        $this->db_result=@mysqli_query($this->dbhandler, $sql_stat) or $this->error($sql_stat,mysqli_error($this->dbhandler),mysqli_errno($this->dbhandler));
        //		$this->db_affected_rows=@mysqli_num_rows($this->db_result);
        //		if($this->db_affected_rows == 0){
        //			$this->error(null, $lang[thereIsNoEntry], null);
        //		}
            
        return $this->db_result;
    }
    
    public function list_tables() {
        global $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> list_tables()\n");
        $this->db_result=$query = $this->dbhandler->query("SHOW TABLES FROM $db");
        $this->db_affected_rows=@mysqli_num_rows($this->db_result);
        return $this->db_result;
    }
    
    public function describe($tablename) {
        global $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> describe($tablename)\n");
        $this->result=@mysqli_query($this->dbhandler, "describe $tablename");
    }
    
    public function table_exists($tablename) {
        global $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> table_exists($tablename)\n");
        $this->pushresults();
        $description=$this->describe($tablename);
        $this->popresults();
        if ($description) $exists=true; else $exists=false;
        return $exists;
    }
    
    public function tablename($tables, $tbl) {
        global $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> tablename($tables, $tbl)\n");
        $listdbtables = array_column(mysqli_fetch_all($this->dbhandler->query('SHOW TABLES')),0);
        return $listdbtables[$tbl];
    }
    
    public function insert_id() {
        global $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> insert_id()\n");
        return mysqli_insert_id($this->dbhandler);
    }
    
    public function insert($table,$fields="",$values="") {
        global $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> insert($table,$fields,$values)\n");
        $sql_stat="insert into $table ";
        
        if (is_array($fields)) $theFields=implode(",",$fields); else $theFields=$fields;
        if (is_array($values)) $theValues="'".implode("','",$values)."'"; else $theValues=$values;
        
        //		if(!is_array($values)){
        //			if(strstr($values, "', '")){
        //				explode("', '", $values);
        //			}elseif(strstr($values, "','")){
        //				explode("','", $values);
        //			}
        //		}
        //		foreach ($values as $key=>$value){
        //			$arr[] = mysqli_real_escape_string($value);
        //		}
        //		$theValues="'" . implode("','",$arr) . "'";
        
        $theValues=str_replace("'now()'","now()",$theValues);
        
        if (!empty($theFields)) $sql_stat.="($theFields) ";
        $sql_stat.="values ($theValues)";
        //echo "<br>insert=> <br>";
        //print_r ($sql_stat);
        //echo "<br>";
        return @mysqli_query($this->dbhandler, $sql_stat) or $this->error($sql_stat,mysqli_error($this->dbhandler),mysqli_errno($this->dbhandler));
    }
    
    public function update($table,$newvals,$where="") {
        global $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> update($table,$newvals,$where)\n");
        if (is_array($newvals)) $theValues=implode(",",$newvals); else $theValues=$newvals;
        
        $sql_stat="update $table set $theValues";
        
        if (!empty($where)) $sql_stat.=" where $where";
        @mysqli_query($this->dbhandler, $sql_stat) or $this->error($sql_stat,mysqli_error($this->dbhandler),mysqli_errno($this->dbhandler));
    }
    
    public function delete($table,$where="") {
        global $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> delete($table,$where)\n");
        
        $sql_stat="delete from $table ";
        
        if (!empty($where)) $sql_stat.="where $where ";
        //echo "<br> del <br>";
        $db_result2=@mysqli_query($this->dbhandler, $sql_stat) or $this->error($sql_stat,mysqli_error($this->dbhandler),mysqli_errno($this->dbhandler));
        $this->db_affected_rows=@mysqli_affected_rows($this->db_result2);
    }
    
    public function free() {
        global $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> free()\n");
        @mysqli_free_result($this->db_result) or $this->error("",mysqli_error($this->dbhandler),mysqli_errno($this->dbhandler));
    }
    
    public function fetch_row() {
        global $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> fetch_row()\n");
        $row=mysqli_fetch_row($this->db_result);
        return $row;
    }
    
    public function fetch_assoc() {
        global $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> fetch_assoc()\n");
        $row=mysqli_fetch_assoc($this->db_result);
        return $row;
    }
    
    public function result($recno,$field) {
        global $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> result($recno,$field)\n");
        
        return $this->mysqli_db_result($this->db_result,$recno,$field);
    }
    
    public function num_fields(){
        global $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> num_fields()\n");
        return mysqli_num_fields($this->db_result);
    }
    
    public function fetch_array($link="") {
        global $settings;
        // به دلیل تعداد تکرار زیاد حذف شده		
//         system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> fetch_array(link ==> ".print_r($link)."\n");
        //		if(isset($this->db_result)){
        if ($link==null){
            $row=mysqli_fetch_array($this->db_result, MYSQLI_ASSOC);
        }else{
            $row=mysqli_fetch_array($link, MYSQLI_ASSOC);
        }
        //		echo "<br/>$this->db_result => ".$this->db_result;
        //echo "<p style='direction:ltr;color:green;'>mysqli_list_tables = >>".mysqli_list_tables($this->db_name)." <br/>";
        //echo "<p style='direction:ltr;color:black;'>mysqli_fetch_array(".$this->db_result.", ".$this->db_name.",table= ".mysqli_tablename(mysqli_list_tables($this->db_name)).", ".MYSQLI_ASSOC.")<br/>";
//         echo "<br/>row =>> <br/>";
//         print_r ($row);
//         echo "<br/>";
        //echo "<p style='direction:ltr;color:grey;'>mysqli_table_name =>> ".mysqli_table_name();
        //echo "</p>";
        //echo "<p style='direction:ltr;color:orange;>";
        //print_r ($this);
        //echo "</p>";
        return $row;
        //		}else{
        //			return "There is no entry";
        //		}
    }
    
    public function fetch_field() {
        global $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> fetch_field()\n");
        $row=mysqli_fetch_field($this->db_result);
        return $row;
    }
    
    public function max_auto_inc($table){
        global $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> max_auto_inc($table)\n");
        $ret = mysqli_fetch_row(mysqli_query("SELECT max(`id`) FROM `$table`"));
        return $ret[0];
    }
    
    public function findMax($table, $field){
        global $settings;
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> findMax($table, $field)\n");
        
        $result = mysqli_query($this->dbhandler, "SELECT max($field) FROM $table");
        if($result){
            $ret = mysqli_fetch_row($result);
            return $ret[0];
        }else{
            return 0;
        }
    }
    
    public function informer($table, $filter, $field=null){
        global $lang;
        global $settings;
//         system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> informer($table, $filter, $field)\n");
        
        //		print "<br> SELECT * FROM $table WHERE $filter - $field <br>";
        $res = mysqli_query($this->dbhandler, "SELECT * FROM $table WHERE $filter");
        if(!empty($res)){
            $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
            if(!empty($row)){
                if(!empty($field)){
                    system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> informer($table, $filter, $field) = $row[$field]\n");
                    return $row[$field];
                }else{
                    system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> informer($table, $filter, $field) = \n".print_r($row,true)."\n");
                    return $row;
                }
            }else{
                system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> informer($table, $filter, $field) = NULL \n");
                return null;
            }
        }else{
            system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> informer($table, $filter, $field) = NULL \n");
            return null;
        }
    }
    
    public function lookup($table, $filter=null){
        global $system;
        global $settings;
//         system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> lookup($table, $filter)\n");
        
        
        if(!empty($filter))
            $this->select("`id`, `name`", $table, $filter, "`name` ASC");
        else
            $this->select("`id`, `name`", $table, "", "`name` ASC");
        
        while($row = $this->fetch_array()){
            $result[$row[id]] = $row[name];
        }
        
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> lookup($table, $filter) = \n".print_r($result,true)."\n");
        return $result;
    }
    
    public function hashLister($fields, $tables, $where=null, $order_by=null, $group_by=null, $having=null, $limit=null, $distinct=null){
        global $settings;
//         system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> hashLister($fields, $tables, $where, $order_by, $group_by, $having, $limit, $distinct)\n");
        
        if(!empty($fields) && !empty($tables)){
            $this->select($fields, $tables, $where, $order_by, $group_by, $having, $limit, $distinct);
        }
        
        $count = 1;
        while ($row = $this->fetch_array()){
            $entityList[$count][count] = $count;
            foreach ($row as $key => $value){
                $entityList[$count][$key] = $value;
            }
            $count++;
        }
        
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> hashLister($fields, $tables, $where, $order_by, $group_by, $having, $limit, $distinct) = \n".print_r($entityList)."\n");
        return $entityList;
    }
    
    public function arrayLister($fields, $tables, $where=null, $order_by=null, $group_by=null, $having=null, $limit=null, $distinct=null){
        global $settings;
//         system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> arrayLister($fields, $tables, $where, $order_by, $group_by, $having, $limit, $distinct)\n");
        
        if(!empty($fields) && !empty($tables)){
            $this->select($fields, $tables, $where, $order_by, $group_by, $having, $limit, $distinct);
        }
        
        while ($row = $this->fetch_array()){
            foreach ($row as $value){
                $entityList[] = $value;
            }
        }
        
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> arrayLister($fields, $tables, $where, $order_by, $group_by, $having, $limit, $distinct) = \n".print_r($entityList,true)."\n");
        return $entityList;
    }
    
    public function arrayLister2d($fields, $tables, $where=null, $order_by=null, $group_by=null, $having=null, $limit=null, $distinct=null){
        global $settings;
//         system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> arrayLister2d($fields, $tables, $where, $order_by, $group_by, $having, $limit, $distinct)\n");
        
        if(!empty($fields) && !empty($tables)){
            $this->select($fields, $tables, $where, $order_by, $group_by, $having, $limit, $distinct);
        }
        
        $count1=1;
        while ($row = $this->fetch_array()){
            $count2=1;
            foreach ($row as $value){
                $entityList[$count1][$count2] = $value;
                $count2++;
            }
            $count1++;
        }
        
        
        system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> arrayLister2d($fields, $tables, $where, $order_by, $group_by, $having, $limit, $distinct) = \n".print_r($entityList,true)."\n");
        return $entityList;
    }
    
    
    public function query($query){
        global $lang;
        global $settings;
//         system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> query(query==>\n$query)\n");
        
        //		print "<br> SELECT * FROM $table WHERE $filter - $field <br>";
        $res = mysqli_query($this->dbhandler, $query);
        if(!empty($res)){
            $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
            if(!empty($row)){
                system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> query(query==>\n$query) = \n".print_r($row,true)."\n");
                return $row;                
            }else{
                system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> query(query==>\n$query) = NULL\n");
                return null;
            }
        }else{
            system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> query(query==>\n$query) = NULL\n");
            return null;
        }
    }
    
    
    
    public function usrsQuery($type, $usrs_query=NULL, $telPriority=1, $addrPriority=1, $fields=NULL, $filter=NULL){
        global $lang;
        global $settings;
//         system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> query($type , query==>\n$query\n, $fields)\n");
        
        if (!empty($filter)){
            $filter = 'AND '.$filter;
        }
        
        if (!empty($fields)){
            $fields = ', '.$fields;
        }
        
        switch ($type){
            case "pers":
                $query = "SELECT ID.id, ID.userName, ID.mobile, ID.email, pers.firstName, pers.lastName, ID.mobile, addr.country, addr.state, addr.city, addr.region, addr.district, addr.address, addr.pNo, addr.floor, addr.unit, addr.zipcode $fields
                                   From (((((usrs_ID AS ID
                                    iNNER JOIN usrs_Personal AS pers ON pers.owner = ID.id)
                                    iNNER JOIN usrs_Address As addr ON addr.owner = ID.id)
                                    iNNER JOIN addr_Tel_Type As addrTypes ON addrTypes.id = addr.addressType)
                                    iNNER JOIN usrs_Tel As tel ON tel.owner = ID.id)
                                    iNNER JOIN addr_Tel_Type As telTypes ON telTypes.id = tel.telType)
                                   WHERE ID.id = $_SESSION[uid] AND tel.telPriority = $telPriority AND addr.priority = $addrPriority $filter";
                break;
            case "co":
                $query = "SELECT ID.id, ID.userName, ID.mobile, ID.email, co.coType, co.coName, ID.mobile, addr.country, addr.state, addr.city, addr.region, addr.district, addr.address, addr.pNo, addr.floor, addr.unit, addr.zipcode $fields 
                               From (((((usrs_ID AS ID
                                iNNER JOIN usrs_co AS co ON co.owner = ID.id)
                                iNNER JOIN usrs_Address As addr ON addr.owner = ID.id)
                                iNNER JOIN addr_Tel_Type As addrTypes ON addrTypes.id = addr.addressType)
                                iNNER JOIN usrs_Tel As tel ON tel.owner = ID.id)
                                iNNER JOIN addr_Tel_Type As telTypes ON telTypes.id = tel.telType)
                               WHERE ID.id = $_SESSION[uid] AND tel.telPriority = $telPriority AND addr.priority = $addrPriority $filter";
                break;
            case "query":
                $query = $usrs_query;
                break;
            default:
                system::debug($settings['debugFile'], "err", "	Function=> mysqli.php-> query(ther is no type entry...)\n");
        }
        
//         system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> query($type , query==>\n$query\n, $fields)\n");
        
        //		print "<br> SELECT * FROM $table WHERE $filter - $field <br>";
        $res = mysqli_query($this->dbhandler, $query);
        
        
//         echo "<br/>res-><br/>";
//         print_r($res);
//         echo "<br/>";
        
        if(!empty($res)){
            $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
            
            
//             echo "<br/>row-><br/>";
//             print_r($row);
//             echo "<br/>";
            
            
            if(!empty($row)){
                //                 echo "<br/>1<br/>";
                system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> query($type , query==>\n$query\n, $fields) = \n".print_r($row,true)."\n");
                return $row;
            }else{
                //                 echo "<br/>2<br/>";
                system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> query($type , query==>\n$query\n, $fields) = NULL\n");
                return null;
            }
        }else{
            //             echo "<br/>3<br/>";
            system::debug($settings['debugFile'], "chrF", "	Function=> mysqli.php-> query($type , query==>\n$query\n, $fields) = NULL\n");
            return null;
        }
    }
    
    
    //#############################################################################################################################
}

// END class
