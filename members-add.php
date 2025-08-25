    <?php
ob_start();
session_start();
error_reporting(0);
require_once("inc/userclasses.php");

    // Handle form submission  member form
    if(isset($_POST['SAVE'])){
        try {
            $_POST['memb_code'] = strtoupper($_POST['memb_code']);
            
            // Check if member code already exists
            $if_exist = $dbconn->GetSingleReconrd("tbl_members","memb_id","memb_code",$_POST['memb_code']);
            if($if_exist != ""){
                $_SESSION['_msg_err'] = "The member code is already exist..!";
                header("location:members-add.php");
                die();
            }
    
    // Handle photo upload
    if ($_FILES['memb_photo']['name']!=""){						
        if(allowfile($_FILES['memb_photo']['name'],array('jpg','jpeg','png')) == false){
            $_SESSION['_msg_err'] = "Invalid Member Photo file selected..!";
            header("location:members-add.php");
            die();
        }
        $_POST['memb_photo'] = post_img($_FILES['memb_photo']['name'], $_FILES['memb_photo']['tmp_name'],"photos/");
    }
    
    // Calculate age from DOB
    if($_POST['memb_dob'] != '') {
        $dob = new DateTime($_POST['memb_dob']);
        $today = new DateTime();
        $age = $today->diff($dob);
        $_POST['memb_age'] = $age->y;
    }
    
    // Set default values
    $_POST['memb_marital_status'] = isset($_POST['memb_marital_status']) ? $_POST['memb_marital_status'] : 0; // Single
    $_POST['memb_is_householder'] = isset($_POST['memb_is_householder']) ? $_POST['memb_is_householder'] : 0; // No
    $_POST['memb_is_alive'] = isset($_POST['memb_is_alive']) ? $_POST['memb_is_alive'] : 1; // Yes
    $_POST['memb_approval'] = isset($_POST['memb_approval']) ? $_POST['memb_approval'] : 0; // Pending
    $_POST['memb_login'] = isset($_POST['memb_login']) ? $_POST['memb_login'] : 0; // No
    $_POST['memb_status'] = isset($_POST['memb_status']) ? $_POST['memb_status'] : 1; // Active
    $_POST['memb_defalut_add'] = isset($_POST['memb_defalut_add']) ? $_POST['memb_defalut_add'] : 1; // Address 1
    $_POST['show_profiles'] = isset($_POST['show_profiles']) ? $_POST['show_profiles'] : 1; // Show profiles
    
    // Set approval timestamp if status is approved
    if($_POST['memb_approval'] == 1) {
        $_POST['approved_dtm'] = date('Y-m-d H:i:s');
        $_POST['approved_by'] = $_SESSION['_userid'];
    }
    
    $_POST['created_dtm'] = date('Y-m-d H:i:s');
    $_POST['created_by'] = $_SESSION['_userid'];
    
            $ins_datas = $dbconn->CIS_InsertRecord("tbl_members",$_POST,1);
            $_SESSION['_msg'] = "Member details has been successfully added..!";
            header("location:members.php");
            die();
        } catch (Exception $e) {
            $_SESSION['_msg_err'] = "Error adding member: " . $e->getMessage();
            header("location:members-add.php");
            die();
        }
}

