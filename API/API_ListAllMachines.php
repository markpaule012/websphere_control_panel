<?php

	/**
		Parameter:
			Machine 
		Returns:
			- Profiles with machine 
			- All profiles if machine id is blank	
	**/
	
	include '../Config/config.php';
	
	header("Content-Type: application/json; charset=UTF-8");	
	
	$conn = new mysqli($db_server, $db_username, $db_password, $db_schema);
	$stmt = $conn->prepare("SELECT * FROM machines");
	$stmt->execute();
	$result = $stmt->get_result();
	$outp = $result->fetch_all(MYSQLI_ASSOC);

	echo json_encode($outp);

?>