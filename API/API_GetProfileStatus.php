<?php

	/**
		Parameter:
			Profile Id 
		Returns:
			1 - active
			0 - inactive
	**/
	
	include '../Config/config.php';
	
	$sqlString = "SELECT A.*, B.m_name FROM profiles A LEFT JOIN machines b ON a.p_machine = b.m_id where a.p_id = " . $_GET["profile"];
	
	$conn = new mysqli($db_server, $db_username, $db_password, $db_schema);
	$stmt = $conn->prepare($sqlString);
	$stmt->execute();
	$result = $stmt->get_result();

	
		
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$host = $row["m_name"] . ':' . $row["p_consolePort"] . '/ibm/console';
			if($socket =@ fsockopen($host, 80, $errno, $errstr, 30)) {
				echo '1';
				//echo $row["m_name"] . ':' . $row["p_consolePort"] . '/ibm/console';
				fclose($socket);
			}else{
				echo '0';
			} 			
		}
	}

	
	
		

?>