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
		<div class="wrapper">
			<div class="logo">
				<img src="pictures/logo.jpg" alt="Very cool Logo">
			</div>
			
			<?php 
					if(!isset($_SESSION['username']) && isset($_POST['username']) && $_POST['password'])
					{
						if(authorizeAdmin($_POST['username'],$_POST['password']))
						{
							$_SESSION['username'] = $_POST['username'];
							$_SESSION['permLevel'] = ADMIN;
						}
					}
					if(isset($_SESSION['username']) && isset($_SESSION['permLevel']))
					{
						if(isAuthorized(ADMIN))
						{
							include("options.php");
						}
						else
						{
							echo "<p class=\"permError\">Unzureichende Befugnisse</p>";
						}
					}
					else
					{
						include("loginAdmin.php");
					}	
				?>
		</div>
	</body>
</html>	