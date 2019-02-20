<?php

	/**
		Parameters (Required):
			WAS ID
			
	**/
	
	include '../Config/config.php';
	
	
	//************************************
	//******* Remove from database *******
	//************************************
	

	$sqlString = "DELETE FROM profiles WHERE p_id=" . $_POST["profile"];
	
	$conn = new mysqli($db_server, $db_username, $db_password, $db_schema);
	$stmt = $conn->prepare($sqlString);
	$stmt->execute();
	$result = $stmt->get_result();
	
	//************************************
	//************* Redirect *************
	//************************************
	
	header("Location: " . "../" .$root_url);


?>