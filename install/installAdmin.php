<?php
	if(!defined('includeConnDetails'))
	{
		define('includeConnDetails', TRUE);
	}
	
	if(isset($_SESSION['error']))
	{
		if($_SESSION['error'] == true)
		{
			switch($_SESSION['errorCode'])
			{
				case 0:
					break;
	
				case 1:
					echo '<p id="installError"> Entries cannot be empty. Please check specified information! </p>';
					break;
	
				case 2:
					echo '<p id="installError"> Passwords did not match! </p>';
					break;
	
				default:
					break;
			}
			unset($_SESSION['error']);
			unset($_SESSION['errorCode']);
		}
	}
	
	
	//handle input from before
	if(isset($_POST['progress']))
	{
		if(isset($_POST['dbHost'],$_POST['dbName'],$_POST['dbUser'],$_POST['dbPassword']))
		{
			$_SESSION['error']= false;
			
			if(empty($_POST['dbHost']) || empty($_POST['dbName']) || empty($_POST['dbUser']) || empty($_POST['dbPassword']))
			{
				$_SESSION['error']= true;
				$_SESSION['errorCode']= 1;
			}
			else 
			{
				$file = fopen("../connDetails.php","w");
			
				$txt =
'<?php
	if(!defined(\'includeConnDetails\'))
	{
		header(\'HTTP/1.0 403 Forbidden\');
		die("It is forbidden to access this page directly");
	}
	else
	{
		$database[\'dbServer\'] = \''.$_POST['dbHost'].'\';
		$database[\'dbName\'] = \''.$_POST['dbName'].'\';
		$database[\'dbUser\'] = \''.$_POST['dbUser'].'\';
		$database[\'dbPassword\'] = \''.$_POST['dbPassword'].'\';
	}
	
?>';
			
				fwrite($file, $txt);
				fclose($file);
			
			
				require("../connDetails.php");
				
				$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
				
				if($connection->connect_error || $connection->errno != 0)
				{
					$_SESSION['error']= true;
					$_SESSION['errorCode']= 2;
				}
				else
				{
					require("setupDatabase.php");
					$connection->close();
				}
			}
		}
		else
		{
			$_SESSION['error']= true;
			$_SESSION['errorCode']= 1;
		}
	
		
		if($_SESSION['error'])
		{
			$_SESSION['installStep']--;
			header("Location: ".htmlspecialchars($_SERVER['PHP_SELF']));
			die;
		}
	}

	
	echo 	'<h1 id="installHeadline">Administrator account</h1>';
	
	
	echo 	'<form id="installForm" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="POST">
				<lable>Username</lable>
				<input type="text" name="adminName" placeholder="Username">
				<lable>Password</lable>
				<input type="password" name="adminPassword" placeholder="Password">
				<lable>Confirm password</lable>
				<input type="password" name="adminPasswordConfirm" placeholder="Confirm Password">
				<lable>Email</lable>
				<input type="email" name="adminEmail" placeholder="Email">
				<button id="installButton" type="submit" name="progress" value="progress">Proceed</button>
			</form>';
	
	echo 	'<form action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="POST">
				<button id="installButton2" type="submit" name="back" value="back">Back</button>
			</form>';
?>