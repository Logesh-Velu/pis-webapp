<?php
ob_start();
session_start();
error_reporting(0);
require_once("inc/userclasses.php");
if(isset($_POST['SAVE'])){
	$_POST['log_pwd']=StandardHash($_POST['usr_paswd']);
	$_POST['usr_type'] = 'S';
	$_POST['usr_avatar'] = 'male.jpg';
	$_POST['lm_by'] = $_SESSION['_userid'];	
	$_POST['lm_dtm'] = date('Y-m-d H:i:s');	
	$ins_datas = $dbconn->CIS_InsertRecord("tbl_login",$_POST,1);
	$_SESSION['_msg'] = "User details has been successfully added..!";
	header("location:list-users.php");
	die();
}
if(isset($_POST['UPDATE'])){	
	if($_POST['usr_paswd'] != ''){
		$_POST['log_pwd']=StandardHash($_POST['usr_paswd']);
	}
	$_POST['lm_by'] = $_SESSION['_userid'];
	$_POST['lm_dtm'] = date('Y-m-d H:i:s');	
	$dbconn->CIS_UpdateRecord("tbl_login"," log_id = '".$_POST['txtHid']."' ",$_POST,1);	
	$_SESSION['_msg'] = "User details has been successfully updated..!";
	header("location:list-users.php");
	die();
}

