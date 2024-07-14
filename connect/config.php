<?php

date_default_timezone_set('America/New_York');

// Database settings
define('dbhost', 'localhost');
define('dbuser', 'root');
define('dbpass', '');
define('dbname', 'legal_consultations');


// Site settings
define('glob_site_name', 'Legal Consultant');   // Site Name
define('glob_site_url', "http://localhost/legal_consultations"); // full path of website
define('glob_site_url_fd', 'legal-consultations.com');
define('glob_site_phone', '+966 11 123 4567');
define('glob_site_address', '123 King Fahd Road, Riyadh, Saudi Arabia');




// SMTP SETTINGS
define('smtp_secure', 'ssl');  //'tls';
define('smtp_port', '465');   //587;
define('smtp_host', 'smtp.gmail.com');
define('smtp_username', 'jamesmiguel999@gmail.com');
define('smtp_sender_name', 'Health');
define('smtp_password', 'vpkvfmarmxikgayo');





// SubServices Price Percentages
$glob_sub_services = [
	"1" => [
		"name" => "Attend a session",
		"per" => 1.2,
		"type" => "sub_service",
		"children" => null,
	],
	"2" => [
		"name" => "Write a statement of claim",
		"per" => 1.3,
		"type" => "sub_service",
		"children" => null,
	],
	"3" => [
		"name" => "Write legal memos",
		"per" => 1.22,
		"type" => "sub_service",
		"children" => null,
	],
	"4" => [
		"name" => "Study the case",
		"per" => 1.43,
		"type" => "sub_service",
		"children" => null,
	],
	"5" => [
		"name" => "Provide legal consultations",
		"per" => 1.32,
		"type" => "sub_service",
		"children" => null,
	],
	"6" => [
		"name" => "Take over the case completely.",
		"per" => 1.72,
		"type" => "sub_service",
		"children" => null,
	],
	"7" => [
		"name" => "In Person",
		"per" => 1.0,
		"type" => "consultation",
		"children" => null,
	],
	"8" => [
		"name" => "Remote",
		"per" => 1.2,
		"type" => "consultation",
		"children" => "remote",
	],
	"9" => [
		"name" => "Via Voice Call",
		"per" => 1.2,
		"type" => "remote",
		"children" => null,
	],
	"10" => [
		"name" => "Via WhatsApp ",
		"per" => 1.3,
		"type" => "remote",
		"children" => null,
	],


];







function plain_validate($value)
{
	// return htmlspecialchars($value, ENT_COMPAT);
	return strip_tags($value);
}


function getError($key, $errors)
{

	$thisError = array();
	foreach ($errors as $error) {
		if (isset($error[$key])) {
			$thisError[] = $error[$key];
		}
	}
	;
	return implode('<br>', $thisError);
}



function validPassword($password)
{
	if (preg_match("/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{8,})/", $password)) {
		return true;
	}
	if (preg_match("/((?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{6,}))|((?=.*[a-z])(?=.*[A-Z])(?=.*[^A-Za-z0-9])(?=.{8,}))/", $password)) {
		return true;
	}
	return false;
}




// Database class

class mypdo
{
	public $pdc = null;
	public function __construct()
	{
		$host = dbhost;
		$db = dbname;
		$user = dbuser;
		$pass = dbpass;
		$charset = 'utf8mb4';
		$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
		$opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false);
		$this->pdc = new PDO($dsn, $user, $pass, $opt);
	}



	public function exec_query($qry, $val = '', $val2 = '')
	{

		$stmt = $this->pdc->prepare($qry);
		if ($val != '') {
			$stmt->bindParam(1, $val, PDO::PARAM_STR);
		}
		if ($val2 != '') {
			$stmt->bindParam(2, $val2, PDO::PARAM_STR);
		}
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			return true;
		}
		return false;
	}


	public function get_one($qry, $val = '')
	{

		$stmt = $this->pdc->prepare($qry);
		if ($val != '') {
			$stmt->bindParam(1, $val, PDO::PARAM_STR);
		}
		$stmt->execute();
		if ($stmt->rowCount() > 0)
			return $stmt->fetch();
		else
			return null;
	}

	public function get_all($qry, $val = '')
	{

		$stmt = $this->pdc->prepare($qry);
		if ($val != '') {
			$stmt->bindParam(1, $val, PDO::PARAM_STR);
		}
		$stmt->execute();
		return $stmt->fetchAll();
	}


	public function get_all_var($qry, $values)
	{

		$stmt = $this->pdc->prepare($qry);

		for ($i = 0; $i < count($values); $i++) {
			$j = $i + 1;
			$stmt->bindParam($j, $values[$i], PDO::PARAM_STR);
		}
		$stmt->execute();
		return $stmt->fetchAll();

	}


	public function get_all_num($qry)
	{

		$stmt = $this->pdc->prepare($qry);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_NUM);

	}


}

