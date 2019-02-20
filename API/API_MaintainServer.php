<?php

	/**
		Parameters (Required):
			WAS ID
			WAS Name
			Was Machine Location
			WAS Server Name
			WAS Node
			WAS Console Port
			WAS HTTP Port
			WAS Share Location
			
	**/
	
	include '../Config/config.php';
	
	$pId = $_POST["pId"];
	$pName = trim($_POST["pName"]);
	$pDescription = trim($_POST["pDescription"]);
	$pMachineLocation = trim($_POST["pMachineLocation"]);
	$pNode = trim($_POST["pNode"]);
	$pConsolePort = trim($_POST["pConsolePort"]);
	$pHttpPort = trim($_POST["pHttpPort"]);
	$pShareLocation = trim($_POST["pShareLocation"]);
	$pServerName = trim($_POST["pServer"]);
	$pNotes = nl2br(($_POST["pNotes"]));
	
	//************************************
	//******* Insert to Database *********
	//************************************
	
	// Translate Backslashes
	$pMachineLocation = str_replace("\\", "\\\\", $pMachineLocation);
	$pShareLocation = str_replace("\\", "\\\\", $pShareLocation);
	
	
	$sqlString = "UPDATE profiles set p_name = '" . $pName . "', p_description = '" . $pDescription . "', p_machineLocation = '" . $pMachineLocation .  "', p_node = '" . $pNode . "', p_consolePort = '" . $pConsolePort . "', p_httpPort = '" . $pHttpPort . "', p_shareLocation = '" . $pShareLocation .  "', p_serverName = '" . $pServerName . "', p_notes = '" . $pNotes . "'  WHERE p_id = '" . $pId . "'";
		echo $sqlString;
	$conn = new mysqli($db_server, $db_username, $db_password, $db_schema);
	$stmt = $conn->prepare($sqlString);
	$stmt->execute();
	$result = $stmt->get_result();
	
	//************************************
	//************* Redirect *************
	//************************************

	header("Location: " . "../" .$root_url);


?>