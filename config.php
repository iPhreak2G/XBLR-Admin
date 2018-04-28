<?php
$stealthname = "XBLR";
$defaultuserimage = "assets/img/logo_new.png";
$loginbgimage = "assets/img/xbox-bg-1.png";
$loglocation = "C:\Users\Administrator\Desktop\BETA\console.log";
$authkey = "tcHWnjeWj6vNAeXTKv45tK7LS";
$servername = (isset($_SESSION['server'])) ? $_SESSION['server'] : "XBLRocks";
$mysql_hostname = "172.93.137.7";
$mysql_user = "unshared";
$mysql_password = "eNkG4Kg6u9FAfBa";
$con = mysqli_connect($mysql_hostname, $mysql_user, $mysql_password) or die("Could not connect database");
function execInBackground($cmd) {
    if (substr(php_uname(), 0, 7) == "Windows"){
        pclose(popen("start /B ". $cmd, "r")); 
    }
    else {
        exec($cmd . " > /dev/null &");  
    }
}
function get_ip()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

function url(){
  return sprintf(
    "%s://%s%s",
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    $_SERVER['SERVER_NAME'],
    $_SERVER['PHP_SELF']
  );
}

function getSetting($val){
	$arr = parse_ini_file("config.ini");
	return $arr[$val];
}
function generateTok() {
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $count = mb_strlen($chars);
    for ($i = 0, $tok1 = ''; $i < 5; $i++) {
        $index = rand(0, $count - 1);
        $tok1 .= mb_substr($chars, $index, 1);
    }
	for ($i = 0, $tok2 = ''; $i < 5; $i++) {
        $index = rand(0, $count - 1);
        $tok2 .= mb_substr($chars, $index, 1);
    }
	for ($i = 0, $tok3 = ''; $i < 5; $i++) {
        $index = rand(0, $count - 1);
        $tok3 .= mb_substr($chars, $index, 1);
    }
	$result = $tok1.'-'.$tok2.'-'.$tok3;
    return $result;
}
$dash = '4294838225';
$bo3 = '68509841';
$bo2 = '1096157379';
$bo1 = '1096157269';
$mw3 = '1096157387';
$mw2 = '1096157207';
$mw1 = '1096157158';
$aw = '1096157460';
$ghosts = '1096157436';
$destiny = '1096157432';
$gtav = '1414793383';

$cmds = array(
'SELECT * FROM `unshared_consoles`.`consoles` WHERE`server`="'.$servername.'";', 
'SELECT * FROM `unshared_consoles`.`consoles` WHERE `lastconnection` > now() - INTERVAL 5 MINUTE AND `server`="'.$servername.'";', //online in the last 5 minutes
'SELECT * FROM `unshared_consoles`.`consoles` WHERE `days` > 0 AND `lifetime` = 0 AND `server`="'.$servername.'";', //with time
'SELECT * FROM `unshared_consoles`.`consoles` WHERE `lifetime` = 1AND `server`="'.$servername.'";', //with lifetime
'SELECT * FROM `unshared_consoles`.`consoles` WHERE `isPlatinum` = 1AND `server`="'.$servername.'";',  //with platinum
'SELECT * FROM `unshared_consoles`.`consoles` WHERE `banned` = 1', //banned
'SELECT * FROM `unshared_consoles`.`consoles` WHERE `timeunbanned` > 1440 AND `timeunbanned` < 2880AND `server`="'.$servername.'";', //unbanned for 1 day
'SELECT * FROM `unshared_consoles`.`consoles` WHERE `timeunbanned` > 2880 AND `timeunbanned` < 4320AND `server`="'.$servername.'";', //unbanned for 2 days
'SELECT * FROM `unshared_consoles`.`consoles` WHERE `timeunbanned` > 4320 AND `timeunbanned` < 5760AND `server`="'.$servername.'";', //unbanned for 3 days
'SELECT * FROM `unshared_consoles`.`consoles` WHERE `timeunbanned` > 5760 AND `timeunbanned` < 7200AND `server`="'.$servername.'";', //unbanned for 4 days
'SELECT * FROM `unshared_consoles`.`consoles` WHERE `timeunbanned` > 7200AND `server`="'.$servername.'";', //unbanned for 5 days
'SELECT * FROM `unshared_consoles`.`consoles` WHERE `timeunbanned` > 1 AND `server`="'.$servername.'";',
'SELECT * FROM `unshared_consoles`.`consoles` ORDER BY `uid` DESC LIMIT 1',
'SELECT * FROM `unshared_consoles`.`consoles` ORDER BY `lastconnection` DESC LIMIT 1',
'SELECT * FROM `unshared_consoles`.`consoles` WHERE `titleid`=',
'SELECT * FROM `unshared_consoles`.`consoles` WHERE `lastconnection` > now() - INTERVAL 7 DAYAND `server`="'.$servername.'";',
'',
'',
'',
'',
'',
'',
'',
'',
'',
'',
'',
''
);

