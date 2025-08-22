<?php
ob_start();
session_start();
error_reporting(0);
require_once("inc/userclasses.php");
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
    <title><?php echo PAGE_TITLE; ?> - Tamil Update</title>
    <link href="assets/modules/toast-master/css/jquery.toast.css" rel="stylesheet" type="text/css">
	<link href="assets/modules/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet" type="text/css">
    <link href="assets/modules/datatables.net-bs4/css/responsive.dataTables.min.css" rel="stylesheet" type="text/css">
    <link href="dist/css/style.min.css" rel="stylesheet" type="text/css">
    <link href="dist/css/cis-style.css" rel="stylesheet" type="text/css">
	<style>
	#custTable td:nth-child(8),#custTable td:nth-child(1){
	 text-align:center !important;
	}
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
                                <li class="breadcrumb-item active">Tamil Update</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card">
					
					<div class="card-body">
						<div class="row">
							<div class="col-md-5 align-self-center">
								<h4 class="card-title">Pending for Tamil Update</h4>
							</div>
							<div class="col-md-7 align-self-center text-right">
								<div class="d-flex justify-content-end align-items-center">
								
								</div>
							</div>
						</div>
						<hr class="m-t-10 m-b-0">
						
												
						<div class="table-responsive m-t-20">
							<table id="custTable" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th width="5%">#</th>
										<th width="10%">Group</th>
										<th width="10%">ID</th>
										<th width="25%">Member Name</th>
										<th width="25%">Tamil Name </th>
										<th width="15%">City Name</th>
										<th width="10%">Action</th>
									</tr>
								</thead>
								<tbody>								
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
    <script src="assets/modules/toast-master/js/jquery.toast.js"></script>
	<script src="assets/modules/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="assets/modules/datatables.net-bs4/js/dataTables.responsive.min.js"></script>
	<script src="assets/modules/fancybox/jquery.fancybox.js" type="text/javascript"></script>
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
		
		var initParams = {
			"bJQueryUI": false,
			"bAutoWidth": false,
			"bStateSave": true,
			"bServerSide": true,
			"bProcessing": true,
			"sPaginationType": "full_numbers",
			"iDisplayLength": 25,
			"sDom": '<"datatable-header"fl>t<"datatable-footer"ip>',
			"sAjaxSource": "inc/datatable/ajax_members_tamil.php",
			"oLanguage": {
				"oPaginate": { "sFirst": "First", "sLast": "Last", "sNext": ">", "sPrevious": "<" }
			},
			"aoColumnDefs": [
			  { "bSortable": false, "aTargets": [0,1,6] },
			  { "sDefaultContent": '', aTargets: ['_all'] }
			],
			"aaSorting": [],
			"fnDrawCallback": function() {
				datatable_init();
			}
		};
		var oTable = $('#custTable').dataTable(initParams);
		//myTable.state.clear();
		
		$('.fancybox').fancybox();
	});
	</script>	
</body>
</html>