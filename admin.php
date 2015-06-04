<?php 
	session_start();
	require_once ('authHandler.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<link type="text/css" rel="stylesheet" href="style.css">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title>
			W3BC0M1C
		</title>
	</head>
	
	<body>
		<div id="wrapperBackend">
			<fieldset>
				<div class="logo">
					<img src="pictures/logo.jpg" alt="Very cool Logo">
				</div>
				
				<?php 
						if(!isset($_SESSION['user_name']) && isset($_POST['username']) && $_POST['password'])
						{
							loginUser($_POST['username'],$_POST['password']);
						}
						if(isset($_SESSION['user_name']) && isset($_SESSION['permLevel']))
						{
							if(isAuthorized(ADMIN))
							{
								include("options.php");
							}
							else
							{
								echo "<p class=\"permError\">You are not authorized!</p>";
							}
						}
						else
						{
							include("loginAdmin.php");
						}	
					?>
			</fieldset>
		</div>
	</body>
</html>	