<?php
ob_start();
session_start();
error_reporting(0);
require_once("../config/pdoconnect.php");
require_once("../config/pdohandler.php");
require_once("../config/pdofunctions.php");
require_once("../config/pdodefinitions.php");
isAdmin();
$conn = new dbconnect();
$dbconn= new dbhandler();
$converter = new Encryption;

	$sWhere = " a.memb_status = 1 ";
	$cWhere = " memb_status = 1 ";
	
	$iTotalRecords = $dbconn->GetCount("tbl_members",$cWhere." AND 1",1);
	$iDisplayLength = intval($_REQUEST['iDisplayLength']);
	$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
	$iDisplayStart = intval($_REQUEST['iDisplayStart']);
	$sEcho = intval($_REQUEST['sEcho']);
	$records = array();
	$records["aaData"] = array(); 
	$end = $iDisplayStart + $iDisplayLength;
	$end = $end > $iTotalRecords ? $iTotalRecords : $end;
	/* Paging */
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".intval( $_GET['iDisplayStart'] ).", ".
			intval( $_GET['iDisplayLength'] );
	}
	/* Ordering */
	$aColumns = array('a.memb_id','a.memb_group','a.memb_code','a.memb_fname','a.memb_lname','a.memb_mobile','b.city_name','a.memb_status');
	$sOrder = "";
	if ( isset( $_GET['iSortCol_0'] ) ){
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= " ".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]." ".
					($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') ." ";
			}
		}
	}else{
		$sOrder = "ORDER BY a.memb_id";	
	}
	$keyword = preg_replace('/[^A-Za-z0-9 \x{0080}-\x{FFFF}]+/u', '', $_GET['sSearch']);
	$sQuery = "
		SELECT DISTINCT a.memb_id,a.memb_group,a.memb_code,a.memb_fname,a.memb_initial,a.memb_lname,a.memb_email,a.memb_mobile,b.city_name
		FROM tbl_members a 
		INNER JOIN mst_city b ON a.memb_city = b.city_id
		WHERE ".$sWhere." AND (a.memb_code LIKE '%".$keyword."%' OR a.memb_fname LIKE '%".$keyword."%' OR a.memb_lname LIKE '%".$keyword."%' OR a.memb_email LIKE '%".$keyword."%' OR a.memb_mobile LIKE '%".$keyword."%' OR b.city_name LIKE '%".$keyword."%' OR CONCAT('G',a.memb_group) LIKE '%".$keyword."%' )
		$sOrder
		$sLimit
		";		
	$pdoRes = $conn->query($sQuery);	
	if ($pdoRes->rowCount() > 0){
		$Sno = $_GET['iDisplayStart'];
		while($obj=$pdoRes->fetch()){
			$Sno++;
			$token = $converter->encode($obj->memb_id.'~'.$_SESSION["_sessiondtm"]);
			$del_link = '<a href="javascript:;" class="delete" rel="'.$obj->memb_id.'" data-toggle="tooltip" data-original-title="Remove"> <i class="ti-trash m-r-10"></i> </a>';	
			$actions = '<a href="members-add.php?token='.$token.'" data-toggle="tooltip" data-original-title="Edit"> <i class="ti-pencil-alt text-info m-r-5"></i> </a>
						'.$del_link.'';
            $records["aaData"][] = array(
			  $Sno,
			  'G'.$obj->memb_group,
			  $obj->memb_code,
			  $obj->memb_fname.'. '.$obj->memb_initial,
			  $obj->memb_lname,
			  $obj->memb_mobile,
			  $obj->city_name,
			  $actions,
		   );
		}
	}
	$pdoRes = null;
	/*
	$records["aaData"][] = array(
		  "1",
		  "2",
		  "3",
		  "4",
		  $sQuery."<br>".$error,
	);
	*/
	$records["sEcho"] = $sEcho;
	$records["iTotalRecords"] = $iTotalRecords;
	$records["iTotalDisplayRecords"] = $iTotalRecords;
	echo json_encode($records);
	$conn = null;
	$dbconn = null;
	die();
?>