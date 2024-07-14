<?php
session_start();

require './config.php';

$pdo = new mypdo();


if (isset($_SESSION['admin_id'])) {
	$admin_id = $_SESSION['admin_id'];
} else {

	die('Login');
}



$global_report = "";

$formats = array('png', 'jpg', 'jpeg', 'gif');





///##################################################
/////     UPDATE GENERAL INFORMATION
///####################################################
if (isset($_POST['ch']) && $_POST['ch'] == "personal_information") {


	$errors = [];


	$fname = plain_validate($_POST['fname']);
	$lname = plain_validate($_POST['lname']);
	$phone = plain_validate($_POST['phone']);
	

	$updated_at = date("Y-m-d");
	$stmt = $pdo->pdc->prepare("UPDATE admins SET first_name = ?, last_name = ?, phone = ?  WHERE admin_id = ?");
	$stmt->bindParam(1, $fname, PDO::PARAM_STR);
	$stmt->bindParam(2, $lname, PDO::PARAM_STR);
	$stmt->bindParam(3, $phone, PDO::PARAM_STR);
	$stmt->bindParam(4, $admin_id, PDO::PARAM_INT);
	$stmt->execute();


	die("PASS");
}



///##################################################
/////     CHANGE PASSWORD
///####################################################
if (isset($_POST['ch']) && $_POST['ch'] == "update_password") {


	$lag_index = trim($_POST['lag_index']);

	$errors = [];
	$old_password = $_POST['old_password'];
	$new_password = $_POST['new_password'];
	$rnew_password = $_POST['rnew_password'];


	if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&+=-_.#^()><])[A-Za-z\d@$!%*?&+=-_.#^()><]{8,}$/', $new_password)) {
		$errors[] = ['new_password', "Minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character:"];
	} elseif ($new_password != $rnew_password) {
		$errors[] = ['rnew_password', "Password not match"];
	}

	$buser = $pdo->get_one("SELECT * FROM admins WHERE admin_id = ?", $admin_id);

	if (!password_verify($old_password, $buser['password'])) {
		$errors[] = ['old_password', "Old password not correct"];
	}
	if (count($errors) > 0) {
		// $errors[] = ['form_info', 'There is an error with your submission'];
		die(json_encode($errors));
	}

	$password = password_hash($new_password, PASSWORD_DEFAULT);

	$stmt = $pdo->pdc->prepare("UPDATE admins SET  password = ? WHERE admin_id = ?");

	$stmt->bindParam(1, $password, PDO::PARAM_STR);
	$stmt->bindParam(2, $admin_id, PDO::PARAM_INT);
	$stmt->execute();
	die("PASS");
}





