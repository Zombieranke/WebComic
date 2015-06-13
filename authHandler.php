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
			changePass($username, $to);
		}
	}
	
	function validateKey($userkey, $username)
	{
		require ('connDetails.php');
			
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
			
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
		
		$stmt = $connection->prepare("SELECT resetkey, timelimit FROM user WHERE username=?");
		$stmt->bind_param('s', $username);
			
		$stmt->execute();
		$stmt->bind_result($key,$limit);
		$stmt->fetch();
		
		$stmt->free_result();
		$stmt->close();
			
		if(isset($key,$limit))
		{
			if( strtotime($limit) > strtotime('now') ) 
			{
				if(password_verify($userkey, $key))
				{
					$stmt = $connection->prepare("UPDATE user SET resetkey = NULL WHERE username=?");
					$stmt->bind_param('s', $username);
						
					$stmt->execute();
					
					$stmt->free_result();
					$stmt->close();
					return true;
				}
			}
		}
		$connection->close();
		return false;
	}
	
	function changePass($username,$to)
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
	
	
	
	function resetPassword($username)
	{
		// If username exists, create a reset key, write the key into the database and send it to the user via his/her entered email.
		require('connDetails.php');
		
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
		
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
		
		$stmt = $connection->prepare("SELECT user_id, email FROM user WHERE username=?");
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$stmt->bind_result($id,$email);
		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();
		
		if( !empty($id) && !empty($email)) // If user exists, create reset key and write it into the db as well as send the user a mail.
		{
			$resetkey = rtrim(strtr(base64_encode(mcrypt_create_iv(64)), '+/', '-_'), '=');
			$resetkey_hash = password_hash($resetkey, PASSWORD_DEFAULT);
			$stmt = $connection->prepare("UPDATE user SET resetkey=?, timelimit= DATE_ADD(NOW(), INTERVAL 4 hour) WHERE user_id=?");
			$stmt->bind_param('si', $resetkey_hash, $id);
			$stmt->execute();
			$stmt->free_result();
			$stmt->close();

			$resetLink= full_url($_SERVER).'Key='.$resetkey;
			
			$mailSubject = 'Webcomic Password retrieval';
			
			$mailText = "Hello ".$username.",\n click on this link to reset your password: ".$resetLink."\nIf you did not trigger this email you might want your password.\n
					Yours sincerly, Webcomic Team";
			
			
			// ======= Mailversand
			
			// Mail versenden und Versanderfolg merken
			$mailSent = @mail($email, $mailSubject, $mailText);
			
			
			if( !defined('PasswordReset') )
			{
				define('PasswordReset', TRUE);
			}
		}

		$connection->close();
	}
	
	function url_origin($s, $use_forwarded_host=false)
	{
		$ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
		$sp = strtolower($s['SERVER_PROTOCOL']);
		$protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
		$port = $s['SERVER_PORT'];
		$port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
		$host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
		$host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
		return $protocol . '://' . $host;
	}
	
	function full_url($s, $use_forwarded_host=false)
	{
		return url_origin($s, $use_forwarded_host) . $s['REQUEST_URI'];
	}
	
	function changeEmail($username,$from,$newEmail)
	{
		if(loginUser($username, $from))
		{		
			require ('connDetails.php');
				
			$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
				
			if($connection->errno != 0)
			{
				die("Database connection failed: ".$connection->connect_error);
			}
				
			$stmt = $connection->prepare("UPDATE user SET email =? WHERE username=?");
			$stmt->bind_param('ss', $newEmail, $username);
				
			$stmt->execute();
			$stmt->free_result();
			$stmt->close();
			$connection->close();
		}
	}

?>