<?php
session_start();

require './config.php';

$pdo = new mypdo();


if (isset($_SESSION['admin_id'])) {

} else {

	die('Login');
}



$global_report = "";





///   DISMISS ADMIN MESSAGE

if (isset($_POST['ch']) && $_POST['ch'] == "dismiss_message") {

	$id = intval($_POST['id']);

	$pdo->exec_query("DELETE FROM admin_messages  WHERE id = '$id'");

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


	$wheres = "";

	$cols = ["reservation_id", "created_at", "service", "subservice", "call_method", "payment_method", "service_fee",  "appointment_date", "appointment_time", "service_description", "updated_at", "modify_count", "first_name", "last_name", "phone", "email"];

	$table = "(SELECT a.*,  b.service_name AS service, c.first_name, c.last_name, c.phone, c.email  FROM reservations a LEFT JOIN services b ON a.service_id = b.service_id LEFT JOIN users c ON a.user_id = c.user_id $wheres) tmp";
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