if ($_REQUEST['token'] != ""){	
	$url_data = $converter->decode($_REQUEST['token']);
	$url_data = explode("~",$url_data);
    if($url_data[1] == $_SESSION["_sessiondtm"]){
		$_REQUEST['log_id'] = $url_data[0];
		$mstRes = $conn->prepare("SELECT * FROM tbl_login WHERE log_id=:log_id");	
		$data_select = array(
			'log_id' => $_REQUEST['log_id']
		);
		$mstRes->execute($data_select);	
		if ($mstRes->rowCount() > 0){
			$obj=$mstRes->fetch();
		}
		$mstRes = null;	
	}else{
		$_SESSION['_msg_err'] = "You don\'t have permission..!";	
		header("location:home.php");			
		die();
	}
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
    <title><?php echo PAGE_TITLE; ?> - Users</title>
    <link href="assets/modules/toast-master/css/jquery.toast.css" rel="stylesheet" type="text/css">
	<link href="dist/css/style.min.css" rel="stylesheet" type="text/css">
	<link href="dist/css/cis-style.css" rel="stylesheet" type="text/css">
	<link href="assets/modules/switchery/dist/switchery.min.css" rel="stylesheet" />
    <link href="dist/css/passwordscheck.css" rel="stylesheet" type="text/css">
</head>

<body class="skin-default fixed-layout">
    <?php echo PRELOADER; ?>
    <div id="main-wrapper">
        <?php require_once("inc/header.php"); ?>
        <?php require_once("inc/menus.php"); ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor">Users</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="home.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Users</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card">
					
					<div class="card-body">
						<div class="row">
							<div class="col-md-5 align-self-center">
								<h4 class="card-title">Add / Edit Users</h4>
							</div>
							<div class="col-md-7 align-self-center text-right">
								<div class="d-flex justify-content-end align-items-center">
									<a class="btn btn-outline-info" href="list-users.php"><i class="fa fa-list m-r-5"></i> List of Users</a>
								</div>
							</div>
						</div>
						<hr class="m-t-10 m-b-0">
						
						
						<div class="m-t-40">
							<form name='thisForm' class="" method='POST' action=""  data-toggle="validator" onSubmit="return fnValidate();" enctype="multipart/form-data">
								<div class="form-body">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">User Name <span class="text-danger">*</span></label>
												<input type="text" class="form-control name_only" required name="usr_name" maxlength="30" value="<?php echo $obj->usr_name; ?>"  />
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">Designation </label>
												<input type="text" class="form-control name_only" name="usr_dest" maxlength="30" value="<?php echo $obj->usr_dest; ?>"  />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">Email Id (Login Id) <span class="text-danger">*</span></label>
												<input type="email" class="form-control email_only" required name="log_name" maxlength="50" value="<?php echo $obj->log_name; ?>"  />
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">Mobile Number <span class="text-danger">*</span></label>
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text">+91</span>
													</div>
													<input type="tel" class="form-control input-sm" name="usr_mobile" id="usr_mobile" data-mask="9999999999" value="<?php echo $obj->usr_mobile; ?>" />
												</div>
											</div>
										</div>
									</div>
									<?php if($_REQUEST["log_id"]!='') { ?>
									<h3 class="box-title m-t-10 m-b-20"><span class="lowercase font-14"><input type="checkbox" value="1" name="pw_reset" id="pw_reset" class="js-switch" data-color="#f96262" data-size="small" /> &nbsp; <label for="pw_reset">Reset Password</label></span></h3>
									<?php } ?>		
									<div class="row resetpw">								
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputPassword" class="control-label">Password  <span class="text-danger">*</span></label>
												<input type="password" data-toggle="validator" data-minlength="6" class="form-control" placeholder="Enter the Password" maxlength="15" name="usr_paswd" id="usr_paswd" value=""  />
												<div id="colors"><span class="help-block">Minimum of 6 characters &nbsp; <span id="result_pw"></span></span></div>										
											</div>	
										</div>	
										<div class="col-md-6"	>
											<div class="form-group">
												<label class="control-label">Confirm Password  <span class="text-danger">*</span></label>
												<input type="password" class="form-control input-sm" name="usr_con_paswd" id="usr_con_paswd" maxlength="15" data-match="#usr_paswd" data-match-error="Whoops, password don't match" placeholder="Confirm" value=""  />
												<div class="help-block with-errors"></div>
											</div>
										</div>				
									</div>
									
								</div>
								<hr class="m-t-20 m-b-0">
								<div class="form-actions">
									<div class="card-body text-center">
									<?php if($_REQUEST["log_id"]!='') { ?>
									  <INPUT class="btn btn-success" type="submit" name="UPDATE" value="UPDATE">
									  <INPUT class="btn btn-secondary" type="button" name="cancel" value="CANCEL" onClick="javascript:history.go(-1);">
									  <input type="hidden" name="txtHid" id="txtHid" value="<?php echo $_REQUEST['log_id'];?>">
									  <input type="hidden" name="token" id="token" value="<?php echo $_REQUEST['token'];?>">
									  <?php }else{ ?>
									  <INPUT class="btn btn-success" type="submit" name="SAVE" value="SAVE">
									  <INPUT class="btn btn-secondary" type="button" name="cancel" value="CANCEL" onClick="javascript:history.go(-1);">
									  <input type="hidden" name="txtHid" id="txtHid" value="0">
									<?php } ?>
									</div>
								</div>
							</form>
							<hr class="m-t-0 m-b-20">
						</div>						
						
					</div>
				</div>
				                
				<?php require_once("inc/right-sidebar.php"); ?>
            </div>
        </div>
		<?php require_once('inc/footer.php'); ?>        
    </div>	
    <script src="assets/modules/jquery/jquery-3.2.1.min.js"></script>
    <script src="assets/modules/popper/popper.min.js"></script>
    <script src="assets/modules/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
    <script src="dist/js/waves.js"></script>
    <script src="dist/js/sidebarmenu.js"></script>
    <script src="dist/js/custom.min.js"></script>
    <script src="dist/js/pages/mask.js"></script>
	<script src="dist/js/validator.js"></script>
    <script src="dist/js/cisadmin.js"></script>
    <script src="dist/js/validate.js"></script>
    <script src="assets/modules/toast-master/js/jquery.toast.js"></script>
	<script src="assets/modules/switchery/dist/switchery.min.js"></script>
	<script type="text/javascript">
    $(document).ready(function() {
		<?php
			if($_SESSION['_msg']!=""){
				echo "$.toast({heading:'Success!',text:'".$_SESSION['_msg']."',position:'top-right',loaderBg:'#ff6849',icon:'success',hideAfter:3500,stack:6});";
				$_SESSION['_msg'] = "";
			}
			if($_SESSION['_msg_err']!=""){
				echo "$.toast({heading:'Error!',text:'".$_SESSION['_msg_err']."',position:'top-right',loaderBg:'#ed1c24',icon:'warning',hideAfter:3500,stack:6});";
				$_SESSION['_msg_err'] = "";
			}
		?>
		var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());
        });		
		
		$('#usr_paswd').keyup(function() {
			$('#result_pw').html(checkStrength($('#usr_paswd').val()))
		});
		
		<?php if($_REQUEST["log_id"]!='') { ?>
		var changeCheckbox = document.querySelector('#pw_reset');
		changeCheckbox.onchange = function() {
		  if($(this).is(':checked')){
			$(".resetpw").slideDown();
		  }else{
			$(".resetpw").slideUp();
		  }
		};
		$("#pw_reset").trigger('change');
		<?php } ?>
	});
	function fnValidate(){		
		if(isNull(document.thisForm.usr_name,"user name..!")){ return false; }
		if(notEmail(document.thisForm.log_name,"email id..!")){ return false; }
		if(isNull(document.thisForm.usr_mobile,"mobile number..!")){ return false; }
		if($("#txtHid").val() == 0){
			if(isPassword(document.thisForm.usr_paswd)){ return false; }
			if(Trim(document.thisForm.usr_paswd.value)!=Trim(document.thisForm.usr_con_paswd.value)){
				alert("Password and confirm password does not match..!"); 
				document.thisForm.usr_con_paswd.focus();
				return false;
			}
		}else{
			if($('#pw_reset').is(':checked')) { 
				if(isPassword(document.thisForm.usr_paswd)){ return false; }
				if(Trim(document.thisForm.usr_paswd.value)!=Trim(document.thisForm.usr_con_paswd.value)){
					alert("Password and confirm password does not match..!"); 
					document.thisForm.usr_con_paswd.focus();
					return false;
				}
			}			
		}
		document.thisForm.submit();		
	}
	</script>	
</body>
</html>