<!DOCTYPE html>
	<head>
		<link type="text/css" rel="stylesheet" href="style.css">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title>
			W3BC0M1C
		</title>
	</head>
	
	<body>
		<div id="wrapper">
			<div class="logo">
				<img src="pictures/logo.jpg" alt="Very cool Logo">
			</div>
			
			<?php 
					if(!isset($_SESSION['username']) && isset($_POST['username']) && $_POST['password'])
					{
						authenticate_user($_POST['username'],$_POST['password']);
					}
					if(isset($_COOKIE['username']) || isset($_SESSION['username']))
					{
						include("options.php");
						if(isset($_COOKIE['username']) && !isset($_SESSION['username']))
						{
							$_SESSION['username'] = $_COOKIE['username'];
						}
					}
					else
					{
						include("login.php");
					}
					
					
					function authenticate_user($user,$password)
					{
						if (database_login($user,$password))
						{
							login_user($user);
						}
					}
					
					function login_user($username)
					{
						if(isset($_POST['stillAlive']))
						{
							setcookie("username","".$username,time()+13371337);
						}
						$_SESSION['username'] = $username;
					}
				?>
		</div>
	</body>
</html>	