<?php
ob_start();
session_start();
error_reporting(0);
require_once("inc/userclasses.php");
if(isset($_POST['SAVE'])){
	$if_exist = $dbconn->GetSingleReconrd("mst_city","city_id","city_name",$_POST['city_name']);
	if($if_exist != ""){
		$_SESSION['_msg_err'] = "The city name is already added..!";
		header("location:mst-city.php");
		die();
	}
	$_POST['state_id'] = 1;
	$_POST['lm_by'] = $_SESSION['_userid'];
	$_POST['lm_dtm'] = date('Y-m-d H:i:s');
	$ins_datas = $dbconn->CIS_InsertRecord("mst_city",$_POST,1);
	$_SESSION['_msg'] = "City Name has been Successfully Added..!";
	header("location:mst-city.php");
	die();
}
if(isset($_POST['UPDATE'])){
	$if_exist = $dbconn->GetSingleReconrd("mst_city","city_id","city_id <> ".$_POST['txtHid']." AND city_name",$_POST['city_name']);
	if($if_exist != ""){
		$_SESSION['_msg_err'] = "The city name is already added..!";
		header("location:mst-city.php?token=".$_POST['token']);
		die();
	}
	$_POST['lm_by'] = $_SESSION['_userid'];
	$_POST['lm_dtm'] = date('Y-m-d H:i:s');
	$dbconn->CIS_UpdateRecord("mst_city"," city_id = '".$_POST['txtHid']."' ",$_POST,1);	
	$_SESSION['_msg'] = "City Name has been Successfully Updated..!";
	header("location:mst-city.php");
	die();
}
if ($_REQUEST['token'] != ""){	
	$url_datas = $converter->decode($_REQUEST['token']);
	$url_data = explode("~",$url_datas);
	if(trim($url_data[1]) == trim($_SESSION["_sessiondtm"])){
		$_REQUEST['city_id'] = $url_data[0];
		$mstRes = $conn->prepare("SELECT * FROM mst_city WHERE city_id=:city_id");	
		$data_select = array(
			'city_id' => $_REQUEST['city_id']
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
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico" type="image/x-icon" />
    <title><?php echo PAGE_TITLE; ?> - City Master</title>
    <link href="assets/modules/toast-master/css/jquery.toast.css" rel="stylesheet" type="text/css">
	<link href="assets/modules/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet" type="text/css">
    <link href="assets/modules/datatables.net-bs4/css/responsive.dataTables.min.css" rel="stylesheet" type="text/css">
    <link href="assets/modules/switchery/dist/switchery.min.css" rel="stylesheet" />
	<link href="dist/css/style.min.css" rel="stylesheet" type="text/css">
    <link href="dist/css/cis-style.css" rel="stylesheet" type="text/css">	
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
                        <h4 class="text-themecolor">City Master</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="home.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">City Master</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card">
					
					<div class="card-body">
						<div class="row">
							<div class="col-md-5 align-self-center">
								<h4 class="card-title">List of Cities</h4>
							</div>
							<div class="col-md-7 align-self-center text-right">
								<div class="d-flex justify-content-end align-items-center">
									<a class="btn btn-outline-info" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-plus-circle m-r-5"></i> Add New City</a>
								</div>
							</div>
						</div>
						<hr class="m-t-10 m-b-0">
						
						<div id="collapseExample" class="collapse bg-blue p-t-10 <?php if($_REQUEST["city_id"]!='') { echo 'show'; }?>">
							<form name='thisForm' class="form-horizontal" method='POST' action=""  data-toggle="validator" onSubmit="return fnValidate();" enctype="multipart/form-data">
								<div class="form-body m-r-10 m-l-10">
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label class="control-label">City Name <span class="text-danger">*</span></label>
												<input type="text" class="form-control splname_only" required name="city_name" maxlength="30" placeholder="" value="<?php echo $obj->city_name; ?>"  />
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label class="control-label">State Name </label>
												<input type="text" class="form-control" name="state_name" maxlength="50" placeholder="" value="<?php echo $obj->state_name; ?>"  />
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label class="control-label">City Name in Tamil <span class="text-danger">*</span></label>
												<input type="text" class="form-control" required name="city_tamil" maxlength="50" placeholder="" value="<?php echo $obj->city_tamil; ?>"  />
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label class="control-label">State Name in Tamil </label>
												<input type="text" class="form-control" name="state_tamil" maxlength="50" placeholder="" value="<?php echo $obj->state_tamil; ?>"  />
											</div>
										</div>
									</div>
									
									<hr class="m-t-10">
									<div class="row">
										<div class="col-md-12 text-center">
											<div>
											<?php if($_REQUEST["city_id"]!='') { ?>
											  <INPUT class="btn btn-success" type="submit" name="UPDATE" value="UPDATE">
											  <INPUT class="btn btn-secondary" type="button" name="cancel" value="CANCEL" onClick="javascript:history.go(-1);">
											  <input type="hidden" name="txtHid" id="txtHid" value="<?php echo $_REQUEST['city_id'];?>">
											  <input type="hidden" name="token" id="token" value="<?php echo $_REQUEST['token'];?>">
											  <?php }else{ ?>
											  <INPUT class="btn btn-success" type="submit" name="SAVE" value="SAVE">
											  <INPUT class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" value="CANCEL" onClick="javascript:;">
											  <input type="hidden" name="txtHid" id="txtHid" value="0">
											<?php } ?>
											</div>
										</div>													
									</div>		
								</div>
							</form>
							<hr class="m-t-10 m-b-20">
						</div>
						
						<div class="table-responsive m-t-20">
							<table id="myTable" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th width="5%">SlNo</th>
										<th width="20%">City Name</th>
										<th width="20%">State Name</th>
										<th width="20%">City Name in Tamil</th>
										<th width="20%">State Name in Tamil</th>
										<th width="10%">Action</th>
									</tr>
								</thead>
								<tbody>
								<?php
								$pdoQry="SELECT * FROM mst_city WHERE city_status = 1 ORDER BY city_name";
								$pdoRes = $conn->query($pdoQry);	
								if ($pdoRes->rowCount() > 0){
									$Sno=1;
									while($obj=$pdoRes->fetch()){										
										$token = $converter->encode($obj->city_id.'~'.$_SESSION["_sessiondtm"]);										
										$membCnt = $dbconn->GetCount("tbl_members","memb_city = '".$obj->city_id."' AND memb_status",1);
										if($membCnt == 0){
											$delLink = '<a href="javascript:;" class="delete" rel="'.$obj->city_id.'" data-toggle="tooltip" data-original-title="Remove"> <i class="ti-trash m-r-10"></i> </a>';
										}else{
											$delLink = '<a href="javascript:;"> <i class="ti-trash text-muted m-r-10"></i> </a>';
										}
									?>
										<tr>
											<td align="center"><?php echo $Sno; ?></td>
											<td><?php echo $obj->city_name;?></td>
											<td><?php echo $obj->state_name;?></td>
											<td><?php echo $obj->city_tamil;?></td>
											<td><?php echo $obj->state_tamil;?></td>
											<td class="text-nowrap text-center">
												<a href="mst-city.php?token=<?php echo $token; ?>" data-toggle="tooltip" data-original-title="Edit"> <i class="ti-pencil-alt text-info m-r-10"></i> </a>
												<?php echo $delLink; ?>
											</td>
										</tr>	
									<?php 
									$Sno++; 
									} 
								}else{ ?>
									<tr>
										<td colspan="5" align="center">
											 No Data Found...
										</td>
									</tr>
								<?php } ?>
								</tbody>
							</table>
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
    <script src="dist/js/cisadmin.js"></script>
	<script src="dist/js/validate.js"></script>
    <script src="assets/modules/toast-master/js/jquery.toast.js"></script>
	<script src="assets/modules/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="assets/modules/datatables.net-bs4/js/dataTables.responsive.min.js"></script>
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
			if($(this).attr('data-txt')==1){
			var txtid = $(this).attr('id')+'-txt';
			if($(this).is(':checked')){
				var datayes = $(this).attr('data-yes');
				$(this).parent().append("<span id='"+txtid+"' class='switch-txt switch-success'>"+datayes+"</span>");	
			}else{
				var datano = $(this).attr('data-no');
				$(this).parent().append("<span id='"+txtid+"' class='switch-txt'>"+datano+"</span>");	
			}}		
        });
		
		var myTable = $('#myTable').DataTable({
			"bJQueryUI": false,
			"bAutoWidth": false,
			"bStateSave": true,
			"sPaginationType": "full_numbers",
			"iDisplayLength": 25,
			"oLanguage": {
				"oPaginate": { "sFirst": "First", "sLast": "Last", "sNext": ">", "sPrevious": "<" }
			},
			"aoColumnDefs": [
			  { "bSortable": false, "aTargets": [0,5] },
			  { "sDefaultContent": '', aTargets: ['_all'] }
			],
			"aaSorting": [],
			"fnDrawCallback": function() {
				$('[data-toggle="tooltip"]').tooltip();
			}
		});
		//myTable.state.clear();
		
		$('#myTable').on('click', 'a.delete', function (e) {
			e.preventDefault();
			var id = $(this).attr('rel');
			var sTable = 'mst_city';
			var sWhere = 'city_id';
			var sStatus = 'city_status';
			var sValue = '0';
			var nRow = $(this).parents('tr');			
			if ( confirm( "Are you sure, confirm to remove this city?" ) ) {	
				$.ajax({
					type:'post',
					url:'inc/cisajax/jquery-delete-records.php',
					data: {"id":id,"sTable":sTable,"sWhere":sWhere,"sStatus":sStatus,"sValue":sValue},
					beforeSend:function(){
					},
					complete:function(){
						$.toast({heading: 'Success!',text:'City details successfully removed..!',position:'top-right',loaderBg:'#ff6849',icon:'success',hideAfter:2000,stack:6});
					},
					success:function(result){
						//$('#scTable').DataTable().row(nRow).remove().draw();
						nRow.hide();
					}
				});				
			}
		});
		
	});
	
	function fnValidate(){		
		if(isNull(document.thisForm.city_name,"city name..!")){ return false; }
		document.thisForm.submit();		
	}
	</script>	
</body>
</html>