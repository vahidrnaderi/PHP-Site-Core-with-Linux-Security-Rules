<?php
/*###########################
#        Yahoo status       #
###########################*/
define ("YAHOO_ONLINE", 1);
define ("YAHOO_OFFLINE", 2);
function execute($yahoo = "", &$errno, &$errstr){
		global $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> whois Module >> sys.php-> execute($yahoo, &$errno, &$errstr)\n");
		
    $errno = 0;
    $errstr = "";
    $lines = @file ("http://opi.yahoo.com/online?u=" . $yahoo . "&m=t"); 
    if ($lines !== false){
	$response = implode ("", $lines);
	if (strpos($response, "NOT ONLINE") !== false){
	    return YAHOO_OFFLINE;
	}elseif(strpos($response, "ONLINE") !== false){
	    return YAHOO_ONLINE;
	}
    }
}
function yahstat($id, $img=null){
    global $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> whois Module >> sys.php-> yahstat($id, $img)\n");
		
    if(!empty($id)){
	if(is_array($id)){
	    foreach($id as $i){
	        $status = execute($i, $errno, $errstr);
	        if($status !== false){
		    switch ($status){ 
		        case YAHOO_ONLINE: 
			    $stat .= "<A HREF=ymsgr:sendim?$i><IMG BORDER=0 SRC=lib/whois/img/On$img.gif></A>";
			break; 
			case YAHOO_OFFLINE: 
			    $stat .= "<A HREF=ymsgr:sendim?$i><IMG BORDER=0 SRC=lib/whois/img/Off$img.gif></A>";
			break; 
		    } 		
		}
	    } 		
	}else{
	    $status = execute($id, $errno, $errstr);
	    if($status !== false){
	        switch ($status){ 
		    case YAHOO_ONLINE: 
			$stat = "<A HREF=ymsgr:sendim?$id><IMG BORDER=0 SRC=lib/whois/img/on$img.gif></A>";
		    break; 
		    case YAHOO_OFFLINE: 
			$stat = "<A HREF=ymsgr:sendim?$id><IMG BORDER=0 SRC=lib/whois/img/off$img.gif></A>";
		    break;
		} 		
	    }
	} 		
	return $stat;
    }else
	return null;
}
/*###########################
#           Whois           #
###########################*/
function whois($id){
		global $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> whois Module >> sys.php-> whois($id)\n");
		
    if(informer($id, 'session') == '1'){
	if(informer($id, 'perm') == '2')
	    $who = 'ea';
	elseif(informer($id, 'perm') == '1')
	    $who = 'em';
	elseif(informer($id, 'perm') == '0')
	    $who = 'eu';
	elseif(informer($id, 'perm') == '-1')
	    $who = 'eg';
    }elseif(informer($id, 'session') == '0'){
	if(informer($id, 'perm') == '2')
	    $who = 'da';
	elseif(informer($id, 'perm') == '1')
	    $who = 'dm';
	elseif(informer($id, 'perm') == '0')
	    $who = 'du';
	elseif(informer($id, 'perm') == '-1')
	    $who = 'dg';
    }
    return $who;
}
/*###########################
#        Whois online       #
###########################*/
function session_list(){
    global $mydb, $lang, $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> whois Module >> sys.php-> session_list()\n");
		
    $users = mysql_query("SELECT * FROM `session` WHERE `id` <> '0'");
    $guests = mysql_query("SELECT * FROM `session` WHERE `id` = '0'");
    $usercount = mysql_num_rows($users);
    $guestcount = mysql_num_rows($guests);
    $out = "<TABLE CLASS=PANEL ALIGN=CENTER WIDTH=90% CELLSPACING=0 CELLPADDING=0 BORDER=0>";
    $out .= "<TR><TD class=headerTB ALIGN=CENTER COLSPAN=2>-- $lang[ONLINEUSER] --</TD>";
    $out .= "<TR><TD WIDTH=70%>$lang[USER]</TD><TD WIDTH=30%>$usercount</TD></TR>";
    $out .= "<TR><TD WIDTH=70%>$lang[GUEST]</TD><TD WIDTH=30%>$guestcount</TD></TR>";
    $out .= "<TR><TD class=headerTB ALIGN=CENTER COLSPAN=2><HR></TD>";
    while($num = mysql_fetch_array($users)){
	$info = informer($num[id]);
	$out .= "<TR><TD WIDTH=70%><A " . tooltip(userinfo, $num[id]) . ">$info[username]</A></TD><TD WIDTH=30%>" . yahstat($info['yid'], 5) . "</TD></TR>";
    }
    $out .= "</TABLE><BR>";
    return $out;
}
// Fetch stat of any table *****************
function statistic($from, $field){
    global $mydb, $lang, $config, $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> whois Module >> sys.php-> statistic($from, $field)\n");
		
    $mydb->select("`$field`", "`$from`", "`aprove` = '1'");
    if($mydb->fetch_row() > 0){
	while($row = $mydb->fetch_array())
	    $fields[] = $row[$field];
	$fields = array_count_values($fields);
	arsort($fields);
	$fields = array_slice_key($fields, 0, 10);
	$statistic = "<TABLE CLASS=PANEL ALIGN=CENTER WIDTH=90% CELLSPACING=0 CELLPADING=0 BORDER=0>";
	$statistic .= "<TR><TD class=headerTB ALIGN=CENTER COLSPAN=3>-- $lang[TOPUSERS] --</TD>";
	$count = 1;
	foreach($fields as $key => $rank){
	    $info = informer($key);
	    $statistic .= "<TR><TD WIDTH=15%>$count- </TD><TD WIDTH=55%><A " . tooltip(userinfo, $key) . ">$info[username]</A></TD><TD WIDTH=30%>" . $rank . "</TD></TR>";
	    $count++;
	}
	$statistic .= "</TABLE>";
    }else{
	$statistic  = "<TABLE CLASS=PANEL ALIGN=CENTER WIDTH=90% CELLSPACING=0 CELLPADING=0 BORDER=0>";
	$statistic .= "<TR><TD class=headerTB ALIGN=CENTER COLSPAN=3>$lang[THERE_IS_NO_ITEM]</TD></TR>";
	$statistic .= "</TABLE>";
    }
    return $statistic;
}
?>