if(isset($_POST['UPDATE'])){	
    try {
        $_POST['memb_code'] = strtoupper($_POST['memb_code']);
        
        // Check if member code already exists (excluding current record)
        $if_exist = $dbconn->GetSingleReconrd("tbl_members","memb_id","memb_id <> ".$_POST['txtHid']." AND memb_code",$_POST['memb_code']);
        if($if_exist != ""){
            $_SESSION['_msg_err'] = "The member code is already exist..!";
            header("location:members-add.php?token=".$_POST['token']);
            die();
        }
    
    // Handle photo upload
    if ($_FILES['memb_photo']['name']!=""){						
        if(allowfile($_FILES['memb_photo']['name'],array('jpg','jpeg','png')) == false){
            $_SESSION['_msg_err'] = "Invalid Member Photo file selected..!";
            header("location:members-add.php?token=".$_POST['token']);
            die();
        }
        if($_POST["hide_memb_photo"] != ""){
            removeFile("photos/".$_POST["hide_memb_photo"]);
        }
        $_POST['memb_photo'] = post_img($_FILES['memb_photo']['name'], $_FILES['memb_photo']['tmp_name'],"photos/");
    }
    
    // Calculate age from DOB
    if($_POST['memb_dob'] != '') {
        $dob = new DateTime($_POST['memb_dob']);
        $today = new DateTime();
        $age = $today->diff($dob);
        $_POST['memb_age'] = $age->y;
    }
    
    // Set approval timestamp if status changes to approved
    if($_POST['memb_approval'] == 1) {
        $_POST['approved_dtm'] = date('Y-m-d H:i:s');
        $_POST['approved_by'] = $_SESSION['_userid'];
    }
    
    $_POST['updated_dtm'] = date('Y-m-d H:i:s');
    $_POST['updated_by'] = $_SESSION['_userid'];
    
            $dbconn->CIS_UpdateRecord("tbl_members"," memb_id = '".$_POST['txtHid']."' ",$_POST,1);	
            $_SESSION['_msg'] = "Member details has been successfully updated..!";
            header("location:members.php");
            die();
        } catch (Exception $e) {
            $_SESSION['_msg_err'] = "Error updating member: " . $e->getMessage();
            header("location:members-add.php?token=".$_POST['token']);
            die();
        }
}

// Initialize variables
$memb_city = 1;
$memb_native = 1;
$memb_state = 'Tamilnadu';
$memb_state_tamil = 'தமிழ்நாடு';

// Initialize $obj as empty object for new member
$obj = new stdClass();

// Initialize $obj as empty object for new member
$obj = new stdClass();

