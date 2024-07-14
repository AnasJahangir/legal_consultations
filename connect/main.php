<?php

require './config.php';
session_start();


$pdo = new mypdo();


///##################################################
/////      ADMIN SIGN IN
///####################################################
if (isset($_POST['ch']) && $_POST['ch'] == 'admin_signin') {

	$email = plain_validate($_POST['email']);
	$password = $_POST['password'];

	$pdo = new mypdo();

	$profu = $pdo->get_one("SELECT * FROM admins WHERE email = ?", $email);

	if ($profu != null) {
		$verify = password_verify($password, $profu['password']);
		if (!$verify) {
			die('Wrong login details');
		} else {
			$_SESSION['admin_id'] = $profu['admin_id'];
			$_SESSION['email'] = $profu['email'];
			die('PASS');
		}
	} else {
		die('Wrong login details');
	}

}




///##################################################
/////    USER  SIGN IN
///####################################################
if (isset($_POST['ch']) && $_POST['ch'] == 'signin') {

	$email = plain_validate($_POST['email']);
	$password = $_POST['password'];

	$pdo = new mypdo();

	$profu = $pdo->get_one("SELECT * FROM users WHERE email = ?", $email);

	if ($profu != null) {
		$verify = password_verify($password, $profu['password']);
		if (!$verify) {
			die('Wrong login details');
		} else {
			$_SESSION['user_id'] = $profu['user_id'];
			$_SESSION['email'] = $profu['email'];
			die('PASS');
		}
	} else {
		die('Wrong login details');
	}

}



///##################################################
/////     SIGN UP
///####################################################
if (isset($_POST['ch']) && $_POST['ch'] == "signup") {

	$first_name = plain_validate($_POST['first_name']);
	$last_name = plain_validate($_POST['last_name']);
	$email = plain_validate($_POST['email']);
	$phone = plain_validate($_POST['phone']);

	$password = trim($_POST['password']);
	$password2 = trim($_POST['password2']);

	$errors = [];


	// Validate first name
	if (strlen($first_name) < 3 || !preg_match('/^[a-zA-Z]{3,}$/', $first_name)) {
		$errors[] = ['first_name', 'First name must be at least 3 letters long and contain letters only'];
	}

	// Validate last name
	if (strlen($last_name) < 3 || !preg_match('/^[a-zA-Z]{3,}$/', $last_name)) {
		$errors[] = ['last_name', 'Last name must be at least 3 letters long and contain letters only'];
	}

	// Validate email
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$errors[] = ['email', 'Invalid email address'];
	} else {
		$allowed_domains = ['gmail.com', 'outlook.sa', 'outlook.com', 'hotmail.com'];
		$domain = substr(strrchr($email, "@"), 1);
		if (!in_array($domain, $allowed_domains)) {
			$errors[] = ['email', 'Email must end with @gmail.com, @outlook.sa, @outlook.com, or @hotmail.com'];
		} else {
			$user = $pdo->get_one("SELECT * FROM users WHERE email = ?", $email);
			if ($user != null) {
				$errors[] = ['email', 'Email address already exists'];
			}
		}
	}

	// Validate mobile number
	if (!preg_match('/^05\d{8}$/', $phone)) {
		$errors[] = ['phone', 'Mobile number must be 10 digits long, contain only numbers, and begin with 05'];
	}

	// Validate password
	if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&+=-_.#^()><])[A-Za-z\d@$!%*?&+=-_.#^()><]{8,}$/', $password)) {
		$errors[] = ['password', 'Password must be at least 8 characters long, contain an uppercase letter, a lowercase letter, a number, and a special character'];
	} elseif ($password != $password2) {
		$errors[] = ['password2', 'Passwords do not match'];
	}


	if (count($errors) > 0) {
		die(json_encode($errors));
	}


	$password = password_hash($password, PASSWORD_DEFAULT);
	$reg_date = date("Y-m-d");

	$pdo->pdc->beginTransaction();
	$stmt = $pdo->pdc->prepare("INSERT INTO users(first_name, last_name,  email, phone, password, created_at)VALUES(?, ?, ?, ?, ?, ?)");
	$stmt->bindParam(1, $first_name, PDO::PARAM_STR);
	$stmt->bindParam(2, $last_name, PDO::PARAM_STR);
	$stmt->bindParam(3, $email, PDO::PARAM_STR);
	$stmt->bindParam(4, $phone, PDO::PARAM_STR);
	$stmt->bindParam(5, $password, PDO::PARAM_STR);
	$stmt->bindParam(6, $reg_date, PDO::PARAM_STR);
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		$user_id = $pdo->pdc->lastInsertId();
		$pdo->pdc->commit();
		die("PASS");
	} else {
		die("DATABASE INSERT ERROR!");
	}
}





