<!DOCTYPE html>
<?php
	if(isset($_GET['logout'])) { session_destroy(); header("location: login.php"); }
	unset($_SESSION['user']);
	unset($_SESSION['pass']);
?>
<html lang="en">
  <head>
  <script>
		function login()
		{
			var elem = document.getElementById("loginbtn");
			var done = false;
			elem.innerHTML="<i class='fa fa-spinner fa-spin'></i> Loading";
			var user = $('#username').val();
			var pass = $('#password').val();
				setInterval(function(){
					if(done == false){
						$.ajax({
							type: "POST",
							url: 'doauth.php',
							data: {username: user, password: pass},
							success: function(data){
								var res = JSON.parse(data);
								//alert(res.message);
								if(res.status != 3){
									document.getElementById('fail').style.display = 'block';
									document.getElementById('fail').innerHTML = '<button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button><i class="alert-ico fa fa-fw fa-ban"></i><strong>Failed!&thinsp;</strong> ' + res.message;
									elem.innerHTML="Sign in";
									setInterval(function(){document.getElementById('fail').style.display = 'none';},1000);
									done = true;
								}else{
									document.getElementById('success').style.display = 'block';
									document.getElementById('success').innerHTML = document.getElementById('success').innerHTML + " " + res.message;
									elem.innerHTML="Sign in";
									setInterval(function(){window.location="index.php?page=dash"},1000);
								}
							}
						});
					}
				},2000);
		}
	</script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>XBLRocks &bull; Login</title>
    <link rel="icon" type="image/png" href="img/favicon.png">
    <link rel="apple-touch-icon-precomposed" href="img/apple-touch-favicon.png">
    <link href="libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic" rel="stylesheet" type="text/css">
    <link href="libs/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="libs/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
    <link href="css/right.css" rel="stylesheet">
    <link href="css/demo.css" rel="stylesheet"><!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
  </head>
  <body class="transparent_lilac">
    <div class="wrapper">
      <div class="login">
	  
        <form class="login__form">
          
		  <div><h2><font color="white"><center><img src="img/logo.png" class="img-circle" width="30">  XBLRocks Login  <img src="img/logo.png" class="img-circle" width="30"></center></font></h2></div>
	 
			<div id="success" style="display: none" role="alert" class="alert alert-success alert-dismissible">
                            <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button><i class="alert-ico fa fa-fw fa-check"></i><strong>Success!&thinsp;</strong>
			</div>
			
			<div id="fail" style="display: none" role="alert" class="alert alert-danger alert-dismissible">
                            <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button><i class="alert-ico fa fa-fw fa-ban"></i><strong>Failed!&thinsp;</strong>
			</div>
			
          <div class="input-group">
		  <div class="input-group-addon"><i class="fa fa-user"></i></div>
            <input type="text" placeholder="Username" id="username" name="username" class="form-control">
          </div><br/>
          <div class="input-group">
		  <div class="input-group-addon"><i class="fa fa-lock"></i></div>
            <input type="password" name="password" id="password" placeholder="Password" class="form-control">
          </div><br/>
          <div class="form-group login__action">
            
            <div class="login__submit">
              <button id="loginbtn" type="button" onclick="login()" class="btn btn-default">Sign in</button>
            </div>
          </div>
        
		
      </div>
    </div>
    
    <script src="libs/jquery/jquery.min.js"></script>
    <script src="libs/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/demo.js"></script>
  </body>
</html>