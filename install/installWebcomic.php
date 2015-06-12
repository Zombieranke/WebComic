<?php
	if(!defined('includeInstall'))
	{
		header('HTTP/1.0 403 Forbidden');
		die("It is forbidden to access this page directly");
	}
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
					echo '<p id="installError"> Comic needs a name! </p>';
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
		if(isset($_POST['adminName'],$_POST['adminPassword'],$_POST['adminPasswordConfirm'],$_POST['adminEmail']))
		{
			$_SESSION['error']= false;
		
			if(empty($_POST['adminName']) || empty($_POST['adminPassword']) || empty($_POST['adminPasswordConfirm']) || empty($_POST['adminEmail']))
			{
				$_SESSION['error']= true;
				$_SESSION['errorCode']= 1;
			}
			else
			{
				if($_POST['adminPassword'] == $_POST['adminPasswordConfirm'])
				{
					require("../connDetails.php");
					
					$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
						
					if($connection->errno != 0)
					{
						die("Database connection failed: ".$connection->connect_error);
					}
					
					$stmt = $connection->prepare("SELECT COUNT(*) FROM user WHERE adminflag = 1");
					$stmt->execute();
					$stmt->bind_result($needUpdate);
					$stmt->fetch();
					$stmt->free_result();
					$stmt->close();
					
					$username = filter_var($_POST['adminName'],FILTER_SANITIZE_STRING);
					$password = password_hash($_POST['adminPassword'], PASSWORD_BCRYPT);
					$email = filter_var($_POST['adminEmail'], FILTER_SANITIZE_EMAIL);
					
					if($needUpdate == 0)
					{		
						$sqlCommand = "INSERT INTO user (username, password, email, adminflag) VALUES (?, ?, ?, 1)";
						$entry = $connection->prepare($sqlCommand);
						$entry->bind_param('sss', $username, $password,  $email);
							
						$entry->execute();

						$entry->free_result();
						$entry->close();
							
						$connection->close();
					}
					else
					{
						$sqlCommand = "UPDATE user SET username = ?, password = ?, email = ? WHERE adminflag = 1";
						$entry = $connection->prepare($sqlCommand);
						$entry->bind_param('sss', $username, $password,  $email);
							
						$entry->execute();
						
						$entry->free_result();
						$entry->close();
							
						$connection->close();
					}
				}
				else
				{
					$_SESSION['error']= true;
					$_SESSION['errorCode']= 2;
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
	
	echo 	'<h1 id="installHeadline">Webcomic configuration</h1>';
	
	echo 	'<form id="installForm" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="POST" enctype="multipart/form-data">
				<lable>Webcomic name</lable>
				<input type="text" name="comicName" placeholder="Name">
				<lable>Logo</lable>
				<input type="file" name="comicLogo" >
				<button id="installButton" type="submit" name="progress" value="progress">Proceed</button>
			</form>';
	
	echo 	'<form action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="POST">
				<button id="installButton2" type="submit" name="back" value="back">Back</button>
			</form>';
?>