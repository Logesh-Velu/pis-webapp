<?php
ob_start();
session_start();
error_reporting(0);
require_once("inc/config/pdoconnect.php");
require_once("inc/config/pdohandler.php");
require_once("inc/config/pdofunctions.php");
require_once("inc/config/pdodefinitions.php");
$conn = new dbconnect();
$dbconn= new dbhandler();
$converter = new Encryption;
$message = '';
if ($_REQUEST['token'] != ""){	
	$url_data = $converter->decode($_REQUEST['token']);
	$url_data = explode("~",$url_data);
    if($url_data[0] != ''){
		$_REQUEST['req_id'] = $url_data[0];
		$_REQUEST['req_date'] = $url_data[1];
		try{
		$mstRes = $conn->prepare("SELECT * FROM tbl_email WHERE req_id=:req_id AND req_date=:req_date");	
		$data_select = array(
			'req_id' => $_REQUEST['req_id'],
			'req_date' => $_REQUEST['req_date']
		);
		$mstRes->execute($data_select);	
		if ($mstRes->rowCount() > 0){
			$obj=$mstRes->fetch();			
			if($url_data[2] == 'APPROVE'){
				$conn->query("UPDATE tbl_email SET req_status = 1, lm_dtm = '".date('Y-m-d H:i:s')."' WHERE req_id='".$obj->req_id."'");
				$message = 'APPROVE';
			}elseif($url_data[2] == 'REJECT'){
				$conn->query("UPDATE tbl_email SET req_status = -1, lm_dtm = '".date('Y-m-d H:i:s')."' WHERE req_id='".$obj->req_id."'");
				$message = 'REJECT';
			}			
		}
		$mstRes = null;
		}catch(Exception  $e)
		{
			echo $e->getMessage();
		}		
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
    <title><?php echo PAGE_TITLE; ?> - Dashboard</title>
    <link href="assets/modules/toast-master/css/jquery.toast.css" rel="stylesheet">
    <link href="dist/css/style.min.css" rel="stylesheet">
    <link href="dist/css/cis-style.css" rel="stylesheet">	
</head>
<body class="skin-default fixed-layout"> 
	<div width="100%" style="background: #f8f8f8; padding: 0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #514d6a;">
	  <div style="max-width: 700px; padding:30px 0;  margin: 0px auto; font-size: 14px">
		<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
		  <tbody>
			<tr>
			  <td style="vertical-align: top; padding-bottom:20px;" align="center"><a href="javascript:;"><img src="main-logo.png" alt="" style="border:none"> </td>
			</tr>
		  </tbody>
		</table>
		<div style="padding: 40px; background: #fff;">
		  <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
			<tbody>
			  <tr>
				<td class="text-center">
					<?php
						if($message == ''){
							echo '<i class="icon-ban" style="font-size:60px;"></i>';
							echo '<h1 class="font-bold m-b-20">SORRY</h1>';							
							echo '<p class="font-18 m-b-20">Invalid page reference, please contact system admin..!</p>';							
						}elseif($message == 'APPROVE'){
							echo '<i class="icon-check text-success" style="font-size:60px;"></i>';
							echo '<h1 class="font-bold m-b-20 text-success">APPROVED</h1>';							
							echo '<p class="font-18 m-b-20">The report details approved successfully, Thank You..!</p>';							
						}elseif($message == 'REJECT'){
							echo '<i class="icon-check text-danger" style="font-size:60px;"></i>';
							echo '<h1 class="font-bold m-b-20 text-danger">REJECTED</h1>';							
							echo '<p class="font-18 m-b-20">The report details rejected successfully, Thank You..!</p>';							
						}					
					?>					
					<p class="text-center"><a href="javascript:window.open('','_self').close();" class="btn btn-warning btn-outline"><span>Close this Window</span></a></p>
				</td>
			  </tr>
			</tbody>
		  </table>
		</div>
	  </div>
	</div>
    <script src="assets/modules/jquery/jquery-3.2.1.min.js"></script>
    <script src="assets/modules/popper/popper.min.js"></script>
    <script src="assets/modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
    <script src="dist/js/waves.js"></script>
    <script src="dist/js/sidebarmenu.js"></script>
    <script src="dist/js/custom.min.js"></script>		
</body>
</html>
<?php 
	$conn = null;
	$dbconn = null;
?>