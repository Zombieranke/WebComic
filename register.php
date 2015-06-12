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
				echo "<p>Please fully fill the form </p>";
			}
			else
			{
				$sqlCommand = "INSERT INTO user (username, password, email) VALUES (?, ?, ?)";
				$entry = $connection->prepare($sqlCommand);
				$entry->bind_param('sss', $username, $password,  $email);
					
				if($entry->execute())
				{
					echo "<p>User: ".$username." erfolgreich registriert</p>";
				}
				else
				{
					echo "<p>Username: ".$username." already exists!</p>";
				}
				
				$entry->free_result();
				$entry->close();
			
				$connection->close();
			}
		}
		else
		{
			echo "<p>Passwords did not match</p>";
		}
	}




	echo '<form action="'.htmlspecialchars($_SERVER['PHP_SELF']).'?register=true" method="POST" id="registrationForm" enctype="multipart/form-data">
		<fieldset>
			<input type="text" name="username" placeholder="Username"/>
			<input type="password" name="password" placeholder="Password"/>
			<input type="password" name="passwordConfirm" placeholder="Confirm Password"/>
			<input type="email" name="email" placeholder="E-mail adress"/>
	
			<input type="submit" name="register" value="Register" id="registrationButton"/>
		</fieldset>
	</form>
	<a href="index.php">&lt;&lt;&lt;&lt;Back to main site</a>';
?>