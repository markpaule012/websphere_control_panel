<?php

	/**
		Parameter:
			Profile Id 
			Command
	**/
	
	include '../Config/config.php';
	
	$sqlString = "SELECT A.*, B.m_name, B.m_user, B.m_pass FROM profiles A LEFT JOIN machines b ON a.p_machine = b.m_id where a.p_id = " . $_GET["profile"];
	$command = trim($_GET["command"]);
	
	$conn = new mysqli($db_server, $db_username, $db_password, $db_schema);
	$stmt = $conn->prepare($sqlString);
	$stmt->execute();
	$result = $stmt->get_result();
	

	
		
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$output;
			//echo 'WMIC /node:' . $row["m_name"] . ' /user:' . $row["m_user"] . ' /password:' . $row["m_pass"] . ' process call create "' . $row["p_machineLocation"] . '\bin\startServer.bat ' . $row["p_serverName"] . '"';
			exec('WMIC /node:' . $row["m_name"] . ' /user:' . $row["m_user"] . ' /password:' . $row["m_pass"] . ' process call create "' . $row["p_machineLocation"] . '\bin\\' . $command .  '"', $output);
			if (strpos($output[6], "0") !== false){
				echo "Command executed! Please wait for 10-15 seconds to transmit the start command.";
			}else{
				if(!empty($outp))
					echo "Error: " . json_encode($outp);
				else
					echo "System Error!";
			}
				
			//echo 'WMIC /node:' . $row["m_name"] . ' /user:' . $row["m_user"] . ' /password:' . $row["m_pass"] . ' process call create "' . $row["p_machineLocation"] . '\bin\stopServer.bat ' . $row["p_serverName"] . '"';
		}
	}

?>