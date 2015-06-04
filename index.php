<?php 
	session_start();
	
	require_once ('authHandler.php');
	require_once ('styleFunctions.php');
	
	if(!isset($_SESSION['webcomicId']))
	{
		require ('stripFunctions.php');
		$_SESSION['webcomicId'] = getDefaultWebcomic();
	}
	
	if(isset($_GET['logout']))
	{
		session_unset();
		session_destroy();
	}
	if(!isset($_SESSION['user_name']) && !isset($_POST['register']) && isset($_POST['username'], $_POST['password']))
	{
		loginUser($_POST['username'],$_POST['password']);
	}
	else if( !isset($_SESSION['user_name']) && isset($_POST['username']) && isset($_GET['reset']) )
	{
		resetPassword($_POST['username']);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<link type="text/css" rel="stylesheet" href="<?php $css =getAppliedCss($_SESSION['webcomicId']); $css = $css ? $css : "style.css"; echo $css; ?>">
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
						<img src="<?php $logo = getAppliedLogo($_SESSION['webcomicId']); $logo = $logo ? $logo : "pictures/logo.jpg"; echo $logo;?>" alt="Very cool Logo">
					</a>
				</div>
				<ul id="navigation">
					<li><a href="index.php">Comic</a></li>
					<li><a href="index.php?view=about">About</a></li>
					<li><a href="index.php?view=contact">Contact</a></li>
					<?php
						if( isset($_SESSION['user_name']) )
						{
							echo '<li><a href="index.php?logout">Logout</a></li>';
						}
						else
						{
							echo '<li><a href="index.php?login=true">Login</a></li>';
							echo '<li><a href="index.php?register=true">Register</a></li>';
						}
					
						if( isAuthorized(ADMIN) )
						{
							echo '<li><a href="admin.php">Admin</a></li>';
						}
					?>
				</ul>
				
				<div id="content">
					<?php
						if(isset($_GET['login']) && $_GET['login'] == 'true')
						{
							include('login.php');
						}
						else if(isset($_GET['register']))
						{
							include('register.php');
						}
						else if (isset($_GET['reset']))
						{
							include('reset.php');
						}
						else
						{
							include('comicNavigation.php');
						}
					?>
				</div>
				<?php 
				if(!isset($_SESSION['user_name']))
				{
					echo '<div class="loginLinks">
						<a href="index.php?register=true">Register</a>
						|
						<a href="index.php?login=true">Login</a>
					</div>';
				}
				else
				{
					echo "<div class=\"loginLinks\">";
					echo	"<a href=\"".htmlspecialchars($_SERVER['PHP_SELF'])."?logout\">Logout</a>";
					echo "</div>";
				}
				?>
			</fieldset>
		</div>
	</body>
</html>