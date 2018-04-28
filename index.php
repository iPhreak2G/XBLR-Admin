<!DOCTYPE html>
<?php
include('config.php');
session_start();
if(!isset($_SESSION['user']) || (trim($_SESSION['user']) == '')) {
	header("location: login.php?logout=1");
	exit();
}
$userr = $_SESSION['user'];
$ip = get_ip();
mysqli_query($con, "UPDATE unshared_users.member SET ip='".$ip."' WHERE username='".$userr."';");

if(isset($_POST['massadd'])){
	$text = $_POST['masscpukey'];
	//echo $text;
    $lines = explode(PHP_EOL, $text); // or use PHP PHP_EOL constant
	if ( !empty($lines) ) {
	  foreach ( $lines as $line ) {
		//echo $line;
		$subcpu = substr($line, 0, 32);
		mysqli_query($con, "INSERT INTO unshared_consoles.consoles (cpukey, name, days, server) VALUES('".$subcpu."', 'Guest', '3', '".$_SESSION['server']."') ON DUPLICATE KEY UPDATE cpukey='".$subcpu."', name='Guest', days='3', server='".$_SESSION['server']."';");
	  }
	}
}
if(isset($_GET['do'])){
	if($num1 < $_SESSION['allowedc']){
	mysqli_query($con, "INSERT INTO `unshared_users`.`feed` (`user`, `action`, `glyph`) VALUES ('".$userr."', 'Edited/Added User: ".$_POST['name']."', 'fa fa-pencil');");
	$res = mysqli_query($con, "INSERT INTO unshared_consoles.consoles (cpukey, name, days, server) VALUES('".$_POST['cpu']."', '".$_POST['name']."', '".$_POST['days']."', '".$_SESSION['server']."') ON DUPLICATE KEY UPDATE cpukey='".$_POST['cpu']."', name='".$_POST['name']."', days='".$_POST['days']."', server='".$_SESSION['server']."';");
	if($res) header("location: index.php?page=manage");
	}
}
if(isset($_GET['doo'])){
	for($i =0; $i<$_POST['amount']; $i++){
		$loltok = generateTok();
		mysqli_query($con, "INSERT INTO `unshared_users`.`feed` (`user`, `action`, `glyph`) VALUES ('".$userr."', 'Generated Token: ".$loltok."', 'fa fa-key');");
		$res = mysqli_query($con, "INSERT INTO `unshared_consoles`.`tokens` (`token`, `time`, `used`, `generatedby`, `server`) VALUES ('$loltok', '".$_POST['days']."', '0', '".$userr."', '".$_SESSION['server']."');");
		if(!$res) echo 'failed';
	}
	header("location: index.php?page=tokens");
}
$val = '';
$bool = false;
$nullarr = array(
	'lastconnection' => '0000-00-00 00:00:00',
	'timeunbanned' => null,
	'GT' => '[null]',
	'kvhash' => '0000000000000000',
	'kvstatus' => 1,
	'kvdata' => null,
	'name' => '[null]',
	'cpukey' => '00000000000000000000000000000000',
	'days' => 0,
);
if(isset($_GET['I'])) $data = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM unshared_consoles.consoles WHERE cpukey='".$_GET['I']."';"));
elseif(isset($_GET['P'])){ $bool = true; $data2 = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM unshared_consoles.consoles WHERE cpukey='".$_GET['P']."';")); }
elseif(isset($_GET['T'])) { mysqli_query($con, "DELETE FROM unshared_consoles.consoles WHERE cpukey='".$_GET['T']."';"); header("location: index.php?page=manage"); $data = $nullarr; }
elseif(isset($_GET['K'])) { $data = $nullarr; $data2 = $nullarr; }
$datetime3 = date_create(date('Y-m-d H:i:s'));
$datetime4 = date_create(date($data["expire"]));
$interval2 = date_diff($datetime4, $datetime3);
if($datetime4 < $datetime3) $expire = 'expired';
else $expire = $interval2->format('%h:%i');

$rank = "user";
$numrank = $_SESSION['rank'];
if($_SESSION['rank'] == 1) $rank = "Owner";
elseif($_SESSION['rank'] == 2) $rank = "Admin";
elseif($_SESSION['rank'] == 3) $rank = "Seller";
elseif($_SESSION['rank'] == 4) $rank = "Token Seller";
elseif($_SESSION['rank'] == 5) $rank = "Support";
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $stealthname; ?> - Admin</title>
	<link rel="icon" type="image/png" href="img/favicon.png">
<link rel="apple-touch-icon-precomposed" href="img/apple-touch-favicon.png">
<link href="libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic" rel="stylesheet" type="text/css">
<link href="libs/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="libs/jquery.scrollbar/jquery.scrollbar.css" rel="stylesheet">
<link href="libs/ionrangeslider/css/ion.rangeSlider.css" rel="stylesheet">
<link href="libs/ionrangeslider/css/ion.rangeSlider.skinFlat.css" rel="stylesheet">
<link href="libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet">
<link href="libs/datatables/media/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="css/right.css" rel="stylesheet" class="demo__css">
<link href="css/demo.css" rel="stylesheet">
<script type="text/javascript">

        function Ajax()
        {
            var
                $http,
                $self = arguments.callee;

            if (window.XMLHttpRequest) {
                $http = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                try {
                    $http = new ActiveXObject('Msxml2.XMLHTTP');
                } catch(e) {
                    $http = new ActiveXObject('Microsoft.XMLHTTP');
                }
            }

            if ($http) {
                $http.onreadystatechange = function()
                {
                    if (/4|^complete$/.test($http.readyState)) {
                        document.getElementById('clog').innerHTML = $http.responseText;
                        setTimeout(function(){$self();}, 1000);
                    }
                };
                $http.open('GET', 'console.php' + '?' + new Date().getTime(), true);
                $http.send(null);
            }

        }

    </script>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
  </head>
  <body class="transparent_lilac">
    <div class="wrapper">
      <nav class="navbar navbar-static-top header-navbar">
        <div class="header-navbar-mobile">
          <div class="header-navbar-mobile__menu">
            <button type="button" class="btn"><i class="fa fa-bars"></i></button>
          </div>
          <div class="header-navbar-mobile__title"><span>Home</span></div>
          <div class="header-navbar-mobile__settings dropdown"><a href="" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" class="btn dropdown-toggle"><i class="fa fa-power-off"></i></a>
            <ul class="dropdown-menu dropdown-menu-right">
              <li><a href="#">Log Out</a></li>
            </ul>
          </div>
        </div>
        <div class="navbar-header"><a href="index.php" class="navbar-brand">
            <div class="logo text-nowrap">
              <div class="logo__img"><img src="img/logo.png" class="img-circle" width="35"></div><
            </div></a></div>
        <div class="topnavbar">
          <ul class="nav navbar-nav navbar-left">
            <li><a href="?page=dash"><span>Dashboard</span></a></li>
            
             
          </ul>
          <ul class="userbar nav navbar-nav">
            <li class="dropdown"><a href="" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" class="userbar__settings dropdown-toggle"><i class="fa fa-power-off"></i></a>
              <ul class="dropdown-menu">
                <li><a href="login.php?logout=1">Log Out</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
      <div class="dashboard">
        <div class="sidebar">
          
          <div class="scrollable scrollbar-macosx">
            <div class="sidebar__cont">
              <div class="sidebar__menu">
                <div class="sidebar__title">Panel</div>
                <ul class="nav nav-menu">
                  <li><a href="?page=dash">
                      <div class="nav-menu__ico"><i class="fa fa-fw fa-check"></i></div>
                      <div class="nav-menu__text"><span>Dashboard</span></div></a></li>
				  <li><a href="?page=manage">
                      <div class="nav-menu__ico"><i class="fa fa-fw fa-users"></i></div>
                      <div class="nav-menu__text"><span>Manage</span></div></a></li>
					  <li><a href="?page=tokens">
                      <div class="nav-menu__ico"><i class="fa fa-fw fa-key"></i></div>
                      <div class="nav-menu__text"><span>Tokens</span></div></a></li>
					  <?php
					  if($numrank <= 2){
						  echo '<li><a href="?page=admin">
								<div class="nav-menu__ico"><i class="fa fa-fw fa-lock"></i></div>
								<div class="nav-menu__text"><span>Admin</span></div></a></li>';
					  }
					  
					  ?>
                </ul>
				
              </div>
            </div>
          </div>
        </div>
        <div class="main">
		<?php
		if (isset($_GET['page'])){
			$page = $_GET['page'];
			
			if($page == 'dash'){
				if(isset($_POST['savenote'])){
					if(file_put_contents('notepad.txt', $_POST['notetxt'])) $saved = true;
				}
				
			if(!mysqli_query($con, "INSERT INTO `unshared_users`.`feed` (`user`, `action`, `glyph`) VALUES ('".$userr."', 'Is on page: Dashboard', 'fa fa-globe');")){
							echo '<div role="alert" class="alert alert-danger alert-dismissible">
                            <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button><i class="alert-ico fa fa-fw fa-ban"></i><strong>Failed!&thinsp;</strong>
					Failed To Set Your Presence Feed!
					</div>';
						}
			echo '<div class="scrollable scrollbar-macosx">
            <div class="main__cont">
              <div class="main-heading">
                <div class="main-title">
                  <ol class="breadcrumb">
                    <li class="active">Dashboard</li>
                  </ol>
                </div>
              </div>';
              if(!isset($_SESSION['welcome']) || $_SESSION['welcome'] = false){
				echo '<div class="row">
				<div role="alert" class="alert alert-success alert-dismissible">
                            <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button><i class="alert-ico fa fa-fw fa-user"></i><strong>Welcome!&thinsp;</strong>
				Welcome '.$userr
				.', to the '.$servername.' panel!</div>';
				$_SESSION['welcome'] = true;
				session_write_close();
			  }
				?>
	<div class="col-md-9">
	  <div class="panel panel-danger">
		<div class="panel-heading">
		  <h3 class="panel-title">Welcome, <?php echo $userr; ?>!</h3>
		</div>
		<div class="panel-body">
		  <div class="panel-body">
			  <ul role="tablist" class="nav nav-pills"><li class="dropdown pull-right tabdrop hide"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-align-justify"></i> <b class="caret"></b></a><ul class="dropdown-menu"></ul></li>
				<li role="presentation" class="active"><a href="#log" aria-controls="customers" role="tab" data-toggle="tab" aria-expanded="true">Console</a></li>
				<li role="presentation" class=""><a href="#notes" aria-controls="managers" role="tab" data-toggle="tab" aria-expanded="false">Personal Pad</a></li>
			</ul>
			  <div class="tab-content">
				<div id="log" role="tabpanel" class="tab-pane active">
				  <textarea id="clog" placeholder="" rows="10" class="form-control"></textarea>
			  </div>
				<div id="notes" role="tabpanel" class="tab-pane">
				<?php 
					if(isset($saved)) echo '<div role="alert" class="alert alert-success">
                                  <h4><i class="alert-ico fa fa-fw fa-check"></i><strong>Done!&thinsp;</strong></h4>Your changes were saved.
                                </div>';
				?>
				  <form method="post" action="index.php?page=dash"><textarea name="notetxt" placeholder="notepad (visible to all staff)" rows="7" class="form-control"><?php echo file_get_contents("notepad.txt"); ?></textarea><br/><center><button name="savenote" type="sumbit" class="btn btn-success">Save</button></center></form>
				</div>
				
			  </div>
			</div>
		</div>
	  </div>
	</div>
	<div class="col-md-3">
	  <div class="panel panel-success">
		<div class="panel-heading">
		  <h3 class="panel-title">Stats</h3>
		</div>
		<div class="panel-body">
		  <div class="ov-widget">
			<div class="ov-widget__list">
			  <div class="ov-widget__item ov-widget__item_inc">
				<div class="ov-widget__value"><?php echo $num1; ?></div>
				<div class="ov-widget__info">
				  <div class="ov-widget__title">Consoles</div>
				  
				</div>
			  </div>
			  <div class="ov-widget__item ov-widget__item_inc">
				<div class="ov-widget__value"><?php echo $num2; ?></div>
				<div class="ov-widget__info">
				  <div class="ov-widget__title">Online</div>
				  
				</div>
			  </div>
			  <div class="ov-widget__item ov-widget__item_inc">
			  	<div class="ov-widget__value"><?php echo $num12; ?></div>
				<div class="ov-widget__info">
				  <div class="ov-widget__title">Unbanned</div>
				  
				</div>
			  </div><br/>
			  <div class="ov-widget__bar"><span>Allowed Consoles</span>
				<div class="progress">
				  <div role="progressbar" aria-valuenow="<?php echo $_SESSION['allowedc'] / $num1 * 100; ?>" aria-valuemin="0" aria-valuemax="<?php $_SESSION['allowedc']; ?>" style="width: <?php echo 1000 / $num3 * 100; ?>%" class="progress-bar progress-bar-success"></div>
				</div>
			  </div>
			</div>
		  </div>
		</div>
	  </div>
	</div>
			  <?php
            echo '</div>
          </div>';			
			}
			elseif($page == 'manage'){
				
				if(!mysqli_query($con, "INSERT INTO `unshared_users`.`feed` (`user`, `action`, `glyph`) VALUES ('".$userr."', 'Is on page: Manage', 'fa fa-pencil');")){
							echo '<div role="alert" class="alert alert-danger alert-dismissible">
                            <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button><i class="alert-ico fa fa-fw fa-ban"></i><strong>Failed!&thinsp;</strong>
					Failed To Set Your Presence Feed!
				</div>';
						}
				echo '<div class="scrollable scrollbar-macosx">
            <div class="main__cont">
              <div class="main-heading">
                <div class="main-title">
                  <ol class="breadcrumb">
                    <li class="active">Manage</li>
                  </ol>
                </div>
              </div>';
              ?>
				<div class="row">
<div class="col-md-3">
	  <div class="panel panel-success">
		<div class="panel-heading">
		  <h3 class="panel-title"><?php echo ($bool == true) ? "Update" : "Add"; ?> User</h3>
		</div>
		<div class="panel-body">
		  <form method="post" action="index.php?do=1">
						  <?php
							if($bool == true) {
								if(!mysqli_query($con, "INSERT INTO `unshared_users`.`feed` (`user`, `action`, `glyph`) VALUES ('".$userr."', 'Is editing user: ".$data2['name']."', 'fa fa-pencil');")){
							echo '<div role="alert" class="alert alert-danger alert-dismissible">
                            <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button><i class="alert-ico fa fa-fw fa-ban"></i><strong>Failed!&thinsp;</strong>
					Failed To Set Your Presence Feed!
				</div>';
						}
							}
						  ?>
						  <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Name</label>
                              <div class="col-sm-10">
                                  <input name="name" type="text" value="<?php echo $data2['name']; ?>" class="form-control" <?php echo ($bool)?"":($numrank>3)?"disabled":""?>>
                              </div>
                          </div><br/><br/>
						  <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">CPU</label>
                              <div class="col-sm-10">
                                  <input name="cpu" type="text" value="<?php echo $data2['cpukey']; ?>" class="form-control" <?php echo ($bool)?"":($numrank>3)?"disabled":""?>>
                              </div>
                          </div><br/><br/>
						  <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Days</label>
                              <div class="col-sm-10">
                                  <input name="days" type="number" value="<?php echo $data2['days']; ?>" class="form-control" <?php echo ($bool)?"":($numrank>3)?"disabled":""?>>
                              </div>
                          </div><br/><br/>
						  <center>
							<button type="submit" name="addsubmit" class="btn btn-success" <?php echo ($bool)?"":($numrank>3)?"disabled":""?>><?php echo ($bool == true) ? "Update" : "Add"; ?> User</button>
							</center>
						</form>
		  </div>
		</div>
		
		<div class="panel panel-success">
		<div class="panel-heading">
		  <h3 class="panel-title">Console Info</h3>
		</div>
		<div class="panel-body">
		  <p>Time Today: <?php echo $expire; ?><br/>Unbanned For: <?php echo $data['timeunbanned'] / 60 . ' Hours'; ?><br/>Last Connected: <?php echo $data['lastconnection']; ?><br/>Is Online: <?php
		  if($data['lastconnection'] > date('Y-m-d H:i:s', strtotime("-5 minutes"))) echo "TRUE"; else echo "FALSE";
		  ?><br/>Gamertag: <?php echo hex2bin($data['GT']); ?></p>
		  </div>
		</div>
		
		<div class="panel panel-success">
		<div class="panel-heading">
		  <h3 class="panel-title">Title Info</h3>
		</div>
		<div class="panel-body">
			<?php
			switch(dechex($data['titleid'])){
				case strtolower('41560817'): //mw2								
				case strtolower('415608CB'): //mw3								
				case strtolower('415608C3'): //bo2							
				case strtolower('415608fC'): //ghosts								
				case strtolower('41560914'): //aw								
				case strtolower('4156091D'): //bo3
				case strtolower('415608F8'): //destiny
				case strtolower('545408A7'): //gtav
				{
					$title = strtoupper(dechex($data['titleid']));
					echo "<center><img src='img/titles/$title.png' width='150' height='200'></center>";
					break;
				}
				case strtolower('FFFE07D1'): //dash
				{
					$title = dechex($data['titleid']);
					echo "<center><img src='img/titles/unknown.png' width='150' height='200'></center>";
					break;
				}
				default:
					echo "<center><img src='img/titles/unknown.png' width='150' height='200'></center>";
					break;
			}
			//echo dechex($data['titleid']);
		  ?>
		  </div>
		</div>
		
		<div class="panel panel-success">
		<div class="panel-heading">
		  <h3 class="panel-title">KeyVault Info</h3>
		</div>
		<div class="panel-body">
		  <p>Console Serial: <?php echo bin2hex(substr($data['kvdata'], hexdec('9CB'), 16)); ?> <br/>Console Region: <?php echo bin2hex(substr($data['kvdata'], hexdec('C8'), 2)); ?><br/>KV Unbanned: <?php echo ($data['kvstatus'] == 0) ? "TRUE" : "FALSE"; ?><br/>MFR Date: <?php echo substr($data['kvdata'], hexdec('9E4'), 8); ?><br/>KV Hash: <?php echo $data['kvhash']; ?></p>
		  </div>
		</div>
		
	  </div>
	
	<div class="col-md-9">
	  <div class="panel panel-danger">
		<div class="panel-heading">
		  <h3 class="panel-title">Consoles</h3>
		</div>
		<div class="panel-body">
		<div class="panel panel-success">
		<div class="panel-heading">
		  <h3 class="panel-title">Add Massive Amount Of Users</h3>
		</div>
		<div class="panel-body">
		  <form method="post" action="index.php?page=manage">
		<textarea name="masscpukey" placeholder="Paste the cpukeys here line by line" rows="10" class="form-control"></textarea>
		<br/>
		 <center>
		<button type="submit" name="massadd" class="btn btn-success">Add</button>
		</center>
		</form>
		</div>
		</div>
		
		  <div class="panel-body">
			  <ul role="tablist" class="nav nav-pills"><li class="dropdown pull-right tabdrop hide"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-align-justify"></i> <b class="caret"></b></a><ul class="dropdown-menu"></ul></li>
				<li role="presentation" class="active"><a href="#authed" aria-controls="customers" role="tab" data-toggle="tab" aria-expanded="true">Authed</a></li>
				<li role="presentation" class=""><a href="#failed" aria-controls="managers" role="tab" data-toggle="tab" aria-expanded="false">Failed</a></li>
				<li role="presentation"><a href="#online" aria-controls="customers" role="tab" data-toggle="tab" aria-expanded="true">Online Now</a></li>
				<li role="presentation"><a href="#banned" aria-controls="customers" role="tab" data-toggle="tab" aria-expanded="true">Banned</a></li>
				
			</ul>
			  <div class="tab-content">
				<div id="authed" role="tabpanel" class="tab-pane active">
				<div class="container-fluid half-padding">
				
				<div id="buttons"></div><table id="example" class="table datatable display table-hover" cellspacing="0" width="100%">
				<thead>
				<tr>
				<th rowspan="1" colspan="1">Name</th>
				<th rowspan="1" colspan="1">CPUKey</th>
				<th rowspan="1" colspan="1">Days</th>
				<th rowspan="1" colspan="1">IP</th>
				<th rowspan="1" colspan="1"></th>
				</tr>
				</thead>
				<tbody>
				<tr role="row" class="odd">
				<?php 
				$disabled4 = ($numrank>4)?'disabled':'';
				$disabled3 = ($numrank>3)?'disabled':'';
				$disabled1 = ($numrank>1)?'disabled':'';
				$res = mysqli_query($con, 'SELECT * FROM `unshared_consoles`.`consoles` WHERE `banned`=0 AND `server`="'.$servername.'";');
				while($row = mysqli_fetch_assoc($res)){
				echo "<tr><td>".$row['name']."</td>
				<td>".$row['cpukey']."</td>
				<td>".$row['days']."</td>
				<td>".$row['ip']."</td>
				<td>";
				echo ($numrank<5)?"<a href='index.php?page=manage&I=".$row['cpukey']."'><i name='I' class='fa fa-info-circle'></i></a>":"";
				echo ($numrank<5)?"<a href='index.php?page=manage&P=".$row['cpukey']."' $disabled4><i name='P' class='fa fa-pencil' $disabled4></i></a>":"";
				echo ($numrank<4)?"<a href='index.php?page=manage&T=".$row['cpukey']."' $disabled3><i name='T' class='fa fa-trash-o' $disabled3></i></a>":"";
				echo ($numrank<3)?"<a href='index.php?page=manage&K=".$row['cpukey']."' $disabled1><i name='K' class='fa fa-download' $disabled1></i></a>":"";
				echo "</td></tr>";
				}
				?>
				
				</tbody>
				</table>
				
				</div>
				</div>
				<div id="failed" role="tabpanel" class="tab-pane">
								<div class="container-fluid half-padding">
				<div id="buttons"></div><table id="example" class="table datatable display table-hover" cellspacing="0" width="100%">
				<thead>
				<tr>
				
				<th rowspan="1" colspan="1">CPUKey</th>
				
				<th rowspan="1" colspan="1">IP</th>
				
				</tr>
				</thead>
				<tbody>
				<tr role="row" class="odd">
				<?php 
				$res = mysqli_query($con, 'SELECT * FROM `unshared_consoles`.`failed` WHERE `server`="'.$servername.'";');
				while($row = mysqli_fetch_assoc($res)){
				echo "<tr>
				<td>".$row['cpukey']."</td>
				<td>".$row['ip']."</td></tr>";
				}
				?>
				
				</tbody>
				</table>
				</div>

				</div>
				<div id="online" role="tabpanel" class="tab-pane">
								<div class="container-fluid half-padding">
				<div id="buttons"></div><table id="example" class="table datatable display table-hover" cellspacing="0" width="100%">
				<thead>
				<tr>
				<th rowspan="1" colspan="1">Name</th>
				<th rowspan="1" colspan="1">CPUKey</th>
				<th rowspan="1" colspan="1">Days</th>
				<th rowspan="1" colspan="1">IP</th>
				<th rowspan="1" colspan="1"></th>
				</tr>
				</thead>
				<tbody>
				<tr role="row" class="odd">
				<?php 
				$res = mysqli_query($con, 'SELECT * FROM `unshared_consoles`.`consoles` WHERE `lastconnection` > now() - INTERVAL 5 MINUTE AND `server`="'.$servername.'";');
				while($row = mysqli_fetch_assoc($res)){
				echo "<tr><td>".$row['name']."</td>
				<td>".$row['cpukey']."</td>
				<td>".$row['days']."</td>
				<td>".$row['ip']."</td>
				<td>";
				echo ($numrank<5)?"<a href='index.php?page=manage&I=".$row['cpukey']."'><i name='I' class='fa fa-info-circle'></i></a>":"";
				echo ($numrank<5)?"<a href='index.php?page=manage&P=".$row['cpukey']."' $disabled4><i name='P' class='fa fa-pencil' $disabled4></i></a>":"";
				echo ($numrank<4)?"<a href='index.php?page=manage&T=".$row['cpukey']."' $disabled3><i name='T' class='fa fa-trash-o' $disabled3></i></a>":"";
				echo ($numrank<3)?"<a href='index.php?page=manage&K=".$row['cpukey']."' $disabled1><i name='K' class='fa fa-download' $disabled1></i></a>":"";
				echo "</td></tr>";
				}
				?>
				
				</tbody>
				</table>
				</div>

				</div>
				<div id="banned" role="tabpanel" class="tab-pane">
								<div class="container-fluid half-padding">
				<div id="buttons"></div><table id="example" class="table datatable display table-hover" cellspacing="0" width="100%">
				<thead>
				<tr>
				<th rowspan="1" colspan="1">Name</th>
				<th rowspan="1" colspan="1">CPUKey</th>
				<th rowspan="1" colspan="1">IP</th>
				</tr>
				</thead>
				<tbody>
				<?php 
				$res = mysqli_query($con, 'SELECT * FROM `unshared_consoles`.`consoles` WHERE `banned`=1 AND `server`="'.$servername.'";');
				while($row = mysqli_fetch_assoc($res)){
				echo "<tr><td>".$row['name']."</td>
				<td>".$row['cpukey']."</td>
				<td>".$row['ip']."</td></tr>";
				}
				?>
				
				</tbody>
				</table>
				</div>

				</div>
			  </div>
			</div>
		</div>
	  </div>
	</div>
	</div>

			  <?php
            echo '</div>
          </div>';	
			}
			elseif($page == 'tokens'){
				if(!mysqli_query($con, "INSERT INTO `unshared_users`.`feed` (`user`, `action`, `glyph`) VALUES ('".$userr."', 'Is on page: Tokens', 'fa fa-key');")){
							echo '<div role="alert" class="alert alert-danger alert-dismissible">
                            <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button><i class="alert-ico fa fa-fw fa-ban"></i><strong>Failed!&thinsp;</strong>
					Failed To Set Your Presence Feed!
				</div>';
						}
				echo '<div class="scrollable scrollbar-macosx">
            <div class="main__cont">
              <div class="main-heading">
                <div class="main-title">
                  <ol class="breadcrumb">
                    <li class="active">Tokens</li>
                  </ol>
                </div>
              </div>';
			  ?>
				<div class="row">
<div class="col-md-4">
	  <div class="panel panel-success">
		<div class="panel-heading">
		  <h3 class="panel-title">Generate Token</h3>
		</div>
		<div class="panel-body">
		  <form method="post" action="index.php?doo=1">
						  
						  <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Amount</label>
                              <div class="col-sm-10">
                                  <input name="amount" type="number" value="0" class="form-control" <?php echo ($numrank>3)?"disabled":""?>>
                              </div>
                          </div><br/><br/>
						  <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Value</label>
                              <div class="col-sm-10">
                                  <input name="days" type="number" value="0" class="form-control" <?php echo ($numrank>3)?"disabled":""?>>
                              </div>
                          </div><br/><br/>
						  <center>
							<button type="submit" name="gensubmit" class="btn btn-success" <?php echo ($numrank>3)?"disabled":""?>>Generate</button>
							</center>
						</form>
		  </div>
		</div>
		</div>
		
	
	<div class="col-md-8">
	  <div class="panel panel-danger">
		<div class="panel-heading">
		  <h3 class="panel-title">Tokens</h3>
		</div>
		<div class="panel-body">
		  <div class="panel-body">
			  <ul role="tablist" class="nav nav-pills"><li class="dropdown pull-right tabdrop hide"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-align-justify"></i> <b class="caret"></b></a><ul class="dropdown-menu"></ul></li>
				<?php
				echo ($numrank<4)?'<li role="presentation" ><a href="#all" aria-controls="customers" role="tab" data-toggle="tab" aria-expanded="true">All Tokens</a></li>':'';
				?>
				<li role="presentation" <?php echo ($numrank>3)?"class='active'":""?>class=""><a href="#yours" aria-controls="managers" role="tab" data-toggle="tab" aria-expanded="false">Your Tokens</a></li>
				
				
			</ul>
			  <div class="tab-content">
				<div id="all" role="tabpanel" class="tab-pane <?php echo ($numrank>3)?"":"active"?>">
				<div class="container-fluid half-padding">
				<div id="buttons"></div><table id="example" class="table datatable display table-hover" cellspacing="0" width="100%">
				<thead>
				<tr>
				<th rowspan="1" colspan="1">Token</th>
				<th rowspan="1" colspan="1">Days</th>
				<th rowspan="1" colspan="1">Used</th>
				<th rowspan="1" colspan="1">Used By</th>
				<th rowspan="1" colspan="1"></th>
				</tr>
				</thead>
				<tbody>
				<tr role="row" class="odd">
				<?php 
				$res = mysqli_query($con, 'SELECT * FROM `unshared_consoles`.`tokens` WHERE `server`="'.$servername.'";');
				while($row = mysqli_fetch_assoc($res)){
				echo "<tr><td>".$row['token']."</td>
				<td>".$row['time']."</td>
				<td>".$row['used']."</td>
				<td>".$row['redeemedbycpukey']."</td>
				</tr>";
				}
				?>
				
				</tbody>
				</table>
				</div>
				</div>
				<div id="yours" role="tabpanel" class="tab-pane <?php echo ($numrank>3)?"active":""?>">
								<div class="container-fluid half-padding">
				<div id="buttons"></div><table id="example" class="table datatable display table-hover" cellspacing="0" width="100%">
				<thead>
				<tr>
				<th rowspan="1" colspan="1">Token</th>
				<th rowspan="1" colspan="1">Days</th>
				<th rowspan="1" colspan="1">Used</th>
				<th rowspan="1" colspan="1">Used By</th>
				<th rowspan="1" colspan="1"></th>
				</tr>
				</thead>
				<tbody>
				<tr role="row" class="odd">
				<?php 
				$toks = "SELECT * FROM unshared_consoles.tokens WHERE generatedby='".$userr."';";
				$res = mysqli_query($con, $toks);
				while($row = mysqli_fetch_assoc($res)){
				echo "<tr><td>".$row['token']."</td>
				<td>".$row['time']."</td>
				<td>".$row['used']."</td>
				<td>".$row['redeemedbycpukey']."</td>
				</tr>";
				}
				?>
				
				</tbody>
				</table>
				</div>

				</div>
				
				</div>

				</div>
			  </div>
			</div>
		</div>
	  </div>
	</div>
	</div>
	<?php
            echo '</div>
          </div>';	
			}
			elseif($page == 'denied'){
				echo '<div class="scrollable scrollbar-macosx">
            <div class="main__cont">
              <div class="main-heading">
                <div class="main-title">
                  <ol class="breadcrumb">
                    <li class="active">Chat</li>
                  </ol>
                </div>
              </div>';
					
            echo '</div>
          </div>';	
			}
			elseif($page == 'admin'){
				 if($numrank > 2) echo '<script>document.ready(function(){$("#myModal").modal("show");window.setTimeout(function(){window.location.href = "'.url().'index.php?page=dash";}, 5000);});</script>';
				if(isset($_POST['restart'])){
					chdir("C:\Users\Administrator\Desktop\BETA");
					exec('start /B "Restarting Server...." "restart_serv.bat"',$output,$return);
					$cmdsuccess = true;
					}elseif(isset($_POST['clearc'])){
						chdir("C:\Users\Administrator\Desktop\BETA");
						exec('start /B "Starting Server...." "clearc.bat"',$output,$return);
						$cmdsuccess = true;
					}elseif(isset($_POST['output'])){
						$cmd = "toutput";
						$conn = fsockopen("172.93.137.113", 1837, $errnumber, $errmsg, 15);
						if($conn) {
							if($cmd != "error") {
								fwrite($conn, $authkey . ";" . $cmd . ";127.0.0.1");
								while(!feof($conn))
									echo fgets($conn, 128);
								fclose($conn);
								$cmdsuccess = true;
							}else{
								fclose($conn);
								$cmdsuccess = false;
							}
						}
					}elseif(isset($_POST['start'])){
						chdir("C:\Users\Administrator\Desktop\BETA");
						exec('start /B "Starting Server...." "start_server_bg.bat"',$output,$return);
						$cmdsuccess = true;
						//exec('\"Desktop\\BETA\\'.$servername.'.exe\"  2>&1'); 
					}elseif(isset($_POST['ping'])){
						$cmd = "ping";
						$conn = fsockopen("172.93.137.113", 1837, $errnumber, $errmsg, 15);
						if($conn) {
							if($cmd != "error") {
								fwrite($conn, $authkey . ";" . $cmd . ";127.0.0.1");
								while(!feof($conn))
									echo fgets($conn, 128);
								fclose($conn);
								$online = true;
							}else{
								fclose($conn);
								$online = false;
							}
						}
					}elseif(isset($_POST['kill'])){
						chdir("C:\Users\Administrator\Desktop\BETA");
						exec('start /B "Starting Server...." "stop_serv.bat"',$output,$return);
						$cmdsuccess = true;
					}elseif(isset($_POST['notifysubmit'])){
						file_put_contents('C:\Users\Administrator\Desktop\BETA\bin\stuff\xosc.txt', $_POST['xosc']);
						file_put_contents('C:\Users\Administrator\Desktop\BETA\bin\stuff\xam.txt', $_POST['xam']);
						file_put_contents('C:\Users\Administrator\Desktop\BETA\bin\stuff\welcome.txt', $_POST['welcome']);
						file_put_contents('C:\Users\Administrator\Desktop\BETA\bin\stuff\updates.txt', $_POST['updates']);
						$changesuccess = true;
						
					}elseif(isset($_POST['upload'])){
						$target_dir = "C:\Users\Administrator\Desktop\BETA\bin\client\'";
						$target_file = $target_dir . basename($_FILES["xex"]["name"]);
						$uploadOk = 1;
						$FileType = pathinfo($target_file,PATHINFO_EXTENSION);
						
						if (file_exists($target_file)) {
							unlink($target_file);
							$fileerrors = 'File Already Exists';
						}
						
						if ($_FILES["xex"]["size"] > 500000) {
							$fileerrors = 'File Is Too Big';
						}
						
						if($FileType != "xex") {
							$fileerrors = 'File Is Not a Valid XEX';
						}
						
						if (move_uploaded_file($_FILES["xex"]["tmp_name"], $target_file)) {
							$filesuccess = true;
						} else {
							$fileerrors = 'Failed To Upload XEX!';
						}

					}else{
						if(!mysqli_query($con, "INSERT INTO `unshared_users`.`feed` (`user`, `action`, `glyph`) VALUES ('".$userr."', 'Is on page: Admin', 'fa fa-lock');")){
							echo '<div role="alert" class="alert alert-danger alert-dismissible">
                            <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button><i class="alert-ico fa fa-fw fa-ban"></i><strong>Failed!&thinsp;</strong>
					Failed To Set Your Presence Feed!
				</div>';
						}
					}
				echo '<div class="scrollable scrollbar-macosx">
            <div class="main__cont">
              <div class="main-heading">
                <div class="main-title">
                  <ol class="breadcrumb">
                    <li class="active">Admin</li>
                  </ol>
                </div>
              </div>';
			  ?>
				<div class="row">
	<div class="col-md-6">
		<div class="row">
			<div class="col-md-12">
			<div class="panel panel-info">
			<div class="panel-heading">
			  <h3 class="panel-title">Activity feed</h3>
			</div>
			<div class="feed-widget">
			  <div class="scroll-wrapper feed-widget__wrap scrollable scrollbar-macosx" style="position: relative;"><div class="feed-widget__wrap scrollable scrollbar-macosx scroll-content" style="height: 252px; margin-bottom: 0px; margin-right: 0px; max-height: none;">
				<div class="feed-widget__cont">
				  <div class="feed-widget__list"><?php
				  $res = mysqli_query($con, 'SELECT * FROM `unshared_users`.`feed` WHERE `server`="'.$servername.'" ORDER BY id DESC LIMIT 6;');
				while($row = mysqli_fetch_assoc($res)){
					$date1 = strtotime("now");
					$date2 = new DateTime($row['time']);
					// Get DateInterval Object
					$before = strtotime("+6 hours", $row['time']);
					$seconds = $date1 - $row['time'];//$before
					$minutes = round($seconds / 60);
					$hours = round($minutes / 60);
					$ago = "$hours hours and $minutes minutes ago";
					echo '<div class="feed-widget__item feed-widget__item_user">
					  <div class="feed-widget__ico"><i class="'.$row['glyph'].'"></i></div>
					  <div class="feed-widget__info">
						<div class="feed-widget__text"><b>'.$row['user'].'</b>  '.$row['action'].'</div>
						<div class="feed-widget__date"></div>
					  </div>
					</div>';
					}
					?>
					  </div>
					</div>
				  </div>
				</div>
			  </div><div class="scroll-element scroll-x"><div class="scroll-element_outer"><div class="scroll-element_size"></div><div class="scroll-element_track"></div><div class="scroll-bar" style="width: 96px;"></div></div></div><div class="scroll-element scroll-y"><div class="scroll-element_outer"><div class="scroll-element_size"></div><div class="scroll-element_track"></div><div class="scroll-bar" style="height: 96px; top: 0px;"></div></div></div></div>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="col-md-12">
			<div class="panel panel-success">
			<div class="panel-heading">
			  <h3 class="panel-title">Server Commands</h3>
			</div>
			<div class="panel-body">
				<?php
				if(isset($_POST['restart']) || isset($_POST['clearc']) || isset($_POST['output']) || isset($_POST['ping']) || isset($cmdsuccess))
				if($cmdsuccess == false){
					echo '<div role="alert" class="alert alert-danger alert-dismissible">
                            <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button><i class="alert-ico fa fa-fw fa-ban"></i><strong>Failed!&thinsp;</strong>
					The Command Server Encountered An Error While Attempting To Send Command!
				</div>';
				}if(online == false){
					echo '<div role="alert" class="alert alert-danger alert-dismissible">
                            <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button><i class="alert-ico fa fa-fw fa-ban"></i><strong>Offline!&thinsp;</strong>
					The Server Appears To Be Offline. Try Starting it.
				</div>';
				}elseif($cmdsuccess == true){
					echo '<div role="alert" class="alert alert-success alert-dismissible">
								<button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button><i class="alert-ico fa fa-fw fa-check"></i><strong>Success!&thinsp;</strong>
						The Command Server Sent Your Command Successfully!
					</div>';
					}elseif($online == true){
						echo '<div role="alert" class="alert alert-success alert-dismissible">
								<button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button><i class="alert-ico fa fa-fw fa-check"></i><strong>Online!&thinsp;</strong>
						The Server is Online!
					</div>';
					}elseif($sell != ''){
					echo '<div role="alert" class="alert alert-danger alert-dismissible">
                            <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button><i class="alert-ico fa fa-fw fa-ban"></i><strong>Failed!&thinsp;</strong>
					'.$shell.'
				</div>';
					}
				?>
			  <div class="form-group">

						<form method="post" action="index.php?page=admin">
							<button name="restart" class="btn btn-block btn-danger">Restart Server</button>
						
						<br/>
						
							<button name="clearc" class="btn btn-block btn-warning">Clear Console</button>
						
						<br/>
						<button name="kill" class="btn btn-block btn-danger"  <?php echo ($numrank>1)?"disabled":""?>>Kill Server</button>
						
						<br/>
						<button name="start" class="btn btn-block btn-success">Start Server</button>
						
						<br/>
						
							<button name="output" class="btn btn-block btn-info"  <?php echo ($numrank>1)?"disabled":""?>>Toggle Console Output</button>
						
						<br/>
						
							<button name="ping" class="btn btn-block btn-success">Ping Server</button>
						</form>
					</div>
			  </div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="col-md-12">
			<div class="panel panel-success">
			<div class="panel-heading">
			  <h3 class="panel-title">Notify's</h3>
			</div>
			<div class="panel-body">
				<?php
				if(isset($_POST['notifysubmit'])){
					if($changesuccess == false){
						echo '<div role="alert" class="alert alert-danger alert-dismissible">
								<button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button><i class="alert-ico fa fa-fw fa-ban"></i><strong>Failed!&thinsp;</strong>
						An Error Occurent While Changing The Xnotify!
					</div>';
					}elseif($changesuccess == true){
						echo '<div role="alert" class="alert alert-success alert-dismissible">
									<button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button><i class="alert-ico fa fa-fw fa-check"></i><strong>Success!&thinsp;</strong>
							Xnotify Successfully Changed!
						</div>';
						}
				}
				?>
					<form action="index.php?page=admin" method="post">
                      <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">XOSC Notify</label>
                        <div class="col-sm-10">
                          <input  <?php echo ($numrank>1)?"disabled":""?> type="text" name="xosc" class="form-control" id="inputName" placeholder="XOSC Notify" value="<?php echo file_get_contents('C:\Users\Administrator\Desktop\BETA\bin\stuff\xosc.txt'); ?>">
                        </div>
						</div><br/>
						<div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">Challenge Notify</label>
                        <div class="col-sm-10">
                          <input  <?php echo ($numrank>1)?"disabled":""?> type="text" name="xam" class="form-control" id="inputName" placeholder="Challenge Notify" value="<?php echo file_get_contents('C:\Users\Administrator\Desktop\BETA\bin\stuff\xam.txt'); ?>">
                        </div>
                      </div><br/>
					  <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">Welcome Notify</label>
                        <div class="col-sm-10">
                          <input  <?php echo ($numrank>1)?"disabled":""?> type="text" name="welcome" class="form-control" id="inputName" placeholder="Challenge Notify" value="<?php echo file_get_contents('C:\Users\Administrator\Desktop\BETA\bin\stuff\welcome.txt'); ?>">
                        </div>
                      </div><br/>
					  <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">Welcome Message (Updates)</label>
                        <div class="col-sm-10">
                          <textarea  <?php echo ($numrank>1)?"disabled":""?> placeholder="server update notes" rows="7" name="updates" class="form-control"><?php echo file_get_contents('C:\Users\Administrator\Desktop\BETA\bin\stuff\updates.txt'); ?></textarea>
                        </div>
                      </div><br/>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" name="notifysubmit" class="btn btn-success"  <?php echo ($numrank>1)?"disabled":""?>>Save Changes</button>
                        </div>
                      </div>
					</form>
			  </div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-4">
	  <div class="panel panel-success">
		<div class="panel-heading">
		  <h3 class="panel-title">Upload New Client</h3>
		</div>
		<div class="panel-body">
		  <form method="post" action="index.php?page=admin">
						  <?php
							if(isset($fileerrors)){
								echo '<div role="alert" class="alert alert-danger alert-dismissible">
								<button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button><i class="alert-ico fa fa-fw fa-upload"></i><strong>Failed!&thinsp;</strong>
						'.$fileerrors.'
					</div>';
							}elseif(isset($filesuccess)){
								echo '<div role="alert" class="alert alert-success alert-dismissible">
								<button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button><i class="alert-ico fa fa-fw fa-upload"></i><strong>Success!&thinsp;</strong>
						Successfully upload new xex!
					</div>';
							}
						  ?>
						  <div class="form-group">
                              <br/><br/>
                              <div class="col-sm-10">
                                  <input type="file" name="xex" id="xex">
                              </div>
                          </div><br/>
						  <hr>
						  <center>
							<button type="submit" name="upload" class="btn btn-sm btn-success" <?php echo ($numrank>1)?"disabled":""?>>Upload</button>
							</center>
							<br/>
							<br/>
						</form>
		  </div>
		</div>
		</div>
	<div class="col-md-8">
	  <div class="panel panel-danger">
		<div class="panel-heading">
		  <h3 class="panel-title">Staff</h3>
		</div>
		<div class="panel-body">
		  <div class="container-fluid half-padding">
				<div id="buttons"></div><table id="example" class="table datatable display table-hover" cellspacing="0" width="100%">
				<thead>
				<tr>
				<th rowspan="1" colspan="1">Name</th>
				<th rowspan="1" colspan="1">Rank</th>
				<th rowspan="1" colspan="1">IP</th>
				</tr>
				</thead>
				<tbody>
				<?php 
				$res = mysqli_query($con, 'SELECT * FROM `unshared_users`.`member` WHERE `server`="'.$servername.'";');
				while($row = mysqli_fetch_assoc($res)){
				$num = $row['security'];
				if($num == 1) $ranking = "Owner";
				elseif($num == 2) $ranking = "Admin";
				elseif($num== 3) $ranking = "Seller";
				elseif($num== 4) $ranking = "Token Seller";
				elseif($num == 5) $ranking = "Support";
				echo "<tr><td>".$row['username']."</td>
				<td>".$ranking."</td>
				<td>".$row['ip']."</td></tr>";
				}
				?>
				
				</tbody>
				</table>
				</div>
		</div>
	  </div>
</div>
</div>
<?php
            echo '</div>
          </div>';	
			}else{
			header("location: index.php?page=dash");		
			}
			
		}else{
			header("location: index.php?page=dash");		
			}
		?>
		  <!--end content-->
		  <div id="modal1" class="modal fade">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Denied</h4>
	  </div>
	  <div class="modal-body">
		<div role="alert" class="alert alert-danger">
                                  <h4><i class="alert-ico fa fa-fw fa-ban"></i><strong>Denied!&thinsp;</strong></h4>You do not have the appropriate permissions to access this page!
                                </div>
	  </div>
	  <div class="modal-footer">
		<button id="closemodal" type="button" data-dismiss="modal" class="btn btn-default">Close</button>
	  </div>
	</div>
  </div>
</div>
        </div>
      </div>
    </div>
    
<script src="libs/jquery/jquery.min.js"></script>
<script src="libs/bootstrap/js/bootstrap.min.js"></script>
<script src="libs/jquery.scrollbar/jquery.scrollbar.min.js"></script>
<script src="libs/bootstrap-tabdrop/bootstrap-tabdrop.min.js"></script>
<script src="libs/sparkline/jquery.sparkline.min.js"></script>
<script src="libs/ionrangeslider/js/ion.rangeSlider.min.js"></script>
<script src="libs/inputNumber/js/inputNumber.js"></script>
<script src="libs/bootstrap-switch/dist/js/bootstrap-switch.min.js"></script>
<script src="libs/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="libs/datatables/media/js/dataTables.bootstrap.js"></script>
<script src="js/template/table_data.js"></script>
<script src="js/main.js"></script>
<script src="js/demo.js"></script><!-- Analytics and visitor tracking scripts can be placed here -->
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
document.onready(function(){
	$('#closemodal').onclick(function(){
		window.location.href = '<?php echo url(); ?>?page=dash';
	}
});
	</script>
<?php
	if($page == 'admin' && $numrank > 2){
		echo "<script type='text/javascript'>window.onload = func
		function func(){
	$('#modal1').modal('show');
	window.setTimeout(function(){
		window.location.href = '".url()."?page=dash';
		}, 5000);
	}
		</script>";
	}
?>
<script type="text/javascript">
        setTimeout(function() {Ajax();}, 1000);
    </script>
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter34743530 = new Ya.Metrika({
                    id:34743530,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/34743530" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<script type="text/javascript">
    $('body').on('click', '.datalist-filter__search button', function() {
        yaCounter34743530.reachGoal('Filter');
    });
    $('body').on('click', '.quickmenu__item', function() {
        yaCounter34743530.reachGoal('Quick');
    });
    $('body').on('click', '.demo__theme', function() {
        yaCounter34743530.reachGoal('Theme');
    });
</script>
  </body>
  
</html>