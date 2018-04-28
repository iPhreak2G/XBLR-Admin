<?php
include('config.php');
$hash = base64_encode('cyanideisthebesthaxorever');
$expire = base64_decode($_GET['pir']);
if(isset($_GET['keyid']) && isset($_GET['confirm']) && isset($_GET['authkey']){
	$account = base64_decode($_GET['keyid']);
	$key = base64_decode($_GET['authkey']);
	if($key == $hash){
		if($_GET['confirm'] == 'true'){
			mysqli_query($con, "UPDATE unshared_user.member SET ip=".get_ip().", confirmed='1', expire=".$expire." WHERE email=".$account.";");
			$error[] = "Your account has been confirmed, you may now login";
			$_SESSION['error'] = $error;
			session_write_close();
			header("location: login.php");
		}
	}
}

?>