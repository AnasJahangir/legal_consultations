<?php
session_start();
if (!isset($_SESSION['admin_id']))
	die("Please login");


require './config.php';


$pdo = new mypdo();


if (isset($_GET['ch']) && $_GET['ch'] == "get_data") {

	$cols = ["service_id", "service_name", "service_description", "default_price", "option"];

	$table = "services";
	$primaryKey = 'service_id';
	$columns = array();
	for ($i = 0; $i < count($cols); $i++) {
		$columns[] = array('db' => $cols[$i], 'dt' => $i);
	}

	// $wheres = "parent_id IS NULL";

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

	$data = SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns);
	// $data = SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, null, $wheres);

	// for($i = 0;  $i < count($data["data"]); $i++){
	// 	// fetch all sibservice
	// 	$sub_data = $pdo->get_all("SELECT * FROM services WHERE parent_id = ? ORDER BY priority, service_id", $data["data"][$i][0]);
	// 	$data["data"][$i][] = $sub_data;

	// }

	echo json_encode($data);
	die();
}




if (isset($_POST['ch']) && $_POST['ch'] == "create") {

	
	$qry = "INSERT INTO  services(service_name, service_description, default_price, `option`)VALUES(?, ?, ?, ?)";

	$stmt = $pdo->pdc->prepare($qry);
	
	$stmt->bindParam(1, $_POST['name'], PDO::PARAM_STR);
	$stmt->bindParam(2, $_POST['description'], PDO::PARAM_STR);
	$stmt->bindParam(3, $_POST['price'], PDO::PARAM_STR);
	$stmt->bindParam(4, $_POST['option'], PDO::PARAM_STR);
	$stmt->execute();

	$service_id = $pdo->pdc->lastInsertId();

	$data = ["service_id" => $service_id];

	echo json_encode($data);
	die();
}




if (isset($_POST['ch']) && $_POST['ch'] == "edit") {

	
	$service_id = intval($_POST['service_id']);


	
	$qry = "UPDATE  services SET service_name = ?, service_description = ?, default_price = ?, `option` = ? WHERE service_id = ?";

	$stmt = $pdo->pdc->prepare($qry);
	
	$stmt->bindParam(1, $_POST['name'], PDO::PARAM_STR);
	$stmt->bindParam(2, $_POST['description'], PDO::PARAM_STR);
	$stmt->bindParam(3, $_POST['price'], PDO::PARAM_STR);
	$stmt->bindParam(4, $_POST['option'], PDO::PARAM_STR);
	$stmt->bindParam(5, $service_id, PDO::PARAM_INT);
	$stmt->execute();

	$data = ["service_id" => $service_id];

	echo json_encode($data);
	die();
}


elseif (isset($_POST['ch']) && $_POST['ch'] == "remove") {


	$service_id = intval($_POST['service_id']);

	$qry = "DELETE FROM  services  WHERE service_id = ?";

	$stmt = $pdo->pdc->prepare($qry);
	$stmt->bindParam(1, $service_id, PDO::PARAM_INT);
	$stmt->execute();

	die("PASS");
	
} 

