<!doctype html>
<html lang="en">
<?php
	include 'Config/config.php';
	
	if(!empty($_GET["machine"]))
		$machine = $_GET["machine"];
	else
		$machine = 1;

?>
<head>

	<!--
		Copyright and all other intellectual property rights in this software, in any form, is vested in Misys International Banking Systems 
		Ltd ("Misys") or a related company.         

		** Mark Allan Paule
		** Developer
	-->
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="assets/img/favicon.ico">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    WebSphere Control Panel
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="css/style.css" />

  <!-- CSS Files -->
  <link href="assets/css/material-dashboard.css?v=2.1.0" rel="stylesheet" />
  
   <script src="assets/js/core/jquery.min.js" type="text/javascript"></script>
  <script src="assets/js/core/popper.min.js" type="text/javascript"></script>
  <script src="assets/js/core/bootstrap-material-design.min.js" type="text/javascript"></script>
  <script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <!-- Chartist JS -->
  <script src="assets/js/plugins/chartist.min.js"></script>
  <!--  Notifications Plugin    -->
  <script src="assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="assets/js/material-dashboard.min.js?v=2.1.0" type="text/javascript"></script>
</head>

<body class="">
  <div class="wrapper ">
    <div class="sidebar" data-color="purple" data-background-color="white" >
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
	  -->
      <div class="logo">
        <a href="#" class="simple-text logo-normal">
          Machines
        </a>
      </div>
      <div class="sidebar-wrapper">
        <ul class="nav">
			<?php
			
				//Modal
				$modalList = "";
		
				$sqlString = "SELECT * FROM machines";		
				$conn = new mysqli($db_server, $db_username, $db_password, $db_schema);
				$stmt = $conn->prepare($sqlString);
				$stmt->execute();
				$result = $stmt->get_result();

				
				if (mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_assoc($result)) {
						if ($row["m_id"] == $machine)
							echo '<li class="nav-item active">';
						else
							echo '<li class="nav-item">';
						echo '    <a class="nav-link" href="?machine=' . $row["m_id"] .  '" >';
						echo '    <i class="material-icons">desktop_windows</i>';
						echo '    <p>' . $row["m_name"] . '</p>';
						echo '  </a>';
						echo '</li>';
						
						if ($row["m_id"] == $machine){
							$machine_name = $row["m_name"];
							$machine_id = $row["m_id"];
							$machine_user = $row["m_user"];
							$machine_pass = $row["m_pass"];
						}
						
						$modalList = $modalList . "<option value='" . $row["m_id"]. "'>" . $row["m_name"] . "</option>";
					}
				} 
			?>
          <li class="nav-item  ">
			<a class="nav-link" data-toggle="modal" data-target="#addMachine" href="#">
              <i class="material-icons">add_to_queue</i>
              <p>Add New Machine</p>
			</a>
          </li>
          <!-- your sidebar here -->
        </ul>
      </div>
    </div>
	<!--   MODALS -->
	<div id="addMachine" class="modal fade" role="dialog">
		<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
				  <div class="modal-header">
					<h4 class="modal-title">Add New Machine</h4>
				  </div>
				  <div class="modal-body">
					<form action="API/API_AddMachine.php" method="POST">
					  <div class="form-group">
						<input type="text" class="form-control" id="machine" aria-describedby="machineHelp" placeholder="Enter machine name" name="machine" required>
						<small id="machineHelp" class="form-text text-muted">Enter machine or vm name. For ex: manvswbfeq17</small>
					  </div>
					  <div class="form-group">
						<input type="text" class="form-control" id="username" placeholder="Username" name="user" required>
					  </div>
					  <div class="form-group">
						<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="pass" required>
					  </div>
					  <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
					  <button type="submit" class="btn btn-primary pull-right">Submit</button>
					</form>
				  </div>
				 
				</div>

		</div>
	</div>
	<div id="addServer" class="modal fade" role="dialog">
		<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
				  <div class="modal-header">
					<h4 class="modal-title">Link New WebSphere Server</h4>
				  </div>
				  <div class="modal-body">
					<form action="API/API_AddServer.php" method="POST">
					  <div class="form-group">
						<select class="form-control" id="pMachine" name="pMachine" aria-describedby="pMachineHelp" required>
							<?php echo $modalList; ?>
						</select>
						<small id="pMachineHelp" class="form-text text-muted">Select Machine</small>
					  </div>
					  <div class="form-group">
						<input type="text" class="form-control" id="pName" placeholder="Profile Name" name="pName" aria-describedby="pNameHelp" required>
						<small id="pNameHelp" class="form-text text-muted">Enter profile name. For ex: AppSrv01_MP1_EQ</small>
					  </div>
					  <div class="form-group">
						<input type="text" class="form-control" id="pDescription" placeholder="Profile Description" name="pDescription" aria-describedby="pDescriptionHelp">
						<small id="pDescriptionHelp" class="form-text text-muted">(Optional) Enter profile description. For ex: Project Development Unit</small>
					  </div>
					  <div class="form-group">
						<input type="text" class="form-control" id="pMachineLocation" placeholder="Profile Location" name="pMachineLocation" aria-describedby="pLocationHelp" required>
						<small id="pLocationHelp" class="form-text text-muted">Enter location on machine. For ex: C:\WAS\profiles\AppSrv01_MP1_EQ</small>
					  </div>
					  <div class="form-group">
						<input type="text" class="form-control" id="pServer" placeholder="Server Name" name="pServer" aria-describedby="pServerHelp" required>
						<small id="pServerHelp" class="form-text text-muted">Enter server name. For ex: server1</small>
					  </div>
					  <div class="form-group">
                          <label>Additional Notes</label>
                          <div class="form-group bmd-form-group">
                            <label class="bmd-label-floating">(Optional) Add Services/EQ Desktop Link Here.</label>
                            <textarea class="form-control" rows="5" name="pNotes"></textarea>
                          </div>
                       </div>
					  <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
					  <button type="submit" class="btn btn-primary pull-right">Submit</button>
					</form>
				  </div>
				 
				</div>

		</div>
	</div>
	<!-- Servers -->
	<!--div id="maintainServer1" class="modal fade" role="dialog">
		<div class="modal-dialog">
				<div class="modal-content">
				  <div class="modal-header">
					<h4 class="modal-title">Maintain WebSphere Server</h4>
				  </div>
				  <div class="modal-body">
					<form action="API/API_MaintainServer.php" method="POST">
					  <div class="form-group">
						<input type="text" class="form-control" id="pName" placeholder="Profile Name" name="pName" aria-describedby="pNameHelp" required>
						<small id="pNameHelp" class="form-text text-muted">Enter profile name. For ex: AppSrv01_MP1_EQ</small>
					  </div>
					  <div class="form-group">
						<input type="text" class="form-control" id="pDescription" placeholder="Profile Description" name="pDescription" aria-describedby="pDescriptionHelp">
						<small id="pDescriptionHelp" class="form-text text-muted">(Optional) Enter profile description. For ex: Project Development Unit</small>
					  </div>
					  <div class="form-group">
						<input type="text" class="form-control" id="pMachineLocation" placeholder="Profile Location" name="pMachineLocation" aria-describedby="pLocationHelp" required>
						<small id="pLocationHelp" class="form-text text-muted">Enter location on machine. For ex: C:\WAS\profiles\AppSrv01_MP1_EQ</small>
					  </div>
					  <div class="form-group">
						<input type="text" class="form-control" id="pNode" placeholder="Node" name="pNode" aria-describedby="pNodeHelp" required>
						<small id="pNodeHelp" class="form-text text-muted">Node Name</small>
					  </div>
					  <div class="form-group">
						<input type="text" class="form-control" id="pConsolePort" placeholder="Console Port" name="pConsolePort" aria-describedby="pConsolePortHelp" required>
						<small id="pConsolePortHelp" class="form-text text-muted">Console Port</small>
					  </div>
					  <div class="form-group">
						<input type="text" class="form-control" id="pHttpPort" placeholder="HTTP Port" name="pHttpPort" aria-describedby="pHttpPortHelp" required>
						<small id="pHttpPortHelp" class="form-text text-muted">HTTP Port</small>
					  </div>
					  <div class="form-group">
						<input type="text" class="form-control" id="pShareLocation" placeholder="Shared Location" name="pShareLocation" aria-describedby="pShareLocationHelp" required>
						<small id="pShareLocationHelp" class="form-text text-muted">HTTP Port</small>
					  </div>
					  <div class="form-group">
						<input type="text" class="form-control" id="pServer" placeholder="Server Name" name="pServer" aria-describedby="pServerHelp" required>
						<small id="pServerHelp" class="form-text text-muted">Enter server name. For ex: server1</small>
					  </div>
					  <div class="form-group">
                          <label>Additional Notes</label>
                          <div class="form-group bmd-form-group">
                            <label class="bmd-label-floating">(Optional) Add Services/EQ Desktop Link Here.</label>
                            <textarea class="form-control" rows="5" name="pNotes"></textarea>
                          </div>
                       </div>
					  <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
					  <button type="submit" class="btn btn-primary pull-right">Submit</button>
					</form>
				  </div>
				</div>
		</div>
	</div-->
	<?php
		
	?>
	<!-- Main Panel -->
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="#pablo">WebSphere profiles on <?php echo $machine_name; ?></a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" data-toggle="modal" data-target="#addServer" href="#">
				  <i class="material-icons">control_point</i>
				  <h>Add Server</p>
				</a>
				</li>

            </ul>
          </div>
        </div>
      </nav>
      <!-- End Navbar -->
        <div class="content">
        <div class="container-fluid">
		<?php
		
				$sqlString = "SELECT * FROM profiles where p_machine = " . $machine_id;		
				$conn = new mysqli($db_server, $db_username, $db_password, $db_schema);
				$stmt = $conn->prepare($sqlString);
				$stmt->execute();
				$result = $stmt->get_result();

				
				if (mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_assoc($result)) {
						
						$profileId = "profile" . $row["p_id"];
						$logsId = "logs" . $row["p_id"];
						$cardId = "card" . $row["p_id"];
						
						echo '<div class="card" id="' . $cardId . '">';
						echo '<div class="card-header card-header-primary">';
						echo '<div class="card-category pull-left">';
						echo '<h4 class="card-title">' . $row["p_name"] .  '</h4>';
						echo '<p class="card-category">' . $row["p_description"] .'</p>';
						echo '</div>';
						echo '<div class="card-category pull-right">';
						echo '<p class="card-category btn" id="status' . $row["p_id"] .  '">Offline</p>';
						echo '<i class="material-icons" data-toggle="collapse" data-target="#' . $profileId .  '" style="cursor:pointer; font-size:36px;" onclick="getFile' . $row["p_id"] . '()">arrow_drop_down</i>';
						echo '</div>';
						echo '</div>';
						echo '<div id ="' . $profileId . '" class="card-body collapse">';
						echo '<div class="row">';
						echo '<div class="col-sm">';
						echo 'Machine: ' . $machine_name;
						echo '</div>';
						echo '<div class="col-sm">';
						echo 'Server: ' . $row["p_serverName"];
						echo '</div>';
						echo '</div>';
						echo '<div class="row">';
						echo '<div class="col-sm">';
						echo 'Profile name: ' . $row["p_name"];
						echo '</div>';
						echo '<div class="col-sm">';
						echo 'Node: ' . $row["p_node"];
						echo '</div>';
						echo '</div>';
						echo '<div class="row">';
						echo '<div class="col-sm">';
						echo 'HTTP Port: ' . $row["p_httpPort"];
						echo '</div>';
						echo '<div class="col-sm">';
						echo 'Console Port: ' . $row["p_consolePort"];
						echo '</div>';
						echo '</div>';
						echo '<div class="row">';
						echo '<div class="col-sm">';
						echo 'Profile Location: ' . $row["p_machineLocation"];
						echo '</div>';
						echo '</div>';
						echo '<div class="row">';
						echo '<div class="col-sm">';
						echo 'Shared Location: ' . $row["p_shareLocation"];
						echo '</div>';
						echo '</div>';
						if (!empty($row["p_notes"]) || empty($row["p_consolePort"])){
							echo '<div class="row">';
							echo '<div class="col-sm">';
							echo '<br/> <h6>Server Notes</h6> ' . $row["p_notes"];
							if (empty($row["p_consolePort"])){
								echo '<p class="text-danger">[System Error]  Server was not added properly. Kindly share the location below to the network with Read access for "Everyone" then edit the server with the correct ports and location. <br />';
								echo 'Location: ' . $row["p_shareLocation"] . '</p>';
							}
							echo '</div>';
							echo '</div>';
						}
						echo '<div class="form-group">';
						echo '<h6>Logs:</h6>';
						echo '<textarea class="form-control rounded-0" id="' . $logsId .  '" rows="30" style="white-space: nowrap;font-family: Courier New,Courier,Lucida Sans Typewriter,Lucida Typewriter,monospace; font-size: 11px;" spellcheck="false"></textarea>';
						echo '</div>';
						echo '<div class="row">';
						echo '<div class="col-sm">';
						echo '<button type="submit" class="btn btn-danger pull-right" id="stop' . $row["p_id"] .  '" onclick="stopServer' . $row["p_id"] .  '()">Stop Server<div class="ripple-container"></div></button>';
						echo '<button type="submit" class="btn btn-primary pull-right" id="start' . $row["p_id"] .  '" onclick="startServer' . $row["p_id"] .  '()">Start Server<div class="ripple-container"></div></button>';
						echo '<a class="nav-link active show pull-right" href="#com' . $row["p_id"] . '"   data-toggle="collapse" style="padding: 12px 10px;" role="button" aria-expanded="false" aria-controls="com' . $row["p_id"] . '"><i class="material-icons">web</i></a>';
						echo '  <div class="collapse pull-right col-md-6" id="com' . $row["p_id"] . '">
								<input type="text" class="form-control" id="command' . $row["p_id"] . '" name="command" placeholder="Enter command here (bin): startServer.bat server1">
							  </div>';
						echo '<form action="API/API_DeleteServer.php" method="POST"style="float:left; display: inline;"><input type="hidden" id="delete' . $row["p_id"] .'" name="profile" value="' . $row["p_id"] . '">';
						echo '<button type="submit"  title="" class="btn btn-danger btn-link btn-sm" ><i class="material-icons">close</i>Remove Server</button></form>';
						echo '<div  style="float:left; display: inline;"><input type="hidden" id="edit' . $row["p_id"] .'" name="edit" value="' . $row["p_id"] . '">';
						echo '<button data-toggle="modal" data-target="#maintainServer' . $profileId . '" href="#" title="" class="btn btn-warning btn-link btn-sm" ><i class="material-icons">edit</i> Edit</button></div>';
						echo '</div>';
						echo '</div>';
						echo '</div>';
						echo '</div>';
						
					
						echo '<script>';
						echo '$(' . $logsId . ').ready(function() {';
						echo 'getFile' . $row["p_id"] .  '();';
						echo 'checkOnline' . $row["p_id"] .  '();';
						echo '});';
						echo '';
						echo 'function getFile' . $row["p_id"] .  '(){';
						echo '$.ajax({';
						echo 'url: "API/API_GetFileContents.php",';
						echo 'data: {';
						echo 'url: "' . str_replace('\\', '\\\\', $row["p_shareLocation"]) . '\\\\logs\\\\' . $row["p_serverName"] . '\\\\SystemOut.log"';
						echo '},';
						echo 'type: "GET",';
						echo 'dataType: "html",';
						echo 'success: function (data) {';
						echo 'var $textarea = $(' . $logsId . ');';
						echo '$($textarea).val(data);';
						echo '$textarea.scrollTop($textarea[0].scrollHeight);';
						echo 'var scrollTimeOut;';
						echo 'if($textarea.is(":focus") || $("#' . $cardId .'").height() < 60){';
						echo 'scrollTimeOut = 10000;';
						echo '} else{';
						echo 'scrollTimeOut = ' . $perf_refresh . ';';
						echo '}';
						echo 'setTimeout(function(){getFile' . $row["p_id"] .  '();}, scrollTimeOut);';
						echo '}';
						echo '});';
						echo '}';
						echo '';
						echo 'function checkOnline' . $row["p_id"] .  '(){';
						echo '$.ajax({';
						echo 'url: "API/API_GetProfileStatus.php",';
						echo 'data: {';
						echo 'profile: ' . $row["p_id"] .  '';
						echo '},';
						echo 'type: "GET",';
						echo 'dataType: "html",';
						echo 'success: function (data) {';
						echo 'if (data.includes("1")) {';
						echo '$("#status' . $row["p_id"] .  '").addClass("btn-success");';
						echo '$("#status' . $row["p_id"] .  '").html("Online");';
						echo '$("#start' . $row["p_id"] .  '").prop("disabled", true);';
						echo '} else if (data.includes("0")){';
						echo '$("#status' . $row["p_id"] .  '").removeClass("btn-success");';
						echo '$("#status' . $row["p_id"] .  '").html("Offline");';
						echo '$("#start' . $row["p_id"] .  '").prop("disabled", false);';
						echo '}';
						echo '';
						echo 'setTimeout(function(){checkOnline' . $row["p_id"] .  '();}, 1000);';
						echo '';
						echo '}';
						echo '});';
						echo '}';
						echo '';
						echo 'function stopServer' . $row["p_id"] .  '(){';
						echo '$.ajax({';
						echo 'url: "API/API_ServerStop.php",';
						echo 'data: {';
						echo 'profile: ' . $row["p_id"] .  '';
						echo '},';
						echo 'type: "GET",';
						echo 'dataType: "html",';
						echo 'success: function (data) {';
						echo 'alert(data);';
						echo '}';
						echo '});';
						echo '}';
						echo '';
						echo 'function startServer' . $row["p_id"] .  '(){';
						echo '$.ajax({';
						echo 'url: "API/API_ServerStart.php",';
						echo 'data: {';
						echo 'profile: ' . $row["p_id"] .  '';
						echo '},';
						echo 'type: "GET",';
						echo 'dataType: "html",';
						echo 'success: function (data) {';
						echo 'alert(data);';
						echo '}';
						echo '});';
						echo '}';
						echo '$("#command' . $row["p_id"] . '").bind("enterKey",function(e){';
						echo '$.ajax({';
						echo 'url: "API/API_ServerRun.php",';
						echo 'data: {';
						echo 'profile: ' . $row["p_id"] . ",";
						echo 'command: $("#command' . $row["p_id"] . '").val()';
						echo '},';
						echo 'type: "GET",';
						echo 'dataType: "html",';
						echo 'success: function (data) {';
						echo 'alert(data);';
						echo '}';
						echo '});';
						echo '$("#command' . $row["p_id"] . '").val("")';
						echo '});';
						echo '$("#command' . $row["p_id"] . '").keyup(function(e){';
						echo 'if(e.keyCode == 13)';
						echo '{';
						echo '$(this).trigger("enterKey");';
						echo '}';
						echo '});';
						echo '</script>';
						
						//************** EDIT Modals
						echo '<div id="maintainServer' . $profileId . '" class="modal fade" role="dialog">';
						echo '<div class="modal-dialog">';
						echo '<div class="modal-content">';
						echo '<div class="modal-header">';
						echo '<h4 class="modal-title">Maintain WebSphere Server</h4>';
						echo '</div>';
						echo '<div class="modal-body">';
						echo '<form action="API/API_MaintainServer.php" method="POST">';
						echo '<input type="hidden" class="form-control" id="pId" placeholder="Profile ID" name="pId" aria-describedby="pNameHelp"  value="' . $row["p_id"] . '" required>';
						echo '<div class="form-group">';
						echo '<input type="text" class="form-control" id="pName" placeholder="Profile Name" name="pName" aria-describedby="pNameHelp"  value="' . $row["p_name"] . '" required>';
						echo '<small id="pNameHelp" class="form-text text-muted">Enter profile name. For ex: AppSrv01_MP1_EQ</small>';
						echo '</div>';
						echo '<div class="form-group">';
						echo '<input type="text" class="form-control" id="pDescription" placeholder="Profile Description" name="pDescription" aria-describedby="pDescriptionHelp" value="' . $row["p_description"] . '" >';
						echo '<small id="pDescriptionHelp" class="form-text text-muted">(Optional) Enter profile description. For ex: Project Development Unit</small>';
						echo '</div>';
						echo '<div class="form-group">';
						echo '<input type="text" class="form-control" id="pMachineLocation" placeholder="Profile Location" name="pMachineLocation" aria-describedby="pLocationHelp" value="' . $row["p_machineLocation"] . '" required>';
						echo '<small id="pLocationHelp" class="form-text text-muted">Enter location on machine. For ex: C:\WAS\profiles\AppSrv01_MP1_EQ</small>';
						echo '</div>';
						echo '<div class="form-group">';
						echo '<input type="text" class="form-control" id="pNode" placeholder="Node" name="pNode" aria-describedby="pNodeHelp" value="' . $row["p_node"] . '" required>';
						echo '<small id="pNodeHelp" class="form-text text-muted">Node Name</small>';
						echo '</div>';
						echo '<div class="form-group">';
						echo '<input type="text" class="form-control" id="pConsolePort" placeholder="Console Port" name="pConsolePort" aria-describedby="pConsolePortHelp" value="' . $row["p_consolePort"] . '" required>';
						echo '<small id="pConsolePortHelp" class="form-text text-muted">Console Port</small>';
						echo '</div>';
						echo '<div class="form-group">';
						echo '<input type="text" class="form-control" id="pHttpPort" placeholder="HTTP Port" name="pHttpPort" aria-describedby="pHttpPortHelp" value="' . $row["p_httpPort"] . '" required>';
						echo '<small id="pHttpPortHelp" class="form-text text-muted">HTTP Port</small>';
						echo '</div>';
						echo '<div class="form-group">';
						echo '<input type="text" class="form-control" id="pShareLocation" placeholder="Shared Location" name="pShareLocation" aria-describedby="pShareLocationHelp" value="' . $row["p_shareLocation"] . '" required>';
						echo '<small id="pShareLocationHelp" class="form-text text-muted">Shared Location</small>';
						echo '</div>';
						echo '<div class="form-group">';
						echo '<input type="text" class="form-control" id="pServer" placeholder="Server Name" name="pServer" aria-describedby="pServerHelp" value="' . $row["p_serverName"] . '" required>';
						echo '<small id="pServerHelp" class="form-text text-muted">Enter server name. For ex: server1</small>';
						echo '</div>';
						echo '<div class="form-group">';
						echo '<label>Additional Notes</label>';
						echo '<div class="form-group bmd-form-group">';
						echo '<label class="bmd-label-floating">(Optional) Add Services/EQ Desktop Link Here.</label>';
						echo '<textarea class="form-control" rows="5" name="pNotes">' . strip_tags($row["p_notes"]) . '</textarea>';
						echo '</div>';
						echo '</div>';
						echo '<button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>';
						echo '<button type="submit" class="btn btn-primary pull-right">Submit</button>';
						echo '</form>';
						echo '</div>';
						echo '</div>';
						echo '</div>';
						echo '</div>';
						
					
						
					}
				} 
		  ?>

		  <!--script>
				$( "#logs1" ).ready(function() {
					getFile();
					checkOnline();
				});
				
				function getFile(){
					  $.ajax({
						url: "API/API_GetFileContents.php",
						data: {
							url: "\\\\manvswbfeq17\\AppSrv01_MP3_EQ\\logs\\server1\\SystemOut.log"
						},
							type: "GET",
						dataType: "html",
						success: function (data) {
							var $textarea = $("#logs1");
							$($textarea).val(data);
							$textarea.scrollTop($textarea[0].scrollHeight);
							var scrollTimeOut;
							if($textarea.is(":focus")){
								scrollTimeOut = 10000;
							} else{
								scrollTimeOut = 1000;
							}
							setTimeout(function(){getFile();}, scrollTimeOut);
						}
					});
				}
				
				function checkOnline(){
					  $.ajax({
						url: "API/API_GetProfileStatus.php",
						data: {
							profile: 1
						},
							type: "GET",
						dataType: "html",
						success: function (data) {
							if (data.includes("1")) {
								$("#status1").addClass("btn-success");  
								$("#status1").html("Online");
								$("#start1").prop("disabled", true);
							} else if (data.includes("0")){
								$("#status1").removeClass("btn-success");
								$("#status1").html("Offline");
							    $("#start1").prop("disabled", false);
							}

							setTimeout(function(){checkOnline();}, 1000);
							
						}
					});
				}
				
				function stopServer1(){
					  $.ajax({
						url: "API/API_ServerStop.php",
						data: {
							profile: 1
						},
							type: "GET",
						dataType: "html",
						success: function (data) {
							alert(data);
						}
					});
				}
				
				function startServer1(){
					  $.ajax({
						url: "API/API_ServerStart.php",
						data: {
							profile: 1
						},
							type: "GET",
						dataType: "html",
						success: function (data) {
							alert(data);
						}
					});
				}
		</script-->
		  
        </div>
      </div>
	  
	  
	  
      <footer class="footer">
        <div class="container-fluid">
          <nav class="float-left">
         
          </nav>
          <div class="float-right">
           
          </div>
          <!-- your footer here -->
        </div>
      </footer>
    </div>
  </div>
  <!--   Core JS Files   -->
 
  
  
  
  
</body>

</html>