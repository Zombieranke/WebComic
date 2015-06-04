<?php
	if(!defined('includeConnDetails'))
	{
		define('includeConnDetails', TRUE);
	}
	define("ANON",0);
	define("USER",1);
	define("ADMIN",2);

	if( !isset($_SESSION['permLevel']) )
	{
		$_SESSION['permLevel'] = ANON;
	}

	function isAuthorized($reqPermLevel)
	{
		if(isset($_SESSION['permLevel']))
		{
			return $_SESSION['permLevel']>=$reqPermLevel;
		}
		else
		{
			return $reqPermLevel == ANON ? TRUE : FALSE;
		}
	}

	function loginUser($username,$password)
	{
		require ('connDetails.php');
		
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
	
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
		
		$stmt = $connection->prepare("SELECT password, adminflag FROM user WHERE username=?");
		$stmt->bind_param('s', $username);
	
		$stmt->execute();
		$stmt->bind_result($database_password, $admin);
		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();
	
	
		$connection->close();

		
		
		if(isset($database_password))
		{
			if(password_verify($password, $database_password))
			{
				if($admin)
				{
					$_SESSION['permLevel'] = ADMIN;
				}
				else
				{
					$_SESSION['permLevel'] = USER;
				}
				
				$_SESSION['user_name'] = $username;
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
	
	function changePassword($username,$from,$to)
	{
		if(loginUser($username, $from))
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
	
	function resetPassword()
	{
		//
	}

?>