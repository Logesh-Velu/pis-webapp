<?php
ob_start();
session_start();
error_reporting(0);
require_once("inc/userclasses.php");

if (isset($_REQUEST['SAVE'])){
  	
	$sqlQuery = "SELECT * FROM tbl_members_temp WHERE session_id = '".$_SESSION['_sessiondtm']."' AND import_status = 1 ORDER BY auto_id";
	$pdoRes = $conn->query($sqlQuery);	
	if ($pdoRes->rowCount() > 0){
		while($obj=$pdoRes->fetch()){
			$_POST['memb_group'] = $obj->memb_group;
			$_POST['memb_code'] = $obj->memb_code;
			$_POST['memb_fname'] = $obj->memb_fname;
			$_POST['memb_initial'] = $obj->memb_initial;
			$_POST['memb_lname'] = $obj->memb_lname;
			$_POST['memb_add1'] = $obj->memb_add1;
			$_POST['memb_add2'] = $obj->memb_add2;
			$_POST['memb_city'] = $obj->memb_city;
			$_POST['memb_state'] = $obj->memb_state;
			$_POST['memb_pincode'] = $obj->memb_pincode;
			$_POST['memb_mobile'] = $obj->memb_mobile;
			$_POST['memb_email'] = $obj->memb_email;		
			$_POST['memb_regdt'] = date('Y-m-d');
			$_POST['lm_by'] = $_SESSION['_userid'];	
			$_POST['lm_dtm'] = date('Y-m-d H:i:s');
			$ins_datas = $dbconn->CIS_InsertRecord("tbl_members",$_POST,1);
		}
	}
	$pdoRes = null;
	$conn->query("DELETE FROM tbl_members_temp WHERE session_id = '".$_SESSION['_sessiondtm']."'");	
	$_SESSION['_msg'] = "All member details has been successfully saved..!";
	header("location:members-import.php");
	die();
		
}
if (isset($_POST['UPLOAD'])){
	
	$conn->query("DELETE FROM tbl_members_temp WHERE session_id = '".$_SESSION['_sessiondtm']."'");
	
	error_reporting(E_ALL ^ E_NOTICE);
	require_once 'excel_reader2.php';
	
	$_REQUEST['excel_file'] = post_img($_FILES['excel_file']['name'], $_FILES['excel_file']['tmp_name'],"xls_files");
	$fileName = "xls_files/".$_REQUEST['excel_file'];
	
	$data = new Spreadsheet_Excel_Reader();
	$data->read($fileName);
	
	for($i=2;$i<=$data->sheets[0]['numRows'];$i++){
				
		$_POST['memb_fname'] = trim($data->sheets[0]['cells'][$i][2]);	
		if($_POST['memb_fname']!=''){
			
			$import_status = 1;
			$import_remarks = 'OK';
				
			$_POST['memb_initial'] = trim($data->sheets[0]['cells'][$i][3]);
			$_POST['memb_lname'] = trim($data->sheets[0]['cells'][$i][4]);
			$_POST['memb_add1'] = trim($data->sheets[0]['cells'][$i][5]);
			$_POST['memb_add2'] = trim($data->sheets[0]['cells'][$i][6]);
			$_POST['memb_cityname'] = trim($data->sheets[0]['cells'][$i][7]);
			
			$city_id = $dbconn->GetSingleReconrd("mst_city","city_id","city_name = '".$_POST['memb_cityname']."' AND 1",1);
			if($city_id > 0){
				$_POST['memb_city'] = $city_id;
				$_POST['memb_state'] = 'Tamilnadu';
			}else{
				$import_status = 0;
				$import_remarks='City name not match with City Master';
			}
			$_POST['memb_pincode'] = trim($data->sheets[0]['cells'][$i][8]);			
			if(strlen($_POST['memb_pincode']) != 6 && $_POST['memb_pincode'] != ''){
				$import_status = 0;
				$import_remarks='Invalid pincode';
			}
			$_POST['memb_mobile'] = trim($data->sheets[0]['cells'][$i][9]);			
			if(strlen($_POST['memb_mobile']) != 10 && $_POST['memb_mobile'] != ''){
				$import_status = 0;
				$import_remarks='Invalid mobile number';
			}
			$_POST['memb_email'] = trim($data->sheets[0]['cells'][$i][10]);
			$_POST['memb_group'] = trim($data->sheets[0]['cells'][$i][11]);
			$_POST['memb_code'] = strtoupper(trim($data->sheets[0]['cells'][$i][12]));
			
			$membcode = $dbconn->GetSingleReconrd("tbl_members","memb_id","memb_code = '".$_POST['memb_code']."' AND 1",1);
			if($membcode != ''){
				$import_status = 0;
				$import_remarks='Mamber code already exist';
			}		
		}else{
			$import_status = 0;
			$import_remarks='First name not found';
			
		}
		
		$_POST['import_status'] = $import_status;
		$_POST['import_remarks'] = $import_remarks;
		$_POST['session_id'] = $_SESSION['_sessiondtm'];
		$_POST['memb_type'] = 'C';
		$_POST['dt'] = date('Y-m-d');
		$ins_datas = $dbconn->CIS_InsertRecord("tbl_members_temp",$_POST,1);
		
		
	}
	
	header("location:members-import.php");
	die();
}

