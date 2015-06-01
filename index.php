<?php 
	session_start();
	
	require_once ('authHandler.php');
	if(isset($_GET['logout']))
	{
		session_unset();
		session_destroy();
	}
	if(!isset($_SESSION['user_name']) && isset($_POST['username']) && $_POST['password'])
	{
		loginUser($_POST['username'],$_POST['password']);
	}
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
		<div id="wrapper">
			<fieldset>
				<div class="logo">
					<a href="index.php">
						<img src="pictures/logo.jpg" alt="Very cool Logo">
					</a>
				</div>
				<ul id="navigation">
					<li><a href="index.php">Comic</a> </li>
					<li><a href="index.php">About me</a> </li>
					<li><a href="index.php">Contact</a>	</li>
					<li><a href="index.php">Login</a> </li>
				</ul>
				
				<div id="content">
					<?php 		
						include('comicNavigation.php');
					?>
				</div>
				<?php 
				if(!isset($_SESSION['user_name']))
				{
					echo '<div id="loginLinks">
						<a href="register.php">Register</a>
						|
						<a href="login.php">Login</a>
					</div>';
				}
				else
				{
					echo '<a href="'.htmlspecialchars($_SERVER['PHP_SELF']).'?logout">Logout</a>';
				}
				?>
			</fieldset>
		</div>
	</body>
</html>