///##################################################
/////     RESET PASSWORD
///####################################################
if (isset($_POST['ch']) && $_POST['ch'] == "reset_password") {

	$password = trim($_POST['password']);
	$password2 = trim($_POST['password2']);
	$token = $_POST['token'];
	$errors = [];


	if ($token == "") {
		$errors[] = "There is an error. The link seems to be missing a token";
	}

	// Validate password
	if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&+=-_.#^()><])[A-Za-z\d@$!%*?&+=-_.#^()><]{8,}$/', $password)) {
		$errors[] = ['password', 'Password must be at least 8 characters long, contain an uppercase letter, a lowercase letter, a number, and a special character'];
	} elseif ($password != $password2) {
		$errors[] = ['password2', 'Passwords do not match'];
	}


	if (count($errors) > 0) {
		die(json_encode($errors));
	}

	require 'crypto.php';

	if ($token != "") {
		try {
			$raw_data = decrypt($token);
			$data = explode("___", $raw_data);
			//$timec."___".$email."___".$type."___".$timec
		} catch (Exception $sc) {
			die("Wrong token");
		}
		$timec = intval($data[0]);
		if ($data[0] != $data[3]) { // timestamp must be equal
			$errors[] = "There is an error with the token";
		}
		if ((time() - $timec) > 7200) {  // expires in 2 hours
			$errors[] = "This link has expired. Please request for a new link through the forgot password page";
		}
	}

	if (count($errors) > 0) {
		die(json_encode($errors));
	} else {

		$email = $data[1];
		$type = $data[2];

		// Get the user id from password recover table
		$user = $pdo->get_one("SELECT * FROM password_reset WHERE email = ? AND time_stamp = '$timec'", $email);
		if ($user !== null) {
			// User found
			$password = $hashed_password = password_hash($password, PASSWORD_DEFAULT);
			
			// UPDATE PASSWORD
			$stmt = $pdo->exec_query("UPDATE $type"."s SET password = '$password' WHERE email = ?", $email);

			//DELETE FROM THE RECOVER PASSWORD TABLE
			$stmt = $pdo->exec_query("DELETE FROM password_reset WHERE email = ?", $email);

			die("PASS");
		} else {
			die("Is like this link has expired");
		}

	}

}



///##################################################
/////     FORGOT PASSWORD
///####################################################
if (isset($_POST['ch']) && $_POST['ch'] == "forgot_password") {

	$email = $_POST['email'];
	$type = $_POST['type'];

	if ($type == "user") {
		$user = $pdo->get_one("SELECT * FROM users WHERE email = ?", $email);
		$reset_url = glob_site_url . '/reset-password.php';
	} else {
		$user = $pdo->get_one("SELECT * FROM admins WHERE email = ?", $email);
		$reset_url = glob_site_url . '/admin/reset-password.php';
	}



	if ($user == null) {
		die("PASS");
	}

	require 'crypto.php';
	require 'mailer.php';

	$mail = new mymailer();

	$timec = time();

	// INSERT INTO THE RECOVER PASSWORD TABLE
	$pdo->exec_query("INSERT INTO password_reset(email, time_stamp)VALUES(?, '$timec')", $email);

	$email = $user['email'];
	$name = $user['first_name'];

	$ref = encrypt($timec . "___" . $email . "___" . $type . "___" . $timec);
	$recover_link = $reset_url . "?pl=$ref&n=&ch=1o";

	$message = '<div style="text-align:center; max-width:600px; display:inline-block;"> <div style=" text-align:left; background-color:#FFF; padding:10px "> <h3 style=" font-size:20px; text-align:center; color:#9a7b32; margin-bottom: 30px;">Reset Password   </h3> <p style="margin-bottom: 15px; text-align:center; font-weight:normal; color:#333"><span style="color:#9a7b32;; font-weight:bolder; font-size:18px; margin-right:20px">Hello! ' . $name . '</span> Please click on the link below to recover your password</p> <p style="text-align:center"><a style="color:#ffffff; margin:20px; padding:10px; border-radius:5px; display:inline-block; text-decoration:none;  background-color: #000; color: #f8dd9f; border-color: #9a7b32;   font-size: 18px; font-weight: bold;" href="' . $recover_link . '"> Reset Password </a></p><p style="font-size: 12px">Or copy the link below and paste it in a browser address bar</p><p style="font-size:12px; white-space:pre-wrap"><a STYLE="color:#06F" href="' . $recover_link . '">' . $recover_link . '</a></p> <p style="font-style:italic; font-size: 12px; font-weight: normal; margin-bottom: 15px; color:#333">You received this mail because you were about recovery a password at  ' . glob_site_name . '. Kindly ignore if you were not the one.</p> </div> </div>';

	$mail->sendmail($name, $email, 'Reset Passsword', $message);

	die("PASS");



}