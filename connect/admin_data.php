<?php
session_start();
if (!isset($_SESSION['admin_id']))
	die("Please login");


require './config.php';


$user_id = $_SESSION['admin_id'];

$pdo = new mypdo();


$user = $pdo->get_one("SELECT * FROM admins WHERE admin_id = ?", $user_id);

if ($user['role'] != '1') {
	die(json_encode(["error" => 'auttentication error. Please login']));
}


if (isset($_GET['ch']) && $_GET['ch'] == "get_data") {


	$is_complex = false;

	$cols = ["admin_id", "first_name", "last_name", "email", "phone", "role"];

	$table = "admins";
	$primaryKey = 'admin_id';
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




if (isset($_POST['ch']) && $_POST['ch'] == "create_admin") {

	$cols = ["first_name", "last_name", "email", "phone", "role", "created_at"];
	$cols2 = ["fname", "lname", "email", "phone", "role", "created_at"];


	$cols_param = "?, ?, ?, ?, ?, ?";

	$created_at = date('Y-m-d H:i:s');

	$qry = "INSERT INTO  admins(" . implode(", ", $cols) . ")VALUES(" . $cols_param . ")";


	$stmt = $pdo->pdc->prepare($qry);

	$j = 0;

	$this_val = [];
	for ($i = 0; $i < (count($cols) - 1); $i++) {
		$this_val[$i] = plain_validate($_POST[$cols2[$i]]);
	}
	for ($i = 0; $i < (count($cols) - 1); $i++) {
		$j = $i + 1;
		$stmt->bindParam($j, $this_val[$i], PDO::PARAM_STR);
	}
	$j++;
	$stmt->bindParam($j, $created_at, PDO::PARAM_STR);
	$stmt->execute();
	$admin_id = $pdo->pdc->lastInsertId();
	die("PASS" . $admin_id);

}




if (isset($_POST['ch']) && $_POST['ch'] == "edit_admin") {

	$cols = ["first_name", "last_name", "email", "phone", "role",];
	$cols2 = ["fname", "lname", "email", "phone", "role",];

	$admin_id = intval($_POST['admin_id']);
	$qry = "UPDATE admins SET " . implode("= ?,", $cols) . " = ?  WHERE admin_id = '$admin_id'";

	$stmt = $pdo->pdc->prepare($qry);

	$this_val = [];
	for ($i = 0; $i < count($cols); $i++) {

		$this_val[$i] = plain_validate($_POST[$cols2[$i]]);
		if($admin_id == 1 && $cols[$i] == "role"){
			$this_val[$i] = "1"; // This admin will always be supper admin
		}
	}
	for ($i = 0; $i < count($cols); $i++) {
		$j = $i + 1;
		$stmt->bindParam($j, $this_val[$i], PDO::PARAM_STR);
	}
	$stmt->execute();

	die("PASS");
}



if (isset($_POST['ch']) && $_POST['ch'] == "update_password") {


	$admin_id = (int) $_POST['admin_id'];

	$password = $_POST['password'];



	$password = password_hash($password, PASSWORD_DEFAULT);

	$stmt = $pdo->pdc->prepare("UPDATE admins SET password = ? WHERE admin_id = ?");
	$stmt->bindParam(1, $password, PDO::PARAM_STR);
	$stmt->bindParam(2, $admin_id, PDO::PARAM_INT);

	$stmt->execute();

	die("PASS" . $admin_id);
} elseif (isset($_POST['ch']) && $_POST['ch'] == "remove_admin") {


	$admin_id = (int) $_POST['admin_id'];

	if($admin_id == "1"){
		die("You can not delete this account, This account is assumed as based admin");
	}

	
	$pdo->exec_query("DELETE FROM  admins WHERE admin_id = '$admin_id'");

	die("PASS" . $admin_id);
	
} 

