<?php
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
	
	echo 	'<h1 id="installHeadline">Database connection</h1>';


	echo 	'<form id="installForm" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="POST">
				<lable>Database Adress</lable>
				<input type="text" name="dbHost" placeholder="Database Adress">
				<lable>Database Name</lable>
				<input type="text" name="dbName" placeholder="Database Name">
				<lable>Database User</lable>
				<input type="text" name="dbUser" placeholder="Database User">
				<lable>Database Password</lable>
				<input type="password" name="dbPassword" placeholder="Database Password">
				<button id="installButton" type="submit" name="progress" value="progress">Proceed</button>
			</form>';
	
	echo 	'<form action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="POST">
				<button id="installButton2" type="submit" name="back" value="back">Back</button>
			</form>';