$result1 = mysqli_query($con, $cmds[0]);
$result2 = mysqli_query($con, $cmds[1]);
$result3 = mysqli_query($con, $cmds[2]);
$result4 = mysqli_query($con, $cmds[3]);
$result5 = mysqli_query($con, $cmds[4]);
$result6 = mysqli_query($con, $cmds[5]);
$result7 = mysqli_query($con, $cmds[6]);
$result8 = mysqli_query($con, $cmds[7]);
$result9 = mysqli_query($con, $cmds[8]);
$result10 = mysqli_query($con, $cmds[9]);
$result11 = mysqli_query($con, $cmds[10]);
$result12 = mysqli_query($con, $cmds[11]);
$result13 = mysqli_query($con, $cmds[12]);
$result14 = mysqli_query($con, "SELECT * FROM `unshared_consoles`.`consoles` WHERE `ip`='$ip';");
$result15 = mysqli_query($con, $cmds[13]);
$resultweek = mysqli_query($con, $cmds[14]);
$resbo3 = mysqli_query($con, "SELECT * FROM `unshared_consoles`.`consoles` WHERE `titleid`='$bo3' AND `lastconnection` > now() - INTERVAL 5 MINUTE;");
$resaw = mysqli_query($con, "SELECT * FROM `unshared_consoles`.`consoles` WHERE `titleid`='$aw' AND `lastconnection` > now() - INTERVAL 5 MINUTE;");
$resghosts = mysqli_query($con, "SELECT * FROM `unshared_consoles`.`consoles` WHERE `titleid`='$ghosts' AND `lastconnection` > now() - INTERVAL 5 MINUTE;");
$resbo2 = mysqli_query($con, "SELECT * FROM `unshared_consoles`.`consoles` WHERE `titleid`='$bo2' AND `lastconnection` > now() - INTERVAL 5 MINUTE;");
$resmw3 = mysqli_query($con, "SELECT * FROM `unshared_consoles`.`consoles` WHERE `titleid`='$mw3' AND `lastconnection` > now() - INTERVAL 5 MINUTE;");
$resbo1 = mysqli_query($con, "SELECT * FROM `unshared_consoles`.`consoles` WHERE `titleid`='$bo1' AND `lastconnection` > now() - INTERVAL 5 MINUTE;");
$resmw2 = mysqli_query($con, "SELECT * FROM `unshared_consoles`.`consoles` WHERE `titleid`='$mw2' AND `lastconnection` > now() - INTERVAL 5 MINUTE;");
$reswaw = mysqli_query($con, "SELECT * FROM `unshared_consoles`.`consoles` WHERE `titleid`=''");
$resmw1 = mysqli_query($con, "SELECT * FROM `unshared_consoles`.`consoles` WHERE `titleid`='$mw1' AND `lastconnection` > now() - INTERVAL 5 MINUTE;");
$resdestiny = mysqli_query($con, "SELECT * FROM `unshared_consoles`.`consoles` WHERE `titleid`='$destiny' AND `lastconnection` > now() - INTERVAL 5 MINUTE;");
$resgtav = mysqli_query($con, "SELECT * FROM `unshared_consoles`.`consoles` WHERE `titleid`='$gtav' AND `lastconnection` > now() - INTERVAL 5 MINUTE;");
$resdash = mysqli_query($con, "SELECT * FROM `unshared_consoles`.`consoles` WHERE `titleid`='$dash' AND `lastconnection` > now() - INTERVAL 5 MINUTE;");
$playersbo3 = mysqli_num_rows($resbo3);
$playersbo2 = mysqli_num_rows($resbo2);
$playersbo1 = mysqli_num_rows($resbo1);
$playersmw3 = mysqli_num_rows($resmw3);
$playersmw2 = mysqli_num_rows($resmw2);
$playersmw1 = mysqli_num_rows($resmw1);
$playersdash = mysqli_num_rows($resdash);
$playersghosts = mysqli_num_rows($resghosts);
$playersaw = mysqli_num_rows($resaw);
$playersdestiny = mysqli_num_rows($resdestiny);
$playersgtav = mysqli_num_rows($resgtav);
$playersbo3 = mysqli_num_rows($resbo3);
$consolesweek = mysqli_num_rows($resultweek);
$num1 = mysqli_num_rows($result1);
$num2 = mysqli_num_rows($result2);
$num3 = mysqli_num_rows($result3);
$num4 = mysqli_num_rows($result4);
$num5 = mysqli_num_rows($result5);
$num6 = mysqli_num_rows($result6);
$num7 = mysqli_num_rows($result7);
$num8 = mysqli_num_rows($result8);
$num9 = mysqli_num_rows($result9);
$num10 = mysqli_num_rows($result10);
$num11 = mysqli_num_rows($result11);
$num12 = mysqli_num_rows($result12);
$array1 = mysqli_fetch_assoc($result13);
$array2 = mysqli_fetch_assoc($result1);
$array3 = mysqli_fetch_assoc($result14);
$array4 = mysqli_fetch_assoc($result15);
$num13 = mysqli_num_rows($result14);
//echo 'DateTime: ' . date('Y-m-d h:i:s', strtotime("now")) . '</br>';

?>