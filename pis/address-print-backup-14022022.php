<?php
ob_start();
session_start();
error_reporting(0);
require_once("inc/userclasses.php");

if(isset($_REQUEST['data'])!=''){	
	$url_datas = $converter->decode($_REQUEST['data']);
	$url_data = explode('&',$url_datas);
	$cityids = '0';
	foreach ($url_data as $param){
        $array = explode("=", $param);
		if($array[0] == 'cityids'){
			$cityids .= ','.$array[1];
		}else{
			$_REQUEST[$array[0]] = $array[1];
		}
    }	
	if($_REQUEST['session'] != $_SESSION["_sessiondtm"]){
		$_SESSION['_msg_err'] = "Invalid search, please try again..!";	
		header("location:address-print.php");			
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
    <title><?php echo PAGE_TITLE; ?> - Print Address</title>
    <link href="assets/modules/toast-master/css/jquery.toast.css" rel="stylesheet" type="text/css">
	<link href="dist/css/style.min.css" rel="stylesheet" type="text/css">
	<link href="dist/css/cis-style.css" rel="stylesheet" type="text/css">
	<link href="assets/modules/custom-select/custom-select.css" rel="stylesheet" type="text/css" />
	<link href="assets/modules/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
	<link href="assets/modules/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
	<style>
	.select-all{
		border-radius: 2px;
		border: 1px solid #999999;
		margin-bottom: 0;
		padding: 8px 12px;
		font-size: 14px;
		font-weight: 400;
		color: #464a4c;
		text-align: center;
		background-color: #eceeef;
		border-bottom-left-radius: 0;
		border-top-left-radius: 0;
		border-left: 0;
	}
	.height40{min-height:40px;}
	</style>
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
                                <li class="breadcrumb-item active">Print Address</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card">
					
					<div class="card-body">
						<div class="row">
							<div class="col-md-5 align-self-center">
								<h4 class="card-title">Print Address</h4>
							</div>
							<div class="col-md-7 align-self-center text-right">
								<div class="d-flex justify-content-end align-items-center">
								
								</div>
							</div>
						</div>
						<hr class="m-t-10 m-b-0">
						
						
						<div class="m-t-20">
							<form name='resultForm' id="resultForm" class="form-horizontal" method='POST' action="" enctype="multipart/form-data">
								<div class="form-body">
									<div class="row">
										<div class="col-md-7">
											<div class="form-group m-b-0 row">
												<div class="col-md-3 p-r-0">
													<select name="memb_group" id="memb_group" class="form-control custom-select height40">
														<option value=''>All Group</option> 
														<option value='1'>Group 1</option> 
														<option value='2'>Group 2</option> 
														<option value='3'>Group 3</option> 
														<option value='4'>Group 4</option> 
														<option value='5'>Group 5</option> 
													</select>
													<script>document.resultForm.memb_group.value="<?php echo $_REQUEST['memb_group']; ?>";</script>
												</div>
												<div class="col-md-8 p-r-0">
													<select class="selectpicker" name="cityids" id="cityids" multiple data-style="form-control" data-placeholder="Choose Cities">
													<?php									
														$sQuery = "SELECT DISTINCT a.city_id,a.city_name FROM mst_city a WHERE a.city_status = 1 ORDER BY a.city_name ";		
														$pdoRes = $conn->query($sQuery);	
														if ($pdoRes->rowCount() > 0){
															while($row=$pdoRes->fetch()){
																echo '<option value="'.$row->city_id.'">'.$row->city_name.'</option>';
															}
														}
														$pdoRes = null;								
													?>
													</select>
												</div>
												<div class="col-md-1 select-all" data-select="cityids" data-toggle="tooltip" data-original-title="Select All" ><i class="ti-check"></i></div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="row">
												<div class="col-md-7">
												<select class="form-control custom-select height40" id="clms" name="clms">
													<option value="2">2 Columns</option>
													<option value="3">3 Columns</option>
												</select>
												</div>
												<div class="col-md-5 p-l-0 p-r-0">
												<INPUT class="btn btn-success btn-block height40" type="submit" name="SEARCH" id="SEARCH" accesskey='s' value="SEARCH">
												</div>
											</div>
										</div>
										<?php if(isset($_REQUEST['data'])!=''){ ?>
										<div class="col-md-2 text-right">
											<a href="javascript:PrintPartsNew(new Array('print_content1'),'PRINT-ADDRESS-<?php echo date('d-m-Y-s'); ?>');" class="btn btn-info btn-outline height40" id="printcontent"><i class="fa fa-print m-r-5"></i><span>Print</span></a>
										</div>
										<?php } ?>
									</div>																		
								</div>
							</form>	
							
								<hr class="m-t-20 m-b-20">
										
								<div class="row">	
									<div class="col-md-12">
									  <div class="invoice multipage" id="print_content1" style="width:800px; margin:0 auto;">
										<?php
										if(isset($_REQUEST['data'])!=''){
											$sqlQuery = "SELECT a.*,b.city_name FROM tbl_members a INNER JOIN mst_city b ON a.memb_city = b.city_id WHERE a.memb_city IN (".$cityids.") ";
											if($_REQUEST['memb_group'] != ''){
												$sqlQuery .= " AND a.memb_group = '".$_REQUEST['memb_group']."' ";
											}
											$sqlQuery .= " AND memb_status = 1 ORDER BY b.city_name,a.memb_fname";
											$pdoRes = $conn->query($sqlQuery);
											$newpage = '';
											$break_class = '';
											if($_REQUEST['clms'] != ''){
												$clms = $_REQUEST['clms'];
											}else{
												$clms = 2;
											}
											$clmWidth = round((100/$clms),2);
											if ($pdoRes->rowCount() > 0){											
												$Sno = 0;
												
												$header = '<table class="mystyle-nb" width="100%"><tr><td><img src="img/login-logo.jpg" width="200px" alt=""></td><td class="uppercase text-right"><h3 class="font-weight-bold m-t-15 m-b-0">Panirendar</h3><p>Youth Association</p></td></tr></table>';
												
												
												echo '<table class="table table-bordered table-address-'.$clms.' '.$break_class.'">';
												echo '<thead><tr><th colspan="'.$clms.'">
														<table class="mystyle-nb" width="100%"><tr><td><img src="img/login-logo.jpg" width="200px" alt=""></td><td class="uppercase text-right"><h3 class="font-weight-bold m-t-15 m-b-0">Panirendar</h3><p>Youth Association</p></td></tr></table>
														</th></tr></thead>';
												echo '<tbody><tr>';
												while($obj=$pdoRes->fetch()){
													if($Sno == 0){
														//echo $newpage;
														//echo '<table class="table table-bordered table-address '.$break_class.'"><tr>';
													}
													if ($Sno % $clms == 0 && $Sno > 0){
														echo '</tr><tr class="'.$break_class.'">';
														$break_class = '';
													}
													$memb_lname = '';													
													if($obj->memb_lname != ''){
														$memb_lname = '<br>'.$obj->memb_lname;
													}
													$memb_add1 = '';
													if($obj->memb_add1 != ''){
														$memb_add1 = '<br>'.$obj->memb_add1;
													}
													$memb_add2 = '';
													if($obj->memb_add2 != ''){
														$memb_add2 = '<br>'.$obj->memb_add2;
													}
													$memb_pincode = '';
													if($obj->memb_pincode != ''){
														$memb_pincode = '-'.$obj->memb_pincode;
													}
													$memb_mobile = '';
													if($obj->memb_mobile != ''){
														$memb_mobile = '<br>Phone: '.$obj->memb_mobile;
													}
													echo '<td width="'.$clmWidth.'%"><span class="font-weight-bold">'.$obj->memb_fname.'. '.$obj->memb_initial.'</span><span class="memb-id">'.$obj->memb_code.'</span>'.$memb_lname.$memb_add1.$memb_add2.'																
																<br>'.$obj->city_name.$memb_pincode.$memb_mobile.'
															</td>';
													$Sno++;
													if ($Sno % 10 == 0 && $Sno > 0){
														//$Sno = 0;
														//$newpage = '</tr></table><p class="newpage"></p>';
														//$newpage = '</tr></table>';
														$break_class = "newpage2";
													}
												}
												echo "</tr></tbody></table>";
											}
											$pdoRes = null;	
										}
										?>
										</div>
									</div>
								</div>
								
								<?php if(isset($_REQUEST['data'])!=''){ ?>
								<hr class="m-t-30 m-b-20">
								<div class="row">
									<div class="col-md-12 text-center">
										<a href="javascript:PrintPartsNew(new Array('print_content1'),'PRINT-ADDRESS-<?php echo date('d-m-Y-s'); ?>');" class="btn btn-info btn-outline" id="printcontent"><i class="fa fa-print m-r-5"></i><span>Print</span></a>
									</div>
								</div>
								<hr class="m-t-20 m-b-20">
								<?php } ?>
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
	<script src="assets/modules/bootstrap-select/bootstrap-select.min.js" type="text/javascript"></script>
	<script src="assets/modules/sweetalert/sweetalert.min.js"></script>
	<script src="print_me.js" type="text/javascript"></script>
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
		$('.selectpicker').selectpicker();		
		
		$(".select-all").click(function(){
			var opnSelect = $(this).data('select');
			$('#'+opnSelect+' option').prop('selected', 'selected');
			$('#'+opnSelect+'').selectpicker('refresh');
		});
		
		$("#resultForm").on("submit", function(event) {
			event.preventDefault();
			if ($('#cityids option:selected').length == 0){
			  alert('Please select city to print address..!');
			  return false;
			}
			//if(notSelected(document.resultForm.cityids,"city..!")){ return false; }
			var str = $(this).serialize()+'&session=<?php echo $_SESSION['_sessiondtm']; ?>';
			$.ajax({
				type:'post',
				url:'inc/cisajax/jquery-encryption.php',
				data: {"url":str},
				success:function(result){
					//console.log(result);
					window.location.href = "address-print.php?data="+result;
				}
			});
		});
		
	});
	
	
	</script>	
</body>
</html>