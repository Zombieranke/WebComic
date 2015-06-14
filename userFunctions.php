<?php
	if(!defined('includeConnDetails'))
	{
		define('includeConnDetails', TRUE);
	}
	
	function getUsers()
	{
		require('connDetails.php');
		
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
		
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
		
		$stmt = $connection->prepare("SELECT user_id, username, email, avatar FROM user WHERE adminflag = 0");
		$stmt->execute();
		$stmt->bind_result($id,$name,$email,$avatar);
		
		$i=0;
		$result = array();
		
		while($stmt->fetch())
		{
			$result[$i]['id'] = $id;
			$result[$i]['name'] = $name;
			$result[$i]['email'] = $email;
			$result[$i]['avatar'] = $avatar;
			$i++;
		}
		
		$stmt->free_result();
		$stmt->close();
		$connection->close();
		
		return $file;
	}
	
	function isSuspended($userId)
	{
		require ('connDetails.php');
			
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
			
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
		
		$stmt = $connection->prepare("SELECT suspended FROM user WHERE user_id=?");
		$stmt->bind_param('s', $userId);
			
		$stmt->execute();
		$stmt->bind_result($limit);
		$stmt->fetch();
		
		$stmt->free_result();
		$stmt->close();
	
		if( strtotime($limit) < strtotime('now') )
		{
			return true;
		}
		return false;
	}
	
	function banUser($userId)
	{
		suspendUser($userId,36500);
	}
	
	function suspendUser($userId, $timeperiod)
	{
		require('connDetails.php');
		
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
		
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
		
		$stmt = $connection->prepare("UPDATE user SET suspended = DATE_ADD(now(), INTERVAL ? day) WHERE USER_ID = ?");
		$stmt->bind_param("ii", $userId, $timeperiod);
		$stmt->execute();
		
		$stmt->free_result();
		$stmt->close();
		$connection->close();
	}
	
	function deleteUser($userId)
	{
		require('connDetails.php');
		
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
		
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
		
		$stmt = $connection->prepare("DELETE FROM user WHERE USER_ID = ?");
		$stmt->bind_param("i", $userId);
		$stmt->execute();
		
		$stmt->free_result();
		$stmt->close();
		$connection->close();
	}
?>