<?php 
	session_start();
	require_once ('authHandler.php');
	require_once ('styleFunctions.php');
	
	if(!isset($_SESSION['webcomicId']))
	{
		require ('stripFunctions.php');
		$_SESSION['webcomicId'] = getDefaultWebcomic();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<link type="text/css" rel="stylesheet" href="<?php $css = getAppliedCss($_SESSION['webcomicId']); $css = $css ? $css : "style.css"; echo $css;?>">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title>
			W3BC0M1C
		</title>
	</head>
	
	<body>
		<div id="wrapperBackend">
			<fieldset>
				<div class="logo">
					<a href="index.php">
					<img src="<?php $logo = getAppliedLogo($_SESSION['webcomicId']); $logo = $logo ? $logo : "pictures/logo.jpg"; echo $logo;?>" alt="Very cool Logo">
					</a>
				</div>
				
				<?php 
						if(!isset($_SESSION['user_name']) && isset($_POST['username']) && $_POST['password'])
						{
							$loginSuccess = loginUser($_POST['username'],$_POST['password']);
							switch($loginSuccess)
							{
								case LOGIN_SUCCESS:
									echo "<p>Login Successful</p>";
									break;
									
								default:
									echo "<p>Login failed</p>";
									break;
							}
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
