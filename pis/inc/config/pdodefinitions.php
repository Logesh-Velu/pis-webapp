<?php
try{
	$conn = new dbconnect();
	$pdoRes = $conn->query("SELECT * FROM mst_application");
	$row = $pdoRes->fetch();	
	define ("APPLICATION_NAME",$row->app_name);
	define ("VERSION","1.0");
	define ("CLIENT_NAME",$row->app_client_web);
	define ("CLIENT_ADD",$row->app_client_address);
	define ("CLIENT_PHONE",$row->app_client_phone);
	define ("CLIENT_EMAIL",$row->app_client_email);
	define ("PAGE_LOGO",$row->app_client_logo);
	define ("PAGE_TITLE",$row->app_page_title);
	define ("PAGE_COPYRIGHT",date("Y")." ".$row->app_page_copyright.' &nbsp; | &nbsp; '."Developed by CIS Technologies");
	define ("DATE_FORMAT","d-M-Y");
	define ("DATE_REP","-");
	define ("DISPLAY_LINKS",10);
	define ("LIST_LENGTH",10);
	define ("LIST_LENGTH_20",20);
	define ("YEAR_START",1970);
	define ("YEAR_END",(date('Y')+5));
	define ("PRELOADER",'<div class="preloader"><div class="loader"><div class="loader__figure"></div><p class="loader__label">CIS Admin</p></div></div>');
	define ("SUPPORT_EMAIL","kabilanju@gmail.com");
	define ("ADMIN_EMAIL","kabilanju@gmail.com");
	define ("DONT_REPLY_EMAIL","do-not-reply@c-infosoft.com");	
	$row = null;
	$conn = null;
}catch (PDOException $e) {
		
}
?>
