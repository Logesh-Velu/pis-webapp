<?php
ob_start();
session_start();
error_reporting(0);
require_once("inc/userclasses.php");
if(isset($_POST['UPDATE'])){
	$sm_ids = '0,';
	for($y=0;$y<count($_REQUEST['Chk']);$y++){
		if($_REQUEST['Chk'][$y] != 0){
			$sm_ids .= $_REQUEST['Chk'][$y].',';
		}
	}
	$sm_ids .= '0';
	$log_exist = $dbconn->GetSingleReconrd("tbl_user_rights","auto_id","mm_id = '".$_REQUEST['mm_id']."' AND log_id",$_REQUEST['txtHid']);
	if($log_exist != ""){
		$_POST['log_id'] = $_POST['txtHid'];
		$_POST['sm_ids'] = $sm_ids;
		$dbconn->CIS_UpdateRecord("tbl_user_rights"," auto_id = '".$log_exist."' ",$_POST,1);	
	}else{
		$_POST['log_id'] = $_POST['txtHid'];
		$_POST['sm_ids'] = $sm_ids;
		$ins_datas = $dbconn->CIS_InsertRecord("tbl_user_rights",$_POST,1);
	}
	$conn->query("DELETE FROM tbl_user_rights WHERE sm_ids = '0,0' AND log_id = ".$_REQUEST['txtHid']);
	$_SESSION['_msg'] = "User rights has been successfully updated..!";
	header("location:mst-users-rights.php?token=".$_POST['token']."&mm_id=".$_REQUEST['mm_id']);
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
			if ($_REQUEST['mm_id'] == ""){
				$_REQUEST['mm_id'] = $dbconn->GetLastRecord("mst_main_menu","mm_id","mm_show",1,"mm_id");
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
    <title><?php echo PAGE_TITLE; ?> - User Rights</title>
    <link href="assets/modules/toast-master/css/jquery.toast.css" rel="stylesheet" type="text/css">
	<link href="dist/css/style.min.css" rel="stylesheet" type="text/css">
	<link href="dist/css/cis-style.css" rel="stylesheet" type="text/css">
	<!-- page CSS -->
    <link href="assets/modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/modules/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
</head>

<body class="skin-default fixed-layout">
    <?php echo PRELOADER; ?>
    <div id="main-wrapper">
        <?php require_once("inc/header.php"); ?>
        <?php $mm_id = 6; require_once("inc/menus.php"); ?>
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
                                <li class="breadcrumb-item active">Users Rights</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card">
					
					<div class="card-body">
						<div class="row">
							<div class="col-md-5 align-self-center">
								<h4 class="card-title">User Rights</h4>
							</div>
							<div class="col-md-7 align-self-center text-right">
								<div class="d-flex justify-content-end align-items-center">
									<a class="btn btn-outline-info" href="list-users.php"><i class="fa fa-angle-left m-r-5"></i> List of Users</a>
								</div>
							</div>
						</div>
						<hr class="m-t-10 m-b-0">
						
						
						<div class="m-t-20">
							<form name='thisForm' class="" method='POST' action=""  data-toggle="validator" onSubmit="return fnValidate();" enctype="multipart/form-data">
								<div class="form-body">
									<div class="row m-t-10">
										<div class="col-md-6">
											<div class="form-group row">
												<label class="col-form-label col-md-3">User Name</label>
												<div class="col-md-9">
													<input type="text" readonly class="form-control input-sm" value="<?php echo $obj->usr_name; ?>"  />
												</div>										
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group row">
												<label class="col-form-label col-md-3">Designation</label>
												<div class="col-md-9">
													<input type="text" readonly class="form-control input-sm" value="<?php echo $obj->usr_dest; ?>"  />
												</div>
											</div>
										</div>												
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group row">
												<label class="col-form-label col-md-3">User ID(Login ID)</label>
												<div class="col-md-9">
													<input type="text" readonly class="form-control input-sm" value="<?php echo $obj->log_name; ?>"  />
												</div>										
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group row">
												<label class="col-form-label col-md-3">Mobile No.</label>
												<div class="col-md-9">
													<input type="text" readonly class="form-control input-sm" value="<?php echo $obj->usr_mobile; ?>"  />
												</div>
											</div>
										</div>												
									</div>
									
									<hr class="m-t-10 m-b-20">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group row">
												<label class="col-form-label col-md-3">Main Menu <span class="text-danger">*</span></label>
												<div class="col-md-9">
													<select name="mm_id" id="mm_id" data-placeholder="Choose a menu.." class="form-control custom-select">
													<?php echo $dbconn->fnFillComboFromTable_Where("mm_id","mm_name","mst_main_menu","mm_id"," WHERE mm_show = 1") ?>
													</select> 
													<script>document.thisForm.mm_id.value="<?php echo $_REQUEST['mm_id']; ?>";</script>
												</div>										
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group row">
												<label class="col-form-label col-md-3">Select</label>
												<div class="col-md-9">
													<p class="col-form-label text-left"><a href="javascript:;" onClick="javascript:check_all('Chk[]');">All</a> &nbsp; | &nbsp; <a href="javascript:;" onClick="javascript:uncheck_all('Chk[]');">None</a></p>
												</div>										
											</div>
										</div>
									</div>
									<hr class="m-t-0 m-b-20">
									<div class="row ">
										<div class="col-md-1 hidden-xs hidden-sm"></div>
										<div class="col-sm-12 col-xs-12 col-md-12 col-lg-11">
											<div class="row">
												<?php
												$sql="SELECT * FROM mst_sub_menu WHERE mm_id = '".$_REQUEST['mm_id']."' AND sm_show = 1 ORDER BY sm_name";
												$pdoRes = $conn->query($sql);	
												if ($pdoRes->rowCount() > 0){
													$cnt=1;
													while($sm=$pdoRes->fetch()){
														$checked = $dbconn->GetSingleReconrd("tbl_user_rights","auto_id","sm_ids LIKE '%,".$sm->sm_id.",%' AND log_id",$_REQUEST['log_id']);
														if($checked == ""){
															echo '<div class="col-md-3 m-t-10 m-b-10">
																		<div class="custom-control custom-checkbox checkbox-info">
																			<input type="checkbox" class="custom-control-input" id="sm'.$sm->sm_id.'" name="Chk[]" value="'.$sm->sm_id.'">
																			<label class="custom-control-label" for="sm'.$sm->sm_id.'">'.$sm->sm_name.'</label>
																		</div>
																	</div>';
														}else{
															echo '<div class="col-md-3 m-t-10 m-b-10">
																		<div class="custom-control custom-checkbox checkbox-info">
																			<input type="checkbox" class="custom-control-input" id="sm'.$sm->sm_id.'" name="Chk[]" checked value="'.$sm->sm_id.'">
																			<label class="custom-control-label" for="sm'.$sm->sm_id.'">'.$sm->sm_name.'</label>
																		</div>
																	</div>';
														}
														$cnt++;
													}
												}
												$pdoRes = null;												
												?>														
											</div>
										</div>                                                
									</div>									
									<!--/row-->
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
	<!-- This page plugins -->
	<script src="assets/modules/switchery/dist/switchery.min.js"></script>
    <script src="assets/modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script src="assets/modules/bootstrap-select/bootstrap-select.min.js" type="text/javascript"></script>
    
	
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
		$('#mm_id').change(function(){
			var token = $('#token').val();
			var mm_id = $('#mm_id').val();
			var url = "mst-users-rights.php?token="+token+"&mm_id="+mm_id;
			window.location = url; // redirect
		});
	});
	function fnValidate(){		
		document.thisForm.submit();		
	}
	function check_all(obj){
		var field = document.thisForm.elements[obj];
		for (i = 0; i < field.length; i++){
			field[i].checked = true;
		}
	}
	function uncheck_all(obj){
		var field = document.thisForm.elements[obj];
		for (i = 0; i < field.length; i++){
			field[i].checked = false;
		}
	}
	</script>	
</body>
</html>