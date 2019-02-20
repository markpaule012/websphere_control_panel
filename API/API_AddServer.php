<?php

	/**
		Parameters (Required):
			WAS Machine
			WAS Name
			Was Machine Location
			WAS Server Name
			
		Optional:
			WAS Node
			WAS Console Port
			WAS HTTP Port
			WAS Share Location
			
	**/
	
	include '../Config/config.php';
	
	$pMachine = $_POST["pMachine"];
	$pName = trim($_POST["pName"]);
	$pMachineLocation = trim($_POST["pMachineLocation"]);
	$pServerName = trim($_POST["pServer"]);
	$pDescription = trim($_POST["pDescription"]);
	
	//Optional Parameters
	$pNotes = ($_POST["pNotes"]);
	
	//$pMachineLocation = str_replace("\\", "\\\\", $pMachineLocation);
	
	//************************************
	//******* Get Machine Name **********
	//************************************
	$sqlString = "SELECT * FROM machines WHERE m_id = " . $pMachine;
	
	$conn = new mysqli($db_server, $db_username, $db_password, $db_schema);
	$stmt = $conn->prepare($sqlString);
	$stmt->execute();
	$result = $stmt->get_result();

	
		
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			
			$mName = trim($row["m_name"]);
			$mUser = $row["m_user"];
			$mPass = $row["m_pass"];
			
			//echo 'WMIC /node:' . $row["m_name"] . ' /user:' . $row["m_user"] . ' /password:' . $row["m_pass"] . ' process call create "' . $row["p_machineLocation"] . '\bin\stopServer.bat ' . $row["p_serverName"] . '"';
		}
	}
	
	
	
	//************************************
	//******* Share the location *********
	//************************************
	
	//echo 'WMIC /node:' . $mName . ' /user:' . $mUser . ' /password:' . $mPass . ' share call create "", "' . $pName . '","", "' . $pName . '","" , "' . $pMachineLocation . '", 0';
	
	exec('WMIC /node:' . $mName . ' /user:' . $mUser . ' /password:' . $mPass . ' share call create "", "' . $pName . '","", "' . $pName . '","" , "' . $pMachineLocation . '", 0');
	

	//************************************
	//******* Read profile properties ****
	//************************************
	
	$pShareLocation = '\\\\' . $mName . '\\' . $pName;
	//echo $pShareLocation; 
	
	$url =  $pShareLocation . "\\logs\AboutThisProfile.txt" ;
	//echo file_get_contents($url);
	
	$pNode = "";
	$pConsolePort = "";
	$pHTTPPort = "";
	
	if ($file = fopen($url , "r")) {
		while(!feof($file)) {
			$line = fgets($file);
			
			//Node
			if (strpos($line, 'Node name') !== false) {
				$nodeTemp = explode(":", $line);
				$pNode =  trim($nodeTemp[1]);
			}
			
			//Console Port
			if (strpos($line, 'Administrative console port') !== false) {
				$cPortTemp = explode(":", $line);
				$pConsolePort =  trim($cPortTemp[1]);
			}
			
			//HTTP Port
			if (strpos($line, 'HTTP transport port') !== false) {
				$hPortTemp = explode(":", $line);
				$pHTTPPort =  trim($hPortTemp[1]);
			}
		}
		fclose($file);
	}
	
	//************************************
	//******* Insert to Database *********
	//************************************
	
	// Translate Backslashes
	$pMachineLocation = str_replace("\\", "\\\\", $pMachineLocation);
	$pShareLocation = str_replace("\\", "\\\\", $pShareLocation);
	
	$sqlString = "INSERT INTO profiles (p_name, p_description, p_machine, p_machineLocation, p_node, p_consolePort, p_httpPort, p_shareLocation, p_serverName, p_notes) VALUES ('" . $pName . "', '" . $pDescription  . "','" . $pMachine . "','" . $pMachineLocation ."','" . $pNode . "','" . $pConsolePort . "','" . $pHTTPPort . "','" . $pShareLocation . "','" . $pServerName . "', '" . $pNotes . "')";
	
	$conn = new mysqli($db_server, $db_username, $db_password, $db_schema);
	$stmt = $conn->prepare($sqlString);
	$stmt->execute();
	$result = $stmt->get_result();
	
	//************************************
	//************* Redirect *************
	//************************************
	
	header("Location: " . "../" .$root_url . "?machine=" . $pMachine);


?>