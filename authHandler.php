<?php
	if(!defined('includeConnDetails'))
	{
		define('includeConnDetails', TRUE);
	}
	define("ANON",0);
	define("USER",1);
	define("ADMIN",2);


	function isAuthorized($reqPermLevel)
	{
		return $_SESSION['permLevel']>=$reqPermLevel;
	}

	function authorizeAdmin($username,$password)
	{
		require ('connDetails.php');
		
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
	
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
		
		$stmt = $connection->prepare("SELECT password FROM user WHERE username=? AND adminflag=true");
		$stmt->bind_param('s', $username);
	
		$stmt->execute();
		$stmt->bind_result($database_password);
		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();
	
	
		$connection->close();

		
		
		if(isset($database_password))
		{
			if(password_verify($password, $database_password))
			{
				return true;
			}
			else
			{
				echo "<p id=\"loginError\">Wrong username or password</p>";
				return false;
			}
		}
		else
		{
			echo "<p id=\"loginError\">Wrong username or password</p>";
			return false;
		}
	}
	
	function changeAdminPass($username,$from,$to)
	{
		if(authorizeAdmin($username, $from))
		{
			$newpass = password_hash($to, PASSWORD_BCRYPT);
			
			require ('connDetails.php');
			
			$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
			
			if($connection->errno != 0)
			{
				die("Database connection failed: ".$connection->connect_error);
			}
			
			$stmt = $connection->prepare("UPDATE user SET password =? WHERE username=?");
			$stmt->bind_param('ss', $newpass, $username);
			
			$stmt->execute();
			$stmt->free_result();
			$stmt->close();
			$connection->close();
		}
	}

?>