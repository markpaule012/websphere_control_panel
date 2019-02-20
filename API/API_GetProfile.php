<?php

	/**
		Parameter:
			Profile Id 
		Returns:
			- Profile details
	**/
	
	include '../Config/config.php';
	
	header("Content-Type: application/json; charset=UTF-8");	
	
	$conn = new mysqli($db_server, $db_username, $db_password, $db_schema);
	$stmt = $conn->prepare("SELECT * FROM profiles where p_id = " . $_GET['id']);
	$stmt->execute();
	$result = $stmt->get_result();
	$outp = $result->fetch_all(MYSQLI_ASSOC);

	echo json_encode($outp);

?>