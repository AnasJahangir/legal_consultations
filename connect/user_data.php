<?php
session_start();
if (!isset($_SESSION['admin_id']))
	die("Please login");


require './config.php';


$admin_id = $_SESSION['admin_id'];

$pdo = new mypdo();



if (isset($_GET['ch']) && $_GET['ch'] == "get_data") {


	$is_complex = false;

	$wheres = "";

	$cols = ["user_id", "first_name", "last_name",  "email", "phone", "created_at"];

	$table = "users";

	$primaryKey = 'user_id';
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





if (isset($_POST['ch']) && $_POST['ch'] == "update_password") {


	$user_id = (int) $_POST['user_id'];

	$password = $_POST['password'];


	$password = password_hash($password, PASSWORD_DEFAULT);

	$stmt = $pdo->pdc->prepare("UPDATE users SET password = ? WHERE user_id = ?");
	$stmt->bindParam(1, $password, PDO::PARAM_STR);
	$stmt->bindParam(2, $user_id, PDO::PARAM_INT);

	$stmt->execute();

	die("PASS" . $user_id);

} elseif (isset($_POST['ch']) && $_POST['ch'] == "remove_user") {


	$user_id = (int) $_POST['user_id'];


	$pdo->exec_query("DELETE FROM  users WHERE user_id = '$user_id'");

	die("PASS" . $user_id);

}

