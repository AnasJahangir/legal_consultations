<?php
session_start();

require './config.php';

$pdo = new mypdo();


if (isset($_SESSION['user_id'])) {
	$user_id = $_SESSION['user_id'];
} else {

	die('Pleaase Login');
}

$user = $pdo->get_one("SELECT * FROM users WHERE user_id = ?", $user_id);





///##################################################
/////     NEW APPOINTMENT
///####################################################
if (isset($_POST['ch']) && $_POST['ch'] == "new_appointment") {


	$errors = [];

	$appointment_date = date("Y-m-d", strtotime($_POST['appointment_date']));
	$appointment_time = date("H:i:s", strtotime("2024-01-01 " . $_POST['appointment_time'])); //ensure time is J;i:s

	$payment_method = plain_validate($_POST['payment_method']);
	$service_description = plain_validate($_POST['service_description']);

	$service_id = intval($_POST['service_id']);
	$subservice_id = $_POST['subservice_id'];
	$call_method_id = $_POST['call_method'];

	$subservice = "";
	$call_method = "";

	$service = $pdo->get_one("SELECT * FROM services WHERE service_id = ?", $service_id);
	if ($service == null) {
		die("Invalid service selected");
	}
	$price = $service['default_price'];
	if ($call_method_id != "") {

		$call_method_option = $glob_sub_services[$call_method_id];
		$sub_service_option = $glob_sub_services[$subservice_id];
		$per = $call_method_option["per"];
		$price = $price * $per;
		$subservice = $sub_service_option['name'];
		$call_method = $call_method_option['name'];

	} else {
		if ($subservice_id != "") {
			$sub_service_option = $glob_sub_services[$subservice_id];
			$per = $sub_service_option["per"];
			$price = $price * $per;
			$subservice = $sub_service_option['name'];
		}
	}




	// Validate future datetime
	$currentDateTime = new DateTime();
	$appointmentDateTime = new DateTime("$appointment_date $appointment_time");
	if ($appointmentDateTime <= $currentDateTime) {
		die("Appointment time must be in the future.");
	}
	// Validate appointment slot
	if (!isValidAppointment($appointment_date, $appointment_time)) {
		die("Invalid appointment time for the given day.");
	}



	// Check for overlaps
	$stmt = $pdo->pdc->prepare("SELECT COUNT(*) FROM reservations WHERE appointment_date = ? AND appointment_time = ?");
	$stmt->bindParam(1, $appointment_date, PDO::PARAM_STR);
	$stmt->bindParam(2, $appointment_time, PDO::PARAM_STR);
	$stmt->execute();
	$count = $stmt->fetchColumn();
	if ($count > 0) {
		die("This appointment slot is already taken.");
	}

	$created_at = date("Y-m-d H:i");
	// Insert the reservation
	$stmt = $pdo->pdc->prepare("INSERT INTO reservations (user_id, service_id, subservice, call_method, service_description, payment_method, appointment_date, appointment_time, service_fee, created_at, updated_at) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	$stmt->bindParam(1, $user_id, PDO::PARAM_INT);
	$stmt->bindParam(2, $service_id, PDO::PARAM_INT);
	$stmt->bindParam(3, $subservice, PDO::PARAM_STR);
	$stmt->bindParam(4, $call_method, PDO::PARAM_STR);
	$stmt->bindParam(5, $service_description, PDO::PARAM_STR);
	$stmt->bindParam(6, $payment_method, PDO::PARAM_STR);
	$stmt->bindParam(7, $appointment_date, PDO::PARAM_STR);
	$stmt->bindParam(8, $appointment_time, PDO::PARAM_STR);
	$stmt->bindParam(9, $price, PDO::PARAM_STR);
	$stmt->bindParam(10, $created_at, PDO::PARAM_STR);
	$stmt->bindParam(11, $created_at, PDO::PARAM_STR);
	$stmt->execute();

	if ($stmt->rowCount() > 0) {

		$reservation_id = $pdo->pdc->lastInsertId();
		// Appoint,ent Modified
		$message = "New Appointment booking for <b>" . $service['service_name']."</b> by  <b>".$user['first_name']." ".$user['last_name']."</b>";
		$stmt = $pdo->pdc->prepare("INSERT INTO  admin_messages(reservation_id, message, created_at) VALUES(?, ?, ?)");
		$stmt->bindParam(1, $reservation_id, PDO::PARAM_INT);
		$stmt->bindParam(2, $message, PDO::PARAM_STR);
		$stmt->bindParam(3, $created_at, PDO::PARAM_STR);
		$stmt->execute();


		die("PASS");
	} else {
		die("There was an error booking this appointment");
	}

}






///##################################################
/////     MODIFY APPOINTMENT
///####################################################
if (isset($_POST['ch']) && $_POST['ch'] == "modify_appointment") {

	$errors = [];

	$reservation_id = intval($_POST['reservation_id']);

	$appointment_date = date("Y-m-d", strtotime($_POST['appointment_date']));
	$appointment_time = date("H:i:s", strtotime("2024-01-01 " . $_POST['appointment_time'])); //ensure time is J;i:s

	$payment_method = plain_validate($_POST['payment_method']);
	$service_description = plain_validate($_POST['service_description']);

	$service_id = intval($_POST['service_id']);
	$subservice_id = $_POST['subservice_id'];
	$call_method_id = $_POST['call_method'];

	$subservice = "";
	$call_method = "";

	$service = $pdo->get_one("SELECT * FROM services WHERE service_id = ?", $service_id);
	if ($service == null) {
		die("Invalid service selected");
	}
	$price = $service['default_price'];
	if ($call_method_id != "") {

		$call_method_option = $glob_sub_services[$call_method_id];
		$sub_service_option = $glob_sub_services[$subservice_id];
		$per = $call_method_option["per"];
		$price = $price * $per;
		$subservice = $sub_service_option['name'];
		$call_method = $call_method_option['name'];

	} else {
		if ($subservice_id != "") {
			$sub_service_option = $glob_sub_services[$subservice_id];
			$per = $sub_service_option["per"];
			$price = $price * $per;
			$subservice = $sub_service_option['name'];
		}
	}


	// Validate future datetime
	$currentDateTime = new DateTime();
	$appointmentDateTime = new DateTime("$appointment_date $appointment_time");
	if ($appointmentDateTime <= $currentDateTime) {
		die("Appointment time must be in the future.");
	}
	// Validate appointment slot
	if (!isValidAppointment($appointment_date, $appointment_time)) {
		die("Invalid appointment time for the given day.");
	}


	$reservation_bfr = $pdo->get_one("SELECT  b.service_name AS `Service`,  a.subservice AS `Sub Service`, 	a.call_method AS `Call method`, a.service_description AS `Service description`, a.payment_method AS `Payment method`, a.appointment_date AS `Appointment date`, a.appointment_time AS `Appointment time`, a.service_fee AS `Service fee` FROM reservations a LEFT JOIN services b ON a.service_id = b.service_id  WHERE a.reservation_id = ?", $reservation_id);

	if ($reservation_bfr == null) {
		die("Error. permission denied");
	}


	// Check for overlaps
	$stmt = $pdo->pdc->prepare("SELECT COUNT(*) FROM reservations WHERE reservation_id != ? AND appointment_date = ? AND appointment_time = ?");
	$stmt->bindParam(1, $reservation_id, PDO::PARAM_STR);
	$stmt->bindParam(2, $appointment_date, PDO::PARAM_STR);
	$stmt->bindParam(3, $appointment_time, PDO::PARAM_STR);
	$stmt->execute();
	$count = $stmt->fetchColumn();
	if ($count > 0) {
		die("This appointment slot is already taken.");
	}

	$created_at = date("Y-m-d H:i");
	// Insert the reservation
	$stmt = $pdo->pdc->prepare("UPDATE  reservations SET service_id = ?, subservice = ?, call_method = ?, service_description = ?, payment_method = ?, appointment_date = ?, appointment_time = ?, service_fee = ?, updated_at = ?  WHERE user_id = ? AND reservation_id = ?");
	$stmt->bindParam(1, $service_id, PDO::PARAM_INT);
	$stmt->bindParam(2, $subservice, PDO::PARAM_STR);
	$stmt->bindParam(3, $call_method, PDO::PARAM_STR);
	$stmt->bindParam(4, $service_description, PDO::PARAM_STR);
	$stmt->bindParam(5, $payment_method, PDO::PARAM_STR);
	$stmt->bindParam(6, $appointment_date, PDO::PARAM_STR);
	$stmt->bindParam(7, $appointment_time, PDO::PARAM_STR);
	$stmt->bindParam(8, $price, PDO::PARAM_STR);
	$stmt->bindParam(9, $created_at, PDO::PARAM_STR);
	$stmt->bindParam(10, $user_id, PDO::PARAM_INT);
	$stmt->bindParam(11, $reservation_id, PDO::PARAM_INT);

	$stmt->execute();
	if ($stmt->rowCount() > 0) {


		$reservation_now = $pdo->get_one("SELECT  b.service_name AS `Service`,  a.subservice AS `Sub Service`, 	a.call_method AS `Call method`, a.service_description AS `Service description`, a.payment_method AS `Payment method`, a.appointment_date AS `Appointment date`, a.appointment_time AS `Appointment time`, a.service_fee AS `Service fee` FROM reservations a LEFT JOIN services b ON a.service_id = b.service_id  WHERE a.reservation_id = ?", $reservation_id);

		$changes = get_difference($reservation_bfr, $reservation_now);
		$label = "Appointment modified";

		// Appoint,ent Modified
		$stmt = $pdo->pdc->prepare("INSERT INTO  reservation_changes (reservation_id, label, changes, created_at) VALUES(?, ?, ?, ?)");
		$stmt->bindParam(1, $reservation_id, PDO::PARAM_INT);
		$stmt->bindParam(2, $label, PDO::PARAM_STR);
		$stmt->bindParam(3, $changes, PDO::PARAM_STR);
		$stmt->bindParam(4, $created_at, PDO::PARAM_STR);
		$stmt->execute();

		// Increase modify Count
		if ($stmt->rowCount()) {
			$pdo->exec_query("UPDATE reservations SET modify_count = modify_count + 1 WHERE reservation_id = ?", $reservation_id);
		}

		// Notify Admin  Tray

		// Appoint,ent Modified
		$message = "Appointment booking for <b>" . $reservation_bfr['Service'] . "</b>  has been modified  by  <b>".$user['first_name']." ".$user['last_name']."</b>";

		$stmt = $pdo->pdc->prepare("INSERT INTO  admin_messages(reservation_id, message, created_at) VALUES(?, ?, ?)");
		$stmt->bindParam(1, $reservation_id, PDO::PARAM_INT);
		$stmt->bindParam(2, $message, PDO::PARAM_STR);
		$stmt->bindParam(3, $created_at, PDO::PARAM_STR);
		$stmt->execute();

		die("PASS");


	} else {
		die("No update was made");
	}

}






///##################################################
/////     RESCHEDULE APPOINTMENT
///####################################################
if (isset($_POST['ch']) && $_POST['ch'] == "reschedule_appointment") {

	$errors = [];

	$reservation_id = intval($_POST['reservation_id']);

	$appointment_date = date("Y-m-d", strtotime($_POST['appointment_date']));
	$appointment_time = date("H:i:s", strtotime("2024-01-01 " . $_POST['appointment_time'])); //ensure time is J;i:s


	// Validate future datetime
	$currentDateTime = new DateTime();
	$appointmentDateTime = new DateTime("$appointment_date $appointment_time");
	if ($appointmentDateTime <= $currentDateTime) {
		die("Appointment time must be in the future.");
	}
	// Validate appointment slot
	if (!isValidAppointment($appointment_date, $appointment_time)) {
		die("Invalid appointment time for the given day.");
	}


	$reservation_bfr = $pdo->get_one("SELECT b.service_name AS `Service`,  a.appointment_date AS `Appointment date`, a.appointment_time AS `Appointment time`, a.service_fee AS `Service fee` FROM reservations a LEFT JOIN services b ON a.service_id = b.service_id  WHERE a.reservation_id = ?", $reservation_id);

	if ($reservation_bfr == null) {
		die("Error. permission denied");
	}


	// Check for overlaps
	$stmt = $pdo->pdc->prepare("SELECT COUNT(*) FROM reservations WHERE reservation_id != ? AND appointment_date = ? AND appointment_time = ?");
	$stmt->bindParam(1, $reservation_id, PDO::PARAM_STR);
	$stmt->bindParam(2, $appointment_date, PDO::PARAM_STR);
	$stmt->bindParam(3, $appointment_time, PDO::PARAM_STR);
	$stmt->execute();
	$count = $stmt->fetchColumn();
	if ($count > 0) {
		die("This appointment slot is already taken.");
	}

	$created_at = date("Y-m-d H:i");
	// Insert the reservation
	$stmt = $pdo->pdc->prepare("UPDATE  reservations SET appointment_date = ?, appointment_time = ?,  updated_at = ?  WHERE user_id = ? AND reservation_id = ?");
	$stmt->bindParam(1, $appointment_date, PDO::PARAM_STR);
	$stmt->bindParam(2, $appointment_time, PDO::PARAM_STR);
	$stmt->bindParam(3, $created_at, PDO::PARAM_STR);
	$stmt->bindParam(4, $user_id, PDO::PARAM_INT);
	$stmt->bindParam(5, $reservation_id, PDO::PARAM_INT);

	$stmt->execute();
	if ($stmt->rowCount() > 0) {


		$reservation_now = $pdo->get_one("SELECT  b.service_name AS `Service`, a.appointment_date AS `Appointment date`, a.appointment_time AS `Appointment time`, a.service_fee AS `Service fee` FROM reservations a LEFT JOIN services b ON a.service_id = b.service_id  WHERE a.reservation_id = ?", $reservation_id);

		$changes = get_difference($reservation_bfr, $reservation_now);
		$label = "Appointment reschedule";

		// Appoint,ent Modified
		$stmt = $pdo->pdc->prepare("INSERT INTO  reservation_changes (reservation_id, label, changes, created_at) VALUES(?, ?, ?, ?)");
		$stmt->bindParam(1, $reservation_id, PDO::PARAM_INT);
		$stmt->bindParam(2, $label, PDO::PARAM_STR);
		$stmt->bindParam(3, $changes, PDO::PARAM_STR);
		$stmt->bindParam(4, $created_at, PDO::PARAM_STR);
		$stmt->execute();

		// Increase modify Count
		if ($stmt->rowCount()) {
			$pdo->exec_query("UPDATE reservations SET modify_count = modify_count + 1 WHERE reservation_id = ?", $reservation_id);
		}

		// Notify Admin  Tray

		// Appoint,ent Modified
		$message = "Appointment booking for <b>" . $reservation_bfr['Service'] . "</b> has been reschedule by  <b>".$user['first_name']." ".$user['last_name']."</b>";

		$stmt = $pdo->pdc->prepare("INSERT INTO  admin_messages(reservation_id, message, created_at) VALUES(?, ?, ?)");
		$stmt->bindParam(1, $reservation_id, PDO::PARAM_INT);
		$stmt->bindParam(2, $message, PDO::PARAM_STR);
		$stmt->bindParam(3, $created_at, PDO::PARAM_STR);
		$stmt->execute();

		die("PASS");


	} else {
		die("No update was made");
	}

}




function isValidAppointment($date, $time)
{
	$dayOfWeek = date('N', strtotime($date)); // 1 (for Monday) through 7 (for Sunday)

	// Convert time to timestamp
	$timeTimestamp = strtotime($time);

	if ($dayOfWeek == 4) { // Thursday
		return $timeTimestamp >= strtotime('08:00:00') && $timeTimestamp < strtotime('17:00:00');
	} elseif ($dayOfWeek >= 1 && $dayOfWeek <= 4) { // Sunday to Wednesday

		return ($timeTimestamp >= strtotime('08:00:00') && $timeTimestamp < strtotime('13:00:00')) ||
			($timeTimestamp >= strtotime('17:00:00') && $timeTimestamp < strtotime('20:00:00'));
	}
	return false;
}


function get_difference($array1, $array2)
{

	$changes = "";
	foreach ($array1 as $key => $val1) {
		$val2 = $array2[$key];
		if (trim($val1) != trim($val2)) {
			$changes .= "<div>$key modified from <b>" . $val1 . "</b> to <b>" . $val2 . "</b><div>";
		}
	}
	return $changes;
}