<?php
	if(!defined('includeConnDetails'))
	{
		define('includeConnDetails', TRUE);
	}
	
	require ('connDetails.php');
	
	
	if(isset($_POST['username'],$_POST['password'],$_POST['passwordConfirm'],$_POST['email']))
	{
		if($_POST['password'] == $_POST['passwordConfirm'])
		{
			$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
			
			if($connection->errno != 0)
			{
				die("Database connection failed: ".$connection->connect_error);
			}
			
			$username = filter_var($_POST['username'],FILTER_SANITIZE_STRING);
			$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
			$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
			
			
			if(strcmp($username,"") == 0 || strcmp($password,"") == 0 || strcmp($email,"") == 0)
			{
				echo "<div id=\"registerMessageError\">Please fully fill the form</div>";
			}
			else
			{
				$sqlCommand = "INSERT INTO user (username, password, email) VALUES (?, ?, ?)";
				$entry = $connection->prepare($sqlCommand);
				$entry->bind_param('sss', $username, $password,  $email);
					
				if($entry->execute())
				{
					echo "<div id=\"registerMessageSuccess\">User: ".$username." erfolgreich registriert</p>";
				}
				else
				{
					echo "<div id=\"registerMessageError\">Username '".$username."' already exists!</p>";
				}
				
				$entry->free_result();
				$entry->close();
			
				$connection->close();
			}
		}
		else
		{
			echo "<div id=\"registerMessageError\">Passwords did not match!</div>";
		}
	}




	echo '
		<fieldset id="registrationFieldset">
			<form id="registrationForm" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'?register=true" method="POST" enctype="multipart/form-data">
				<input class="registration" type="text" name="username" placeholder="Username"/>
				<input class="registration" type="password" name="password" placeholder="Password"/>
				<input class="registration" type="password" name="passwordConfirm" placeholder="Confirm Password"/>
				<input class="registration" type="text" name="email" placeholder="E-mail address"/>
		
				<input id="registrationSubmit" type="submit" name="register" value="Register Now" />
			</form>
			<form action="index.php" method="POST">
				<button id="delayRegistrationButton" type="submit" name="delayRegistration"  value="delayRegistration">Sign Up Later</button>
			</form>
		</fieldset>';
?>