// Load existing data for editing
if ($_REQUEST['token'] != ""){	
    $url_datas = $converter->decode($_REQUEST['token']);
    $url_data = explode("~",$url_datas);
    if(trim($url_data[1]) == trim($_SESSION["_sessiondtm"])){
        $_REQUEST['memb_id'] = $url_data[0];
        $mstRes = $conn->prepare("SELECT * FROM tbl_members WHERE memb_id=:memb_id");	
        $data_select = array(
            'memb_id' => $_REQUEST['memb_id']
        );
        $mstRes->execute($data_select);	
        if ($mstRes->rowCount() > 0){
            $obj=$mstRes->fetch();
            $memb_city=$obj->memb_city;
            $memb_native=$obj->memb_native;
            $memb_state=$obj->memb_state;
            if($obj->memb_state_tamil != ""){
                $memb_state_tamil=$obj->memb_state_tamil;
            }
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico" type="image/x-icon" />
    <title><?php echo PAGE_TITLE; ?> - Add/Edit Members</title>
    <link href="assets/modules/toast-master/css/jquery.toast.css" rel="stylesheet" type="text/css">
    <link href="dist/css/style.min.css" rel="stylesheet" type="text/css">
    <link href="dist/css/cis-style.css" rel="stylesheet" type="text/css">
    <link href="assets/modules/custom-select/custom-select.css" rel="stylesheet" type="text/css" />
    <link href="assets/modules/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
</head>

<body class="skin-default fixed-layout">
   
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
                                <li class="breadcrumb-item active">Members</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5 align-self-center">
                                <h4 class="card-title">Add / Edit Members</h4>
                            </div>
                            <div class="col-md-7 align-self-center text-right">
                                <div class="d-flex justify-content-end align-items-center">
                                    <a class="btn btn-outline-info" href="members.php"><i class="fa fa-list m-r-5"></i> List of Members</a>
                                </div>
                            </div>
                        </div>
                        <hr class="m-t-10 m-b-0">
                        
                        <div class="m-t-40">
                            <form name='thisForm' class="" method='POST' action="" data-toggle="validator" onSubmit="return fnValidate();" enctype="multipart/form-data">
                                <div class="form-body">
                                    <!-- Basic Information Section -->
                                    <h5 class="text-info mb-3"><i class="fa fa-user"></i> Basic Information</h5>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Member Code <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control splname_only uppercase" required name="memb_code" maxlength="20" value="<?php echo isset($obj->memb_code) ? $obj->memb_code : ''; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Prefix</label>
                                                <select name="memb_prefix" class="form-control">
                                                    <option value="Mr." <?php echo (isset($obj->memb_prefix) && $obj->memb_prefix == 'Mr.') ? 'selected' : ''; ?>>Mr.</option>
                                                    <option value="Mrs." <?php echo (isset($obj->memb_prefix) && $obj->memb_prefix == 'Mrs.') ? 'selected' : ''; ?>>Mrs.</option>
                                                    <option value="Ms." <?php echo (isset($obj->memb_prefix) && $obj->memb_prefix == 'Ms.') ? 'selected' : ''; ?>>Ms.</option>
                                                    <option value="Dr." <?php echo (isset($obj->memb_prefix) && $obj->memb_prefix == 'Dr.') ? 'selected' : ''; ?>>Dr.</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">First Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control splname_only" required name="memb_fname" maxlength="75" value="<?php echo isset($obj->memb_fname) ? $obj->memb_fname : ''; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Initial</label>
                                                <input type="text" class="form-control splname_only" name="memb_initial" maxlength="10" value="<?php echo isset($obj->memb_initial) ? $obj->memb_initial : ''; ?>" />
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Alias Name</label>
                                                <input type="text" class="form-control splname_only" name="memb_aliasname" maxlength="75" value="<?php echo isset($obj->memb_aliasname) ? $obj->memb_aliasname : ''; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">English Name</label>
                                                <input type="text" class="form-control splname_only" name="memb_enname" maxlength="100" value="<?php echo isset($obj->memb_enname) ? $obj->memb_enname : ''; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Tamil Name</label>
                                                <input type="text" class="form-control" name="memb_tmname" maxlength="255" value="<?php echo isset($obj->memb_tmname) ? $obj->memb_tmname : ''; ?>" />
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Date of Birth</label>
                                                <input type="text" class="form-control datepicker" name="memb_dob" id="memb_dob" value="<?php echo isset($obj->memb_dob) ? $obj->memb_dob : ''; ?>" placeholder="YYYY-MM-DD" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Age</label>
                                                <input type="text" class="form-control" name="memb_age" id="memb_age" value="<?php echo isset($obj->memb_age) ? $obj->memb_age : ''; ?>" readonly />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Place of Birth</label>
                                                <input type="text" class="form-control" name="memb_pob" maxlength="100" value="<?php echo isset($obj->memb_pob) ? $obj->memb_pob : ''; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Gender <span class="text-danger">*</span></label>
                                                <select name="memb_gender" class="form-control" required>
                                                    <option value="">Select Gender</option>
                                                    <option value="Male" <?php echo (isset($obj->memb_gender) && $obj->memb_gender == 'Male') ? 'selected' : ''; ?>>Male</option>
                                                    <option value="Female" <?php echo (isset($obj->memb_gender) && $obj->memb_gender == 'Female') ? 'selected' : ''; ?>>Female</option>
                                                    <option value="Other" <?php echo (isset($obj->memb_gender) && $obj->memb_gender == 'Other') ? 'selected' : ''; ?>>Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Nationality</label>
                                                <input type="text" class="form-control" name="memb_nationality" maxlength="50" value="<?php echo isset($obj->memb_nationality) ? $obj->memb_nationality : 'Indian'; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">PIN Code</label>
                                                <input type="text" class="form-control" name="memb_pincode" maxlength="6" value="<?php echo isset($obj->memb_pincode) ? $obj->memb_pincode : ''; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">State in Tamil</label>
                                                <input type="text" class="form-control" name="memb_state_tamil" maxlength="50" value="<?php echo isset($obj->memb_state_tamil) ? $obj->memb_state_tamil : $memb_state_tamil; ?>" />
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Family Information Section -->
                                    <h5 class="text-info mb-3 mt-4"><i class="fa fa-users"></i> Family Information</h5>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Father</label>
                                                <select name="father_id" id="father_id" class="form-control select2" data-placeholder="Select Father">
                                                    <option value=""></option>
                                                    <?php echo $dbconn->fnFillComboFromTable_Where("memb_id","memb_fname","tbl_members","memb_fname"," WHERE memb_status = 1 AND memb_gender = 'Male'"); ?>
                                                </select>
                                                <script>document.thisForm.father_id.value="<?php echo $obj->father_id; ?>";</script>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Father Name (Manual)</label>
                                                <input type="text" class="form-control" name="father_name" maxlength="100" value="<?php echo isset($obj->father_name) ? $obj->father_name : ''; ?>" placeholder="Enter father name manually if not in system" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Mother</label>
                                                <select name="mother_id" id="mother_id" class="form-control select2" data-placeholder="Select Mother">
                                                    <option value=""></option>
                                                    <?php echo $dbconn->fnFillComboFromTable_Where("memb_id","memb_fname","tbl_members","memb_fname"," WHERE memb_status = 1 AND memb_gender = 'Female'"); ?>
                                                </select>
                                                <script>document.thisForm.mother_id.value="<?php echo $obj->mother_id; ?>";</script>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Mother Name (Manual)</label>
                                                <input type="text" class="form-control" name="mother_name" maxlength="100" value="<?php echo isset($obj->mother_name) ? $obj->mother_name : ''; ?>" placeholder="Enter mother name manually if not in system" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Marital Status</label>
                                                <select name="memb_marital_status" class="form-control">
                                                    <option value="0" <?php echo (isset($obj->memb_marital_status) && $obj->memb_marital_status == 0) ? 'selected' : ''; ?>>Single</option>
                                                    <option value="1" <?php echo (isset($obj->memb_marital_status) && $obj->memb_marital_status == 1) ? 'selected' : ''; ?>>Married</option>
                                                    <option value="2" <?php echo (isset($obj->memb_marital_status) && $obj->memb_marital_status == 2) ? 'selected' : ''; ?>>Divorced</option>
                                                    <option value="3" <?php echo (isset($obj->memb_marital_status) && $obj->memb_marital_status == 3) ? 'selected' : ''; ?>>Widowed</option>
                                                </select>
                                            </div>
                                        </div>
                                      
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Spouse</label>
                                                <select name="spouse_id" id="spouse_id" class="form-control select2" data-placeholder="Select Spouse">
                                                    <option value=""></option>
                                                    <?php echo $dbconn->fnFillComboFromTable_Where("memb_id","memb_fname","tbl_members","memb_fname"," WHERE memb_status = 1"); ?>
                                                </select>
                                                <script>document.thisForm.spouse_id.value="<?php echo $obj->spouse_id; ?>";</script>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Spouse Name (Manual)</label>
                                                <input type="text" class="form-control" name="spouse_name" maxlength="100" value="<?php echo isset($obj->spouse_name) ? $obj->spouse_name : ''; ?>" placeholder="Enter spouse name manually if not in system" />
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Is Householder?</label>
                                                <div class="form-check">
                                                    <input type="radio" name="memb_is_householder" value="1" <?php echo (isset($obj->memb_is_householder) && $obj->memb_is_householder == 1) ? 'checked' : ''; ?> class="form-check-input" />
                                                    <label class="form-check-label">Yes</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="radio" name="memb_is_householder" value="0" <?php echo (isset($obj->memb_is_householder) && ($obj->memb_is_householder == 0 || $obj->memb_is_householder == '')) ? 'checked' : ''; ?> class="form-check-input" />
                                                    <label class="form-check-label">No</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Is Alive?</label>
                                                <div class="form-check">
                                                    <input type="radio" name="memb_is_alive" value="1" <?php echo (isset($obj->memb_is_alive) && ($obj->memb_is_alive == 1 || $obj->memb_is_alive == '')) ? 'checked' : ''; ?> class="form-check-input" />
                                                    <label class="form-check-label">Yes</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="radio" name="memb_is_alive" value="0" <?php echo (isset($obj->memb_is_alive) && $obj->memb_is_alive == 0) ? 'checked' : ''; ?> class="form-check-input" />
                                                    <label class="form-check-label">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Date of Demise</label>
                                                <input type="text" class="form-control datepicker" name="memb_demise_dt" value="<?php echo isset($obj->memb_demise_dt) ? $obj->memb_demise_dt : ''; ?>" placeholder="YYYY-MM-DD" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Approval Status</label>
                                                <select name="memb_approval" class="form-control">
                                                    <option value="0" <?php echo (isset($obj->memb_approval) && $obj->memb_approval == 0) ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="1" <?php echo (isset($obj->memb_approval) && $obj->memb_approval == 1) ? 'selected' : ''; ?>>Approved</option>
                                                    <option value="2" <?php echo (isset($obj->memb_approval) && $obj->memb_approval == 2) ? 'selected' : ''; ?>>Rejected</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Login Allowed</label>
                                                <div class="form-check">
                                                    <input type="radio" name="memb_login" value="1" <?php echo (isset($obj->memb_login) && $obj->memb_login == 1) ? 'checked' : ''; ?> class="form-check-input" />
                                                    <label class="form-check-label">Yes</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="radio" name="memb_login" value="0" <?php echo (isset($obj->memb_login) && ($obj->memb_login == 0 || $obj->memb_login == '')) ? 'checked' : ''; ?> class="form-check-input" />
                                                    <label class="form-check-label">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Profile Status</label>
                                                <select name="memb_status" class="form-control">
                                                    <option value="1" <?php echo (isset($obj->memb_status) && ($obj->memb_status == 1 || $obj->memb_status == '')) ? 'selected' : ''; ?>>Active</option>
                                                    <option value="0" <?php echo (isset($obj->memb_status) && $obj->memb_status == 0) ? 'selected' : ''; ?>>Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Login Credentials Section -->
                                    <h5 class="text-info mb-3 mt-4"><i class="fa fa-key"></i> Login Credentials</h5>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Login Username</label>
                                                <input type="text" class="form-control" name="memb_log_nam" maxlength="50" value="<?php echo isset($obj->memb_log_nam) ? $obj->memb_log_nam : ''; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Login Password</label>
                                                <input type="password" class="form-control" name="memb_log_pw" maxlength="255" value="<?php echo isset($obj->memb_log_pw) ? $obj->memb_log_pw : ''; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Show Profiles</label>
                                                <div class="form-check">
                                                    <input type="radio" name="show_profiles" value="1" <?php echo (isset($obj->show_profiles) && $obj->show_profiles == 1) ? 'checked' : ''; ?> class="form-check-input" />
                                                    <label class="form-check-label">Yes</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="radio" name="show_profiles" value="0" <?php echo (isset($obj->show_profiles) && $obj->show_profiles == 0) ? 'checked' : ''; ?> class="form-check-input" />
                                                    <label class="form-check-label">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Contact Information Section -->
                                    <h5 class="text-info mb-3 mt-4"><i class="fa fa-phone"></i> Contact Information</h5>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Photo</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input image_only" name="memb_photo" id="memb_photo" data-toggle="tooltip" data-original-title="Image Only, Below 500KB" data-size="500" data-submit='1'>
                                                    <label class="custom-file-label form-control" for="memb_photo">Choose file</label>
                                                    <input type="hidden" name="hide_memb_photo" value="<?php echo isset($obj->memb_photo) ? $obj->memb_photo : ''; ?>">
                                                    <small id="memb_photo_error" class="cis-feedback"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Primary Phone <span class="text-danger">*</span></label>
                                                <input type="tel" class="form-control" required name="memb_mobile" maxlength="15" value="<?php echo isset($obj->memb_mobile) ? $obj->memb_mobile : ''; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Secondary Phone</label>
                                                <input type="tel" class="form-control" name="memb_mobile2" maxlength="15" value="<?php echo isset($obj->memb_mobile2) ? $obj->memb_mobile2 : ''; ?>" />
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Email Address</label>
                                                <input type="email" class="form-control email_only" name="memb_email" maxlength="100" value="<?php echo isset($obj->memb_email) ? $obj->memb_email : ''; ?>" />
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Address Section -->
                                    <h5 class="text-info mb-3 mt-4"><i class="fa fa-map-marker"></i> Address Information</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Address 1 (English) <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control address_only" required name="memb_add1" maxlength="255" value="<?php echo isset($obj->memb_add1) ? $obj->memb_add1 : ''; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Address 1 (Tamil)</label>
                                                <input type="text" class="form-control" name="memb_add1_tamil" maxlength="255" value="<?php echo isset($obj->memb_add1_tamil) ? $obj->memb_add1_tamil : ''; ?>" />
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Address 2 (English)</label>
                                                <input type="text" class="form-control address_only" name="memb_add2" maxlength="255" value="<?php echo isset($obj->memb_add2) ? $obj->memb_add2 : ''; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Address 2 (Tamil)</label>
                                                <input type="text" class="form-control" name="memb_add2_tamil" maxlength="255" value="<?php echo isset($obj->memb_add2_tamil) ? $obj->memb_add2_tamil : ''; ?>" />
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Forward Address for Letters/Invitations</label>
                                                <div class="form-check">
                                                    <input type="radio" name="memb_defalut_add" value="1" <?php echo (isset($obj->memb_defalut_add) && ($obj->memb_defalut_add == 1 || $obj->memb_defalut_add == '')) ? 'checked' : ''; ?> class="form-check-input" />
                                                    <label class="form-check-label">Address 1</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="radio" name="memb_defalut_add" value="2" <?php echo (isset($obj->memb_defalut_add) && $obj->memb_defalut_add == 2) ? 'checked' : ''; ?> class="form-check-input" />
                                                    <label class="form-check-label">Address 2</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">City</label>
                                                <select name="memb_city" id="memb_city" class="form-control select2" data-placeholder="Choose a city..">
                                                    <option></option> 
                                                    <?php echo $dbconn->fnFillComboFromTable_Where("city_id","city_name","mst_city","city_name"," WHERE city_status = 1"); ?>
                                                </select> 
                                                <script>document.thisForm.memb_city.value="<?php echo $memb_city; ?>";</script>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">State</label>
                                                <input type="text" class="form-control" name="memb_state" maxlength="30" value="<?php echo $memb_state; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Presently in City, Country</label>
                                                <input type="text" class="form-control" name="memb_present_city" maxlength="100" value="<?php echo isset($obj->memb_present_city) ? $obj->memb_present_city : ''; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Native Place</label>
                                                <select name="memb_native" id="memb_native" class="form-control select2" data-placeholder="Choose a city..">
                                                    <option></option> 
                                                    <?php echo $dbconn->fnFillComboFromTable_Where("city_id","city_name","mst_city","city_name"," WHERE city_status = 1"); ?>
                                                </select> 
                                                <script>document.thisForm.memb_native.value="<?php echo $memb_native; ?>";</script>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Additional Information Section -->
                                    <h5 class="text-info mb-3 mt-4"><i class="fa fa-info-circle"></i> Additional Information</h5>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Family Deity</label>
                                                <select name="memb_fd" id="memb_fd" class="form-control select2" data-placeholder="Choose Family Deity..">
                                                    <option></option> 
                                                    <?php echo $dbconn->fnFillComboFromTable_Where("fd_id","fd_name","tbl_family_diety","fd_name"," WHERE status = 1"); ?>
                                                </select> 
                                                <script>document.thisForm.memb_fd.value="<?php echo $obj->memb_fd; ?>";</script>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Education</label>
                                                <input type="text" class="form-control" name="memb_edu" maxlength="100" value="<?php echo isset($obj->memb_edu) ? $obj->memb_edu : ''; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Occupation & Profession</label>
                                                <input type="text" class="form-control" name="memb_occ" maxlength="100" value="<?php echo isset($obj->memb_occ) ? $obj->memb_occ : ''; ?>" />
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Interests / Good at</label>
                                                <textarea name="memb_interest" class="form-control" maxlength="255"><?php echo isset($obj->memb_interest) ? $obj->memb_interest : ''; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Willing to donate blood?</label>
                                                <div class="form-check">
                                                    <input type="radio" name="memb_blood_donate" value="1" <?php echo (isset($obj->memb_blood_donate) && $obj->memb_blood_donate == 1) ? 'checked' : ''; ?> class="form-check-input" />
                                                    <label class="form-check-label">Yes</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="radio" name="memb_blood_donate" value="0" <?php echo (isset($obj->memb_blood_donate) && ($obj->memb_blood_donate == 0 || $obj->memb_blood_donate == '')) ? 'checked' : ''; ?> class="form-check-input" />
                                                    <label class="form-check-label">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Blood Group</label>
                                                <select name="memb_blood_group" class="form-control">
                                                    <option value="">Select Blood Group</option>
                                                    <option value="A+" <?php echo (isset($obj->memb_blood_group) && $obj->memb_blood_group == 'A+') ? 'selected' : ''; ?>>A+</option>
                                                    <option value="A-" <?php echo (isset($obj->memb_blood_group) && $obj->memb_blood_group == 'A-') ? 'selected' : ''; ?>>A-</option>
                                                    <option value="B+" <?php echo (isset($obj->memb_blood_group) && $obj->memb_blood_group == 'B+') ? 'selected' : ''; ?>>B+</option>
                                                    <option value="B-" <?php echo (isset($obj->memb_blood_group) && $obj->memb_blood_group == 'B-') ? 'selected' : ''; ?>>B-</option>
                                                    <option value="AB+" <?php echo (isset($obj->memb_blood_group) && $obj->memb_blood_group == 'AB+') ? 'selected' : ''; ?>>AB+</option>
                                                    <option value="AB-" <?php echo (isset($obj->memb_blood_group) && $obj->memb_blood_group == 'AB-') ? 'selected' : ''; ?>>AB-</option>
                                                    <option value="O+" <?php echo (isset($obj->memb_blood_group) && $obj->memb_blood_group == 'O+') ? 'selected' : ''; ?>>O+</option>
                                                    <option value="O-" <?php echo (isset($obj->memb_blood_group) && $obj->memb_blood_group == 'O-') ? 'selected' : ''; ?>>O-</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Last Donated Date</label>
                                                <input type="text" class="form-control datepicker" name="last_donated" value="<?php echo isset($obj->last_donated) ? $obj->last_donated : ''; ?>" placeholder="YYYY-MM-DD" />
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Remarks</label>
                                                <textarea name="memb_remarks" class="form-control" maxlength="500" rows="3"><?php echo isset($obj->memb_remarks) ? $obj->memb_remarks : ''; ?></textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                
                                <hr class="m-t-30 m-b-0">
                                <div class="form-actions">
                                    <div class="card-body text-center">
                                        <?php if($_REQUEST["memb_id"]!='') { ?>
                                            <INPUT class="btn btn-success" type="submit" name="UPDATE" value="UPDATE">
                                            <INPUT class="btn btn-secondary" type="button" name="cancel" value="CANCEL" onClick="javascript:history.go(-1);">
                                            <input type="hidden" name="txtHid" id="txtHid" value="<?php echo $_REQUEST['memb_id'];?>">
                                            <input type="hidden" name="token" id="token" value="<?php echo $_REQUEST['token'];?>">
                                        <?php }else{ ?>
                                            <INPUT class="btn btn-success" type="submit" name="SAVE" value="SAVE">
                                            <INPUT class="btn btn-secondary" type="button" name="cancel" value="CANCEL" onClick="javascript:history.go(-1);">
                                            <input type="hidden" name="txtHid" id="txtHid" value="0">
                                        <?php } ?>
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
    <script src="assets/modules/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    
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
        
        // Initialize select2
        $(".select2").select2();
        
        // Initialize datepicker
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
        
        // Calculate age when DOB changes
        $('#memb_dob').on('change', function() {
            calculateAge();
        });
        
        // Calculate age function
        function calculateAge() {
            var dob = $('#memb_dob').val();
            if(dob) {
                var today = new Date();
                var birthDate = new Date(dob);
                var age = today.getFullYear() - birthDate.getFullYear();
                var monthDiff = today.getMonth() - birthDate.getMonth();
                
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                
                $('#memb_age').val(age);
            }
        }
        
        // Show/hide demise date based on is_alive selection
        $('input[name="memb_is_alive"]').on('change', function() {
            if($(this).val() == '0') {
                $('input[name="memb_demise_dt"]').closest('.form-group').show();
            } else {
                $('input[name="memb_demise_dt"]').closest('.form-group').hide();
            }
        });
        
        // Trigger initial state
        if($('input[name="memb_is_alive"]:checked').val() == '1') {
            $('input[name="memb_demise_dt"]').closest('.form-group').hide();
        }
        
        // Set default values for new members
        if($('input[name="memb_is_alive"]:checked').length == 0) {
            $('input[name="memb_is_alive"][value="1"]').prop('checked', true);
        }
        if($('input[name="memb_is_householder"]:checked').length == 0) {
            $('input[name="memb_is_householder"][value="0"]').prop('checked', true);
        }
        if($('input[name="memb_login"]:checked').length == 0) {
            $('input[name="memb_login"][value="0"]').prop('checked', true);
        }
        if($('input[name="memb_blood_donate"]:checked').length == 0) {
            $('input[name="memb_blood_donate"][value="0"]').prop('checked', true);
        }
        if($('input[name="memb_defalut_add"]:checked').length == 0) {
            $('input[name="memb_defalut_add"][value="1"]').prop('checked', true);
        }
        if($('input[name="show_profiles"]:checked').length == 0) {
            $('input[name="show_profiles"][value="1"]').prop('checked', true);
        }
        
        // Show/hide demise date based on is_alive selection
        $('input[name="memb_is_alive"]').on('change', function() {
            if($(this).val() == '0') {
                $('input[name="memb_demise_dt"]').closest('.form-group').show();
            } else {
                $('input[name="memb_demise_dt"]').closest('.form-group').hide();
            }
        });
        
        // Trigger initial state
        if($('input[name="memb_is_alive"]:checked').val() == '1') {
            $('input[name="memb_demise_dt"]').closest('.form-group').hide();
        }
        
        // Set default values for new members
        if($('input[name="memb_is_alive"]:checked').length == 0) {
            $('input[name="memb_is_alive"][value="1"]').prop('checked', true);
        }
        if($('input[name="memb_is_householder"]:checked').length == 0) {
            $('input[name="memb_is_householder"][value="0"]').prop('checked', true);
        }
        if($('input[name="memb_login"]:checked').length == 0) {
            $('input[name="memb_login"][value="0"]').prop('checked', true);
        }
        if($('input[name="memb_blood_donate"]:checked').length == 0) {
            $('input[name="memb_blood_donate"][value="0"]').prop('checked', true);
        }
        if($('input[name="memb_defalut_add"]:checked').length == 0) {
            $('input[name="memb_defalut_add"][value="1"]').prop('checked', true);
        }
        if($('input[name="show_profiles"]:checked').length == 0) {
            $('input[name="show_profiles"][value="1"]').prop('checked', true);
        }
        
        // Auto-generate login username if login is allowed
        $('input[name="memb_login"]').on('change', function() {
            if($(this).val() == '1') {
                var fname = $('input[name="memb_fname"]').val();
                var code = $('input[name="memb_code"]').val();
                if(fname && code) {
                    var username = fname.toLowerCase().replace(/[^a-z0-9]/g, '') + code.toLowerCase();
                    $('input[name="memb_log_nam"]').val(username);
                }
            }
        });
        
        // Auto-generate password if login is allowed
        $('input[name="memb_login"]').on('change', function() {
            if($(this).val() == '1') {
                var code = $('input[name="memb_code"]').val();
                if(code) {
                    var password = code + '@' + Math.random().toString(36).substr(2, 8);
                    $('input[name="memb_log_pw"]').val(password);
                }
            }
        });
        
        // Auto-fill city and state based on PIN code (if available)
        $('input[name="memb_pincode"]').on('blur', function() {
            var pincode = $(this).val();
            if(pincode && pincode.length == 6) {
                // This is a placeholder for PIN code lookup functionality
                // You can implement actual PIN code API call here
                console.log('PIN code lookup for:', pincode);
            }
        });
        
        // Show/hide login credentials based on login allowed selection
        $('input[name="memb_login"]').on('change', function() {
            if($(this).val() == '1') {
                $('input[name="memb_log_nam"], input[name="memb_log_pw"]').closest('.form-group').show();
            } else {
                $('input[name="memb_log_nam"], input[name="memb_log_pw"]').closest('.form-group').hide();
            }
        });
        
        // Trigger initial state for login fields
        if($('input[name="memb_login"]:checked').val() == '0') {
            $('input[name="memb_log_nam"], input[name="memb_log_pw"]').closest('.form-group').hide();
        }
    });
    
    function fnValidate(){
        if(isNull(document.thisForm.memb_code,"member code..!")){ return false; }
        if(isNull(document.thisForm.memb_fname,"first name..!")){ return false; }
        if(isNull(document.thisForm.memb_add1,"address..!")){ return false; }
        if(isNull(document.thisForm.memb_mobile,"primary phone..!")){ return false; }
        if(isNull(document.thisForm.memb_gender,"gender..!")){ return false; }
        
        if(document.thisForm.memb_email.value != ""){
            if(notEmail(document.thisForm.memb_email,"email id..!")){ return false; }	
        }
        
        // Validate PIN code if provided
        if(document.thisForm.memb_pincode.value != ""){
            if(!/^\d{6}$/.test(document.thisForm.memb_pincode.value)){
                alert("Please enter a valid 6-digit PIN code!");
                document.thisForm.memb_pincode.focus();
                return false;
            }
        }
        
        // Validate mobile number format
        if(document.thisForm.memb_mobile.value != ""){
            if(!/^\d{10}$/.test(document.thisForm.memb_mobile.value.replace(/\D/g, ''))){
                alert("Please enter a valid 10-digit mobile number!");
                document.thisForm.memb_mobile.focus();
                return false;
            }
        }
        
        document.thisForm.submit();
    }
    </script>
</body>
</html>
