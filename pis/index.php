<?PHP
ob_start();
session_start();
error_reporting(0);
require_once("inc/config/pdoconnect.php");
require_once("inc/config/pdofunctions.php");
$conn = new dbconnect();
if(isset($_POST['LOGIN'])){
	$login_success = '';
	try{
		echo 'test';
		$stmt = $conn->prepare("SELECT * FROM tbl_login WHERE log_status = 1 AND log_name=:logname AND log_pwd=:logpwd ");
		$data_login = array(
			'logname' => $_POST['username'],
			'logpwd' => StandardHash($_POST['password'])
		);
		$stmt->execute($data_login);
		if($stmt->rowCount() > 0){			
			$user_data = $stmt->fetch();
			$login_success = 'YES';			
			$_SESSION['_admin']=true;
			$_SESSION['_user']="CISADMIN";
			$_SESSION['_userid']=$user_data->log_id;
			$_SESSION['_username']=$user_data->usr_name;			
			$_SESSION['_usertype']=$user_data->usr_type;			
			$_SESSION["_sessiondtm"] = date("Ymd").date("His"); 
			$conn->query("UPDATE tbl_login SET log_dtm = '".date('Y-m-d H:i:s')."' WHERE log_id = '".$user_data->log_id."' ");
		}
	}catch (PDOException $e) {
		echo "Error!: ".$e->getMessage()."";
	}		
	$conn = null;
	if ($login_success != '') {
		header("location:home.php");
		die();
	}else{
		$_SESSION['_msg']="Invalid User Name / Password. Please Try Again..!";						
		$_SESSION['_admin']=false;
		$_SESSION['_user']="";		
		$_SESSION['_userid']="";				
		$_SESSION['_username']="";				
		$_SESSION['_usertype']="";
		$_SESSION["_sessiondtm"]="";		
		header("location:index.php");
		die();
	}
}
if(isset($_POST['FORGOT'])){
	$_SESSION['_msg']="Please contact admin..!";
	header("location:index.php");
	die();	
}
if($_SESSION['_user']!=""){
	header("location:home.php");
	die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<link rel="icon" href="favicon.ico" type="image/x-icon" />
    <title>Admin Login</title>
    <link href="dist/css/pages/login.css" rel="stylesheet">
    <link href="dist/css/style.min.css" rel="stylesheet">   
</head>
<body class="skin-default card-no-border">
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">CIS Admin</p>
        </div>
    </div>
    <section id="wrapper">
        <div class="login-register" style="background-image:url(img/login/login-bg.jpg);">
            <div class="login-box card">
                <div class="card-body">
                    <h3 class="text-center m-b-20">
					<img src="img/login/login-logo.jpg" style="width:70%;" alt=""> 
					</h3>
					<form class="form-horizontal form-material" id="loginform" method="post" autocomplete="off" action="">                        
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" name="username" id="username" type="email" required="" maxlength="40" value="admin@panirendar.com" placeholder="Username"> </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" name="password" id="password" type="password" required="" maxlength="15" placeholder="Password"> </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="d-flex no-block align-items-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" tabindex="-1" id="customCheck1">
                                        <label class="custom-control-label" for="customCheck1">Remember me</label>
                                    </div> 
                                    <div class="ml-auto m-t-10">
                                        <a href="javascript:void(0)" id="to-recover" tabindex="-1" class="text-muted"><i class="fas fa-lock m-r-5"></i> Forgot pwd?</a> 
                                    </div>
                                </div>
                            </div>
                        </div>
						<?php
							//$_SESSION['_msg'] = 'Invalid User Name / Password. Try Again..!';
							if($_SESSION['_msg']!=""){
								echo '<div class="form-group m-b-10"><div class="col-sm-12 text-center text-danger">';
								echo '<p>'.$_SESSION['_msg'].'</p>';
								echo '</div></div>';
								$_SESSION['_msg'] = "";
							}
						?>
                        <div class="form-group text-center">
                            <div class="col-xs-12 p-b-0">
                                <button name="LOGIN" class="btn btn-block btn-lg btn-info btn-rounded" type="submit">Log In</button>
                            </div>
                        </div>
                    </form>
                    <form class="form-horizontal" id="recoverform" method="post" autocomplete="off" action="" >
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <h3>Recover Password</h3>
                                <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" type="text" required="" placeholder="Email"> </div>
                        </div>
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button name="FORGOT" class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
	<script src="assets/modules/jquery/jquery-3.2.1.min.js"></script>
    <script src="assets/modules/popper/popper.min.js"></script>
    <script src="assets/modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $(".preloader").fadeOut();
        });
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        });
        $('#to-recover').on("click", function() {
            $("#loginform").slideUp();
            $("#recoverform").fadeIn();
        });
		jQuery(document).ready(function() {
			$("#username").focus();
		});
    </script>
</body>
</html>