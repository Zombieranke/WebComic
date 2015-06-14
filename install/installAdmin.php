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
	

	
	echo	'<script type="text/javascript">
					function confirmPass()
				{
					newPass = document.getElementById("newPass").value;
					newPassConfirm = document.getElementById("newPassConfirm").value;
					if(newPass == newPassConfirm && newPass != "")
					{
						document.getElementById("checkMark").innerHTML = "<img src=\"../pictures/Checkmark.png\"/>";
						return true;
					}
					else if(newPassConfirm !="")
					{
						document.getElementById("checkMark").innerHTML = "<img src=\"../pictures/redCross.png\"/>";
						return false;
					}
				}
					
				</script>';
	
	
	echo 	'<div id="installForm"><form action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="POST">
				<fieldset>
				<div class="installFormDiv">Username</div>
				<input type="text" name="adminName" placeholder="Username"></br>
				<div class="installFormDiv">Password</div>
				<input id="newPass" type="password" name="adminPassword" placeholder="Password" onInput="confirmPass()"></br>
				<div class="installFormDiv">Confirm password</div>
				<input id="newPassConfirm" type="password" name="adminPasswordConfirm" placeholder="Confirm Password" onInput="confirmPass()">
				<div id="checkMark"></div></br>
				<div class="installFormDiv">Email</div>
				<input type="email" name="adminEmail" placeholder="Email"></br>
				<button id="installButton" type="submit" name="progress" value="progress">Proceed</button>
				</fieldset>
			</form></div>';
	
	echo 	'<form action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="POST">
				<button id="installButton2" type="submit" name="back" value="back">Back</button>
			</form>';
?>