<?php
ob_start();
session_start();
error_reporting(0);
require_once("inc/userclasses.php");
if(isset($_POST['SAVE'])){
	$_POST['memb_code'] = strtoupper($_POST['memb_code']);
	$if_exist = $dbconn->GetSingleReconrd("tbl_members","memb_id","memb_code",$_POST['memb_code']);
	if($if_exist != ""){
		$_SESSION['_msg_err'] = "The member code is already exist..!";
		header("location:members-add.php");
		die();
	}	
	if ($_FILES['memb_photo']['name']!=""){						
		if(allowfile($_FILES['memb_photo']['name'],array('jpg','jpeg','png')) == false){
			$_SESSION['_msg_err'] = "Invalid Member Photo file selected..!";
			header("location:members-add.php");
			die();
		}
	}
	if ($_FILES['memb_photo']['name']!=""){						
		$_POST['memb_photo'] = post_img($_FILES['memb_photo']['name'], $_FILES['memb_photo']['tmp_name'],"photos/");
	}
	
	if($_POST['memb_fname_tamil'] != ""){
		$_POST['memb_tamil_update'] = 1;
	}
	$_POST['memb_regdt'] = date('Y-m-d');
	$_POST['lm_by'] = $_SESSION['_userid'];	
	$_POST['lm_dtm'] = date('Y-m-d H:i:s');
	$ins_datas = $dbconn->CIS_InsertRecord("tbl_members",$_POST,1);
	$_SESSION['_msg'] = "Customer details has been successfully added..!";
	header("location:members.php");
	die();
}
if(isset($_POST['UPDATE'])){	
	$_POST['memb_code'] = strtoupper($_POST['memb_code']);
	$if_exist = $dbconn->GetSingleReconrd("tbl_members","memb_id","memb_id <> ".$_POST['txtHid']." AND memb_code",$_POST['memb_code']);
	if($if_exist != ""){
		$_SESSION['_msg_err'] = "The member code is already exist..!";
		header("location:members-add.php?token=".$_POST['token']);
		die();
	}
	if ($_FILES['memb_photo']['name']!=""){						
		if(allowfile($_FILES['memb_photo']['name'],array('jpg','jpeg','png')) == false){
			$_SESSION['_msg_err'] = "Invalid Member Photo file selected..!";
			header("location:members-add.php?token=".$_POST['token']);
			die();
		}
	}
	if($_POST["hide_memb_photo"] != ""){
		removeFile("photos/".$_POST["hide_memb_photo"]);
	}
	if ($_FILES['memb_photo']['name']!=""){						
		$_POST['memb_photo'] = post_img($_FILES['memb_photo']['name'], $_FILES['memb_photo']['tmp_name'],"photos/");
	}
	
	if($_POST['memb_fname_tamil'] != ""){
		$_POST['memb_tamil_update'] = 1;
	}
	$_POST['lm_by'] = $_SESSION['_userid'];	
	$_POST['lm_dtm'] = date('Y-m-d H:i:s');
	$dbconn->CIS_UpdateRecord("tbl_members"," memb_id = '".$_POST['txtHid']."' ",$_POST,1);	
	$_SESSION['_msg'] = "Customer details has been successfully updated..!";
	if($_REQUEST['mode'] == 'auto'){
		header("location:members-tamil.php");
	}else{
		header("location:members.php");
	}
	die();
}
$memb_city = 1;
$memb_native = 1;
$memb_group = 1;
$memb_state = 'Tamilnadu';
$memb_state_tamil = 'தமிழ்நாடு';
if ($_REQUEST['token'] != ""){	
	$url_datas = $converter->decode($_REQUEST['token']);
	$url_data = explode("~",$url_datas);
    if(trim($url_data[1]) == trim($_SESSION["_sessiondtm"])){
		$_REQUEST['memb_id'] = $url_data[0];
		$mstRes = $conn->prepare("SELECT * FROM tbl_members WHERE memb_id=:memb_id");	
		$data_select = array(
			'memb_id' => $_REQUEST['memb_id']
		);
		$mstRes->execute($data_select);	
		if ($mstRes->rowCount() > 0){
			$obj=$mstRes->fetch();
			$memb_group=$obj->memb_group;
			$memb_city=$obj->memb_city;
			$memb_native=$obj->memb_city;
			$memb_state=$obj->memb_state;
			if($obj->memb_state_tamil != ""){
				$memb_state_tamil=$obj->memb_state_tamil;
			}
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
    <title><?php echo PAGE_TITLE; ?> - Add Members</title>
    <link href="assets/modules/toast-master/css/jquery.toast.css" rel="stylesheet" type="text/css">
	<link href="dist/css/style.min.css" rel="stylesheet" type="text/css">
	<link href="dist/css/cis-style.css" rel="stylesheet" type="text/css">
	<link href="assets/modules/custom-select/custom-select.css" rel="stylesheet" type="text/css" />	
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
                        <h4 class="text-themecolor">Members</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="home.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Members</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card">
					
					<div class="card-body">
						<div class="row">
							<div class="col-md-5 align-self-center">
								<h4 class="card-title">Add / Edit Members</h4>
							</div>
							<div class="col-md-7 align-self-center text-right">
								<div class="d-flex justify-content-end align-items-center">
									<a class="btn btn-outline-info" href="members.php"><i class="fa fa-list m-r-5"></i> List of Members</a>
								</div>
							</div>
						</div>
						<hr class="m-t-10 m-b-0">
						
						
						<div class="m-t-40">
							<form name='thisForm' class="" method='POST' action=""  data-toggle="validator" onSubmit="return fnValidate();" enctype="multipart/form-data">
								<div class="form-body">
									<div class="row">										
										<div class="col-md-3">
											<div class="form-group">
												<label class="control-label">Group <span class="text-danger">*</span></label>
												<select name="memb_group" id="memb_group" data-placeholder="Choose a Group.." class="select2 form-control">
												<option value='1'>Group 1</option> 
												<option value='2'>Group 2</option> 
												<option value='3'>Group 3</option> 
												<option value='4'>Group 4</option> 
												<option value='5'>Group 5</option> 
												</select>
												<script>document.thisForm.memb_group.value="<?php echo $memb_group; ?>";</script>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label class="control-label">Member Code <span class="text-danger">*</span></label>
												<input type="text" class="form-control splname_only uppercase" required name="memb_code" maxlength="6" value="<?php echo $obj->memb_code; ?>"  />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-5">
											<div class="form-group">
												<label class="control-label">Member Name <span class="text-danger">*</span></label>
												<input type="text" class="form-control splname_only" required name="memb_fname" maxlength="40" value="<?php echo $obj->memb_fname; ?>"  />
											</div>
										</div>
										<div class="col-md-1 p-l-0">
											<div class="form-group">
												<label class="control-label">Initial</label>
												<input type="text" class="form-control splname_only" required name="memb_initial" maxlength="10" value="<?php echo $obj->memb_initial; ?>"  />
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label class="control-label">Member Name in Tamil</label>
												<input type="text" class="form-control" name="memb_fname_tamil" maxlength="100" value="<?php echo $obj->memb_fname_tamil; ?>"  />
											</div>
										</div>
										<div class="col-md-1 p-l-0">
											<div class="form-group">
												<label class="control-label">Initial</label>
												<input type="text" class="form-control" name="memb_initial_tamil" maxlength="20" value="<?php echo $obj->memb_initial_tamil; ?>"  />
											</div>
										</div>										
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">Father / Husband Name</label>
												<input type="text" class="form-control splname_only" name="memb_lname" maxlength="40" value="<?php echo $obj->memb_lname; ?>"  />
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">Father / Husband Name in Tamil</label>
												<input type="text" class="form-control" name="memb_lname_tamil" maxlength="100" value="<?php echo $obj->memb_lname_tamil; ?>"  />
											</div>
										</div>
									</div>	

									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">Spouse Name</label>
												<input type="text" class="form-control splname_only" name="memb_sname" maxlength="40" value="<?php echo $obj->memb_sname; ?>"  />
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">Spouse Name in Tamil</label>
												<input type="text" class="form-control" name="memb_sname_tamil" maxlength="100" value="<?php echo $obj->memb_sname_tamil; ?>"  />
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">Address Line 1 <span class="text-danger">*</span></label>
												<input type="text" class="form-control address_only" required name="memb_add1" maxlength="100" value="<?php echo $obj->memb_add1; ?>"  />
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">Address Line 1 in Tamil</label>
												<input type="text" class="form-control" name="memb_add1_tamil" maxlength="200" value="<?php echo $obj->memb_add1_tamil; ?>"  />
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">Address Line 2 </label>
												<input type="text" class="form-control address_only" name="memb_add2" maxlength="100" value="<?php echo $obj->memb_add2; ?>"  />
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">Address Line 2 in Tamil</label>
												<input type="text" class="form-control" name="memb_add2_tamil" maxlength="200" value="<?php echo $obj->memb_add2_tamil; ?>"  />
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label class="control-label">City Name <span class="text-danger">*</span></label>
												<select name="memb_city" id="memb_city" data-placeholder="Choose a city.." class="select2 form-control">
												<option></option> 
												<?php echo $dbconn->fnFillComboFromTable_Where("city_id","city_name","mst_city","city_name"," WHERE city_status = 1"); ?></select> 
												<script>document.thisForm.memb_city.value="<?php echo $memb_city; ?>";</script>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label class="control-label">State <span class="text-danger">*</span></label>
												<input type="text" class="form-control name_only" required name="memb_state" maxlength="30" value="<?php echo $memb_state; ?>"  />
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label class="control-label">State in Tamil </label>
												<input type="text" class="form-control" name="memb_state_tamil" maxlength="50" value="<?php echo $memb_state_tamil; ?>"  />
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label class="control-label">PIN Code</label>
												<input type="text" class="form-control name_only" name="memb_pincode" data-mask="999999" maxlength="6" value="<?php echo $obj->memb_pincode; ?>"  />
											</div>
										</div>										
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">Mobile Number</label>
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text">+91</span>
													</div>
													<input type="tel" class="form-control input-sm" name="memb_mobile" id="memb_mobile" data-mask="9999999999" value="<?php echo $obj->memb_mobile; ?>" />
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">Email Id</label>
												<input type="email" class="form-control email_only" name="memb_email" maxlength="50" value="<?php echo $obj->memb_email; ?>"  />
											</div>
										</div>
									</div>	
									
									
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">Native City <span class="text-danger">*</span></label>
												<select name="memb_native" id="memb_native" data-placeholder="Choose a city.." class="select2 form-control">
												<option></option> 
												<?php echo $dbconn->fnFillComboFromTable_Where("city_id","city_name","mst_city","city_name"," WHERE city_status = 1"); ?></select> 
												<script>document.thisForm.memb_native.value="<?php echo $memb_native; ?>";</script>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group m-b-0">
												<label class="control-label">Member Photo <small>(Passport Size)</small></label>
												<div class="custom-file">
												  <input type="file" class="custom-file-input image_only" name="memb_photo" id="memb_photo" data-toggle="tooltip" data-original-title="Image Only, Below 500KB" data-size="500" data-submit='1'>
												  <label class="custom-file-label form-control" for="memb_photo">Choose file</label>
												  <input type="hidden" name="hide_memb_photo" value="<?php echo $obj->memb_photo; ?>">
												  <small id="memb_photo_error" class="cis-feedback"></small>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label class="control-label">Remarks if any</label>
												<textarea name="memb_remarks" id="memb_remarks" maxlength="250" class="form-control"><?php echo $obj->memb_remarks; ?></textarea>
											</div>
										</div>
									</div>									
								</div>
								<hr class="m-t-30 m-b-0">
								<div class="form-actions">
									<div class="card-body text-center">
									<?php if($_REQUEST["memb_id"]!='') { ?>
									  <INPUT class="btn btn-success" type="submit" name="UPDATE" value="UPDATE">
									  <INPUT class="btn btn-secondary" type="button" name="cancel" value="CANCEL" onClick="javascript:history.go(-1);">
									  <input type="hidden" name="txtHid" id="txtHid" value="<?php echo $_REQUEST['memb_id'];?>">
									  <input type="hidden" name="token" id="token" value="<?php echo $_REQUEST['token'];?>">
									  <input type="hidden" name="mode" id="mode" value="<?php echo $_REQUEST['mode'];?>">
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
	<script src="assets/modules/custom-select/custom-select.min.js" type="text/javascript"></script>
    
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
		
		// For select 2
		$(".select2").select2();
	});
	
	function fnValidate(){		
		if(isNull(document.thisForm.memb_fname,"first name..!")){ return false; }
		if(isNull(document.thisForm.memb_add1,"address..!")){ return false; }
		if(notSelected(document.thisForm.mst_city,"city..!")){ return false; }
		if(document.thisForm.memb_email.value != ""){
			if(notEmail(document.thisForm.memb_email,"email id..!")){ return false; }	
		}		
		document.thisForm.submit();		
	}
	</script>	
</body>
</html>