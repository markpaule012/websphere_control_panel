<?php
	
		/*
			Paramter:
				url - Path to file.
			
			Ex:
				?url=\\manvswbfeq17\\AppSrv01_MP6_EQLITE\\logs\\server1\\SystemOut.log
		
		*/
		
		$url =  $_GET["url"];
		echo file_get_contents($url);

?>