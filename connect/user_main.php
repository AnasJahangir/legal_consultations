<?php
session_start();

require './config.php';

$pdo = new mypdo();


if (isset($_SESSION['user_id'])) {
	$user_id = $_SESSION['user_id'];
} else {

	die('Login');
}





$global_report = "";






///##################################################
/////     UPDATE GENERAL INFORMATION
///####################################################
if (isset($_POST['ch']) && $_POST['ch'] == "personal_information") {


	$errors = [];


	$first_name = plain_validate($_POST['first_name']);
	$last_name = plain_validate($_POST['last_name']);
	$phone = plain_validate($_POST['phone']);

	
	$errors = [];


	// Validate first name
	if (strlen($first_name) < 3 || !preg_match('/^[a-zA-Z]{3,}$/', $first_name)) {
		$errors[] = ['first_name', 'First name must be at least 3 letters long and contain letters only'];
	}

	// Validate last name
	if (strlen($last_name) < 3 || !preg_match('/^[a-zA-Z]{3,}$/', $last_name)) {
		$errors[] = ['last_name', 'Last name must be at least 3 letters long and contain letters only'];
	}

	
	// Validate mobile number
	if (!preg_match('/^05\d{8}$/', $phone)) {
		$errors[] = ['phone', 'Mobile number must be 10 digits long, contain only numbers, and begin with 05'];
	}


	if (count($errors) > 0) {
		die(json_encode($errors));
	}



	$updated_at = date("Y-m-d");
	$stmt = $pdo->pdc->prepare("UPDATE users SET first_name = ?, last_name = ?, phone = ?  WHERE user_id = ?");
	$stmt->bindParam(1, $first_name, PDO::PARAM_STR);
	$stmt->bindParam(2, $last_name, PDO::PARAM_STR);
	$stmt->bindParam(3, $phone, PDO::PARAM_STR);
	$stmt->bindParam(4, $user_id, PDO::PARAM_INT);
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

	$buser = $pdo->get_one("SELECT * FROM users WHERE user_id = ?", $user_id);

	if (!password_verify($old_password, $buser['password'])) {
		$errors[] = ['old_password', "Old password not correct"];
	}
	if (count($errors) > 0) {
		// $errors[] = ['form_info', 'There is an error with your submission'];
		die(json_encode($errors));
	}

	$password = password_hash($new_password, PASSWORD_DEFAULT);

	$stmt = $pdo->pdc->prepare("UPDATE users SET  password = ? WHERE user_id = ?");

	$stmt->bindParam(1, $password, PDO::PARAM_STR);
	$stmt->bindParam(2, $user_id, PDO::PARAM_INT);
	$stmt->execute();
	die("PASS");
}


///##################################################
/////     UPDATE EMAIL ADDRESS
///####################################################
if (isset($_POST['ch']) && $_POST['ch'] == "update_email") {


	$errors = [];
	$email = $_POST['email'];

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$errors[] = ['update_email', 'Invalid email address'];
	} else {
		$allowed_domains = ['gmail.com', 'outlook.sa', 'outlook.com', 'hotmail.com'];
		$domain = substr(strrchr($email, "@"), 1);
		if (!in_array($domain, $allowed_domains)) {
			$errors[] = ['update_email', 'Email must end with @gmail.com, @outlook.sa, @outlook.com, or @hotmail.com'];
		} else {
			$user = $pdo->get_one("SELECT * FROM users WHERE email = ? AND  user_id != '$user_id'", $email);
			if ($user != null) {
				$errors[] = ['update_email', 'Email address already exists'];
			}
		}
	}

	if (count($errors) > 0) {
		die(json_encode($errors));
	}

	$stmt = $pdo->pdc->prepare("UPDATE users SET  email = ? WHERE user_id = ?");

	$stmt->bindParam(1, $email, PDO::PARAM_STR);
	$stmt->bindParam(2, $user_id, PDO::PARAM_INT);
	$stmt->execute();
	die("PASS");
}



// GET RESERVATION RECORDS

if (isset($_POST['ch']) && $_POST['ch'] == "cancel_reservation") {

	$reservation_id = intval($_POST['reservation_id']);


	
	$reservation_bfr = $pdo->get_one("SELECT  b.service_name AS `Service`,  a.subservice AS `Sub Service`, 	a.call_method AS `Call method`, a.service_description AS `Service description`, a.payment_method AS `Payment method`, a.appointment_date AS `Appointment date`, a.appointment_time AS `Appointment time`, a.service_fee AS `Service fee`  FROM reservations a LEFT JOIN services b ON a.service_id = b.service_id  WHERE a.reservation_id = ?", $reservation_id);


	$pdo->exec_query("DELETE FROM reservations WHERE user_id = '$user_id' AND reservation_id = ?", $reservation_id);

	$user = $pdo->get_one("SELECT * FROM users WHERE user_id = ?", $user_id);

	// Appoint,ent Modified
	$message = "Appointment booking for  <b>" . $reservation_bfr['Service'] . "</b>  was canceled by  <b>".$user['first_name']." ".$user['last_name']."</b>";

	$created_at = date("Y-m-d H:i:s");

	$stmt = $pdo->pdc->prepare("INSERT INTO  admin_messages(reservation_id, message, created_at) VALUES(?, ?, ?)");
	$stmt->bindParam(1, $reservation_id, PDO::PARAM_INT);
	$stmt->bindParam(2, $message, PDO::PARAM_STR);
	$stmt->bindParam(3, $created_at, PDO::PARAM_STR);
	$stmt->execute();


	die("PASS");


}


///   GET Reservation chnages

if (isset($_GET['ch']) && $_GET['ch'] == "reservation_changes") {

	$reservation_id = intval($_GET['reservation_id']);

	$data = $pdo->get_all_num("SELECT created_at, label, changes FROM reservation_changes WHERE reservation_id = '$reservation_id'  ORDER BY created_at ASC");

	die(json_encode($data));


}



// GET RESERVATION RECORDS

if (isset($_GET['ch']) && $_GET['ch'] == "get_data") {


	$is_complex = false;


	$wheres = " WHERE user_id = '".$user_id."'";

	$cols = ["reservation_id", "created_at", "service", "subservice", "call_method", "payment_method", "service_fee",  "appointment_date", "appointment_time", "service_description", "updated_at", "modify_count"];

	$table = "(SELECT a.*,  b.service_name AS service  FROM reservations a LEFT JOIN services b ON a.service_id = b.service_id $wheres) tmp";
	$primaryKey = 'service_id';

	$columns = array();
	for ($i = 0; $i < count($cols); $i++) {
		$columns[] = array('db' => $cols[$i], 'dt' => $i);
	}

	// SQL server connection information
	$sql_details = array(
		'user' => dbuser,
		'pass' => dbpass,
		'db' => dbname,
		'host' => dbhost
	);
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * If you just want to use the basic configuration for DataTables with PHP
	 * server-side, there is no need to edit below this line.
	 */
	require('ssp_class.php');

	if ($is_complex) {
		echo json_encode(
			SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, null, $wheres)
		);
	} else {
		echo json_encode(
			SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
		);
	}
	die();
}