if ($_REQUEST['mode'] == 'DELETE'){
	$conn->query("DELETE FROM tbl_members_temp WHERE session_id = '".$_SESSION['_sessiondtm']."'");
	header("location:members-import.php");
	die();
}

$conn->query("DELETE FROM tbl_members_temp WHERE dt < '".date('Y-m-d')."'");
$memb_data = $dbconn->GetCount("tbl_members_temp","session_id = '".$_SESSION['_sessiondtm']."' AND import_status",1);


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
    <title><?php echo PAGE_TITLE; ?> - Import Members</title>
    <link href="assets/modules/toast-master/css/jquery.toast.css" rel="stylesheet" type="text/css">
	<link href="dist/css/style.min.css" rel="stylesheet" type="text/css">
	<link href="dist/css/cis-style.css" rel="stylesheet" type="text/css">
	<link href="assets/modules/custom-select/custom-select.css" rel="stylesheet" type="text/css" />
	<link href="assets/modules/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
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
                                <li class="breadcrumb-item active">Import Members</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card">
					
					<div class="card-body">
						<div class="row">
							<div class="col-md-5 align-self-center">
								<h4 class="card-title">Import Members</h4>
							</div>
							<div class="col-md-7 align-self-center text-right">
								<div class="d-flex justify-content-end align-items-center">
									<a href="import_sample.xls" target="_blank" class="btn btn-outline-info m-r-5"><i class="fa fa-file-excel-o m-r-5"></i><span> Download Excel Format</span></a>
									<a class="btn btn-outline-info" href="members.php"><i class="fa fa-list m-r-5"></i> List of Members</a>
								</div>
							</div>
						</div>
						<hr class="m-t-10 m-b-0">
						
						
						<div class="m-t-20">
							<form name='thisForm' class="form-horizontal" method='POST' action=""  data-toggle="validator" onSubmit="return fnValidate();" enctype="multipart/form-data">
								<input type="hidden" name="memb_data" id="memb_data" value="<?php echo $memb_data; ?>"> 
										
								<div class="form-body">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group m-b-0 row">
												<label class="control-label text-right col-md-3">Excel File <span class="text-danger">*</span></label>
												<div class="col-md-9">
													<div class="custom-file mb-3">
													  <input type="file" class="custom-file-input xls_only" name="excel_file" id="excel_file" data-toggle="tooltip" data-original-title=".xls file only, Below 500KB" data-size="500" data-submit='1'>
													  <label class="custom-file-label form-control" for="excel_file">Choose file</label>
													  <small id="excel_file_error" class="cis-feedback"></small>
													  <input type="hidden" name="hide_excel_file" value="<?php echo $obj->excel_file; ?>">
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-2">
											<INPUT class="btn btn-success" type="submit" name="UPLOAD" id="UPLOAD" accesskey='u' value="UPLOAD">
										</div>
									</div>																		
								</div>
								
								<hr class="m-t-0 m-b-20">
										
								<div class="row">	
									<div class="col-md-12">
									  <div class="table-responsive" id="print_content1">
										<?php
										
										$sqlQuery = "SELECT * FROM tbl_members_temp WHERE session_id = '".$_SESSION['_sessiondtm']."' ORDER BY auto_id";
										$pdoRes = $conn->query($sqlQuery);	
										if ($pdoRes->rowCount() > 0){											
											echo '<table class="table table-striped table-hover table-bordered">';
											echo '<thead>
													<tr>
														<th>#</th>
														<th>First Name</th>
														<th>Last Name</th>
														<th>Address</th>
														<th>City</th>
														<th>Email</th>
														<th>Mobile</th>
														<th>Import Status</th>
													</tr>
												</thead>';
											$Sno = 0;
											$mem_avl = 0;
											while($obj=$pdoRes->fetch()){
												$Sno++;
												if($obj->import_status == 1){
													$row_class = '';
													$mem_avl++;
												}else{
													$row_class = 'text-danger';
												}
												echo '<tr class="'.$row_class.'">
														<td>'.$Sno.'</td>
														<td>'.$obj->memb_fname.'. '.$obj->memb_initial.'</td>
														<td>'.$obj->memb_lname.'</td>
														<td>'.$obj->memb_add1.'<br>'.$obj->memb_add2.'<br>'.$obj->memb_pincode.'</td>
														<td>'.$obj->memb_cityname.'</td>
														<td>'.$obj->memb_email.'</td>
														<td>'.$obj->memb_mobile.'</td>
														<td>'.$obj->import_remarks.'</td>
													</tr>';
											}
											echo "</table>";
										}
										$pdoRes = null;
										
										?>
										</div>
									</div>
								</div>
								
								<div class="row">	
									<div class="col-md-12 text-center">
									 <?php if($memb_data>0) { 
										echo '<p><strong>'.$mem_avl.'</strong> Member Datas are valid to Import..!</p>'; 
									 ?>
									 <INPUT class="btn btn-success" type="submit" name="SAVE" id="SAVE" accesskey='s' value="SAVE ALL">											  
									 <INPUT class="btn btn-secondary" type="button" name="DELETE" id="DELETE" value="DELETE ALL" accesskey='d'>
									 <?php }else{									 
									 if($Sno > 0){ 
										echo '<p>Member Datas are not valid..!</p>'; 
									 ?>
									 <INPUT class="btn btn-secondary" type="button" name="DELETE" id="DELETE" value="DELETE ALL" accesskey='d'>
									 <?php } } ?>
									</div>
								</div>								
							</form>
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
    <script src="assets/modules/sweetalert/sweetalert.min.js"></script>
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
		
		$('#DELETE').on('click', function (e) {
			e.preventDefault();			
				swal({   
					title: "Are you sure?",   
					text: "Confirm to remove all AWB Datas?",   
					type: "warning",   
					showCancelButton: true,   
					confirmButtonColor: "#DD6B55",   
					confirmButtonText: "Yes, Delete All!",   
					closeOnConfirm: false 
				}, function(){	
					window.location.href="members-import.php?mode=DELETE";
				});	
		});
		
	});
	
	function fnValidate(){	
		
		if(document.thisForm.memb_data.value == 0){
			if(notXlsFile(document.thisForm.excel_file,"import member data..!")){ return false; } 
		}else{
			if(document.thisForm.excel_file.value != ""){
				if(notXlsFile(document.thisForm.excel_file,"import member data..!")){ return false; } 	
			}		
		}		
		document.thisForm.submit();
		
	}
	</script>	
</body>
</html>