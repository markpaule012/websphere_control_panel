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
		
	if (empty($_GET["machine"])) {
		$sqlString = "SELECT * FROM profiles";
	}else{
		$sqlString = "SELECT * FROM profiles where p_machine = " . $_GET["machine"];
	}
	
	$conn = new mysqli($db_server, $db_username, $db_password, $db_schema);
	$stmt = $conn->prepare($sqlString);
	$stmt->execute();
	$result = $stmt->get_result();
	$outp = $result->fetch_all(MYSQLI_ASSOC);

	echo json_encode($outp);

?>