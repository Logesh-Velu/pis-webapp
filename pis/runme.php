<?php
ob_start();
session_start();
error_reporting(0);

$_SESSION['_exp_file'] = 'Members-'.date('Ymdhi');
echo $_SESSION['_exp_qery'] = "SELECT a.*,b.city_name FROM `tbl_members` a INNER JOIN mst_city b ON a.memb_city = b.city_id";
header("location:export_csv_all.php");			
die();

$conn = null;
$dbconn = null;
?>