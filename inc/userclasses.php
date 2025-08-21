<?php
require_once("inc/config/pdoconnect.php");
require_once("inc/config/pdohandler.php");
require_once("inc/config/pdofunctions.php");
require_once("inc/config/pdodefinitions.php");
isAdmin();
$conn = new dbconnect();
$dbconn= new dbhandler();
$converter = new Encryption;
require_once("inc/active_menu.php");
?>