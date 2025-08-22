<?php
ob_start();
session_start();
error_reporting(0);
require_once("../config/pdoconnect.php");
require_once("../config/pdohandler.php");
require_once("../config/pdofunctions.php");
require_once("../config/pdodefinitions.php");

// Check if user is admin
if(!isset($_SESSION['_user']) || $_SESSION['_user'] != "CISADMIN"){
    echo "Access Denied";
    exit;
}

$conn = new dbconnect();

if($_POST["id"] != "" && $_POST["sTable"] != "" && $_POST["sWhere"] != ""){	
    try {
        // Always perform hard delete (permanent removal)
        $sqlQuery = "DELETE FROM ".$_POST['sTable']." WHERE ".$_POST['sWhere']." = :id";
        $stmt = $conn->prepare($sqlQuery);
        $stmt->execute(array(':id' => $_POST["id"]));
        
        if($stmt->rowCount() > 0) {
            echo "success";
        } else {
            echo "no_changes";
        }
    } catch (Exception $e) {
        echo "error: " . $e->getMessage();
    }
} else {
    echo "missing_parameters";
}

$conn = null;
?>