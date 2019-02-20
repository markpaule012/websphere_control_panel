<?php

	/**
		Parameter:
			Machine Name
			Machine User
			Machine Pass
	**/
	
	include '../Config/config.php';
	
	$username = str_replace("\\", "\\\\", $_POST["user"]);
	$password = str_replace("\\", "\\\\", $_POST["pass"]);
	
	$sqlString = "INSERT INTO machines (m_name, m_user, m_pass) VALUES ('" . $_POST["machine"] . "', '" . $username  . "', '" . $password ."')";
	
	$conn = new mysqli($db_server, $db_username, $db_password, $db_schema);
	$stmt = $conn->prepare($sqlString);
	$stmt->execute();
	$result = $stmt->get_result();
	
	header("Location: " . "../" .$root_url);


?>