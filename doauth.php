<?php
include('config.php');
session_start();
$error = array();
$errflag = false;
function sendConfirmationEmail($email){
	$subject="Confirm Account";
	$body="Please click the following link to confirm your account: http://xbl.rocks/admin/confirm.php?keyid".base64_encode($email)."&authkey=".base64_encode('cyanideisthebesthaxorever')."&confirm=true";
	mail($email,$subject,$body);
}
function clean($str) {
	$str = @trim($str);
	if(get_magic_quotes_gpc()) {
		$str = stripslashes($str);
	}
	return mysqli_real_escape_string($str);
}
if(isset($_GET['hashpass'])) echo sha1(base64_encode($_GET['hashpass']));
else{
$username = $_POST['username'];
$password = $_POST['password'];
$password = sha1(base64_encode($password));
if($username == '') {
	$error[] = 'Username missing';
	$errflag = true;
}
if($password == '') {
	$error[] = 'Password missing';
	$errflag = true;
}
if($errflag) {
	$_SESSION['error'] = $error;
	session_write_close();
	//header("location: login.php");
	$response = array(
				"status" => 2,
				"message" => "Username or password missing!"
			);
			echo json_encode($response);
	exit();
}
$qry="SELECT * FROM unshared_users.member WHERE username='$username' AND password='$password'";
$result=mysqli_query($con, $qry);
if($result) {
	if(mysqli_num_rows($result) > 0) {
		session_regenerate_id();
		$member = mysqli_fetch_assoc($result);
		$_SESSION['user'] = $member['username'];
		$_SESSION['email'] = $member['email'];
		$_SESSION['pass'] = $member['password'];
		$_SESSION['rank'] = $member['security'];
		$_SESSION['allowedc'] = $member['allowed'];
		$_SESSION['server'] = $member['server'];
		$_SESSION['banreason'] = $member['banreason'];
		if ($_SESSION['rank'] == 6){
			$error[] = 'You have been banned! Reason: ' . $_SESSION['BAN_REASON'];
			$errflag = true;
			$_SESSION['error'] = $error;
			session_write_close();
			//header("location: login.php");
			$response = array(
				"status" => 0,
				"message" => "You have been banned!"
			);
			echo json_encode($response);
			exit();
		}else if ($_SESSION['rank'] < 6){
			if($member['confirmed'] == '1'){
				if(get_ip() == $member['ip'] || $member['ip'] == '*'){
					if($member['expire'] > date("Y-m-d H:i:s")){
					session_write_close();
					mysqli_query($con, "INSERT INTO `unshared_users`.`feed` (`user`, `action`, `glyph`) VALUES ('".$_SESSION['user']."', 'Logged in', 'fa fa-sign-in');");
					//header("location: index.php?page=dash");
					$response = array(
						"status" => 3,
						"message" => "Logging you in now!"
					);
					echo json_encode($response);
					exit();
					}
				}else{
					$error[] = "Your IP does not match the one on file";
					$error[] = "Please click the check your email for more info";
					$errflag = true;
					$_SESSION['error'] = $error;
					session_write_close();
					//header("location: login.php");
					$response = array(
						"status" => 4,
						"message" => "Your IP does not match the one on file!"
					);
					echo json_encode($response);
					
				}
				
			}else{
				$error[] = "Your account has not been confirmed";
				$error[] = "Please click the activation link in your email";
				$errflag = true;
				$_SESSION['error'] = $error;
				session_write_close();
				sendConfirmationEmail($member['email']);
				//header("location: login.php");
				$response = array(
				"status" => 5,
				"message" => "Your account has not been confirmed!"
			);
			echo json_encode($response);
				
			}
		}		
	}else {
		$error[] = 'Username or password was incorrect!';
		$errflag = true;
		if($errflag) {
			$_SESSION['error'] = $error;
			session_write_close();
			
		}
		//header("location: login.php");
		$response = array(
				"status" => 6,
				"message" => "Username or password was incorrect!"
			);
			echo json_encode($response);
		exit();
		
		
	}
}else {
	$error[] = 'Failed to execute command!';
	$errflag = true;
	if($errflag) {
		$_SESSION['error'] = $error;
		session_write_close();
		//header("location login.php");
		$response = array(
				"status" => 7,
				"message" => "Could not log you in!"
			);
			echo json_encode($response);
	}
}
}
?>