<?php
	if(!defined('includeInstall'))
	{
		header('HTTP/1.0 403 Forbidden');
		die("It is forbidden to access this page directly");
	}
	
	echo 	'<h1 id="installHeadline">Database connection</h1>';
	
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
					echo '<p id="installError"> Could not connect to database. Please check specified information! </p>';
					break;
						
				default:
					break;
			}
			unset($_SESSION['error']);
			unset($_SESSION['errorCode']);
		}
	}


	echo 	'<div id="installForm"><form action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="POST">
				<fieldset>
				<div class="installFormDiv">Database Address</div>
				<input type="text" name="dbHost" placeholder="Database Address"></br>
				<div class="installFormDiv">Database Name</div>
				<input type="text" name="dbName" placeholder="Database Name"></br>
				<div class="installFormDiv">Database User</div>
				<input type="text" name="dbUser" placeholder="Database User"></br>
				<div class="installFormDiv">Database Password</div>
				<input type="password" name="dbPassword" placeholder="Database Password"></br>
				<button id="installButton" type="submit" name="progress" value="progress">Proceed</button>
				</fieldset>
			</form></div>';
	
	echo 	'<form action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="POST">
				<button id="installButton2" type="submit" name="back" value="back">Back</button>
			</form>';