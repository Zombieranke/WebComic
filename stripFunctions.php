<?php
	if(!defined('includeConnDetails'))
	{
		define('includeConnDetails', TRUE);
	}
	
	function fetchStrip($id)
	{
		require ('connDetails.php');
		if(!is_numeric($id))
		{
			die("Parameter unzul&auml;ssig");
		}
	
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
	
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
		
		$stmt = $connection->prepare("SELECT data FROM strip WHERE strip_id= ? AND releasedate < CURRENT_TIMESTAMP");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->bind_result($file);
		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();
		$connection->close();
	
		return $file;
	}
	
	function fetchStripAbs($id)
	{
		require ('connDetails.php');
		if(!is_numeric($id))
		{
			die("Parameter unzul&auml;ssig");
		}
	
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
	
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
	
		$stmt = $connection->prepare("SELECT data FROM strip WHERE strip_id= ?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->bind_result($file);
		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();
		$connection->close();
	
		return $file;
	}
	
	function getNextId($id)
	{
		require ('connDetails.php');
		if(!is_numeric($id))
		{
			die("Parameter unzul&auml;ssig");
		}
	
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
	
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
	
		$stmt = $connection->prepare("SELECT strip_id FROM strip WHERE releasedate > (SELECT releasedate FROM strip WHERE strip_id = ?) AND releasedate < CURRENT_TIMESTAMP ORDER BY releasedate ASC LIMIT 1");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->bind_result($resId);
		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();
		$connection->close();
	
		return $resId;
	}
	
	function getPreviousId($id)
	{
		require ('connDetails.php');
		if(!is_numeric($id))
		{
			die("Parameter unzul&auml;ssig");
		}
	
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
	
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
	
		$stmt = $connection->prepare("SELECT strip_id FROM strip WHERE releasedate < (SELECT releasedate FROM strip WHERE strip_id = ?) AND releasedate < CURRENT_TIMESTAMP ORDER BY releasedate DESC LIMIT 1");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->bind_result($resId);
		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();
		$connection->close();
	
		return $resId;
	}
	
	function getFirstId()
	{
		require ('connDetails.php');
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
	
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
	
		$stmt = $connection->prepare("SELECT strip_id FROM strip WHERE releasedate < CURRENT_TIMESTAMP ORDER BY releasedate ASC LIMIT 1");
		$stmt->execute();
		$stmt->bind_result($resId);
		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();
		$connection->close();
	
		return $resId;
	}
	
	function getLatestId()
	{
		require ('connDetails.php');
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
	
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
	
		$stmt = $connection->prepare("SELECT strip_id FROM strip WHERE releasedate < CURRENT_TIMESTAMP ORDER BY releasedate DESC LIMIT 1");
		$stmt->execute();
		$stmt->bind_result($resId);
		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();
		$connection->close();
	
		return $resId;
	}
	
	function getRandomId()
	{
		require ('connDetails.php');
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
	
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
	
		$stmt = $connection->prepare("SELECT strip_id FROM strip WHERE releasedate < CURRENT_TIMESTAMP ORDER BY RAND() ASC LIMIT 1");
		$stmt->execute();
		$stmt->bind_result($resId);
		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();
		$connection->close();
	
		return $resId;
	}
	
	function getStrips()
	{
		require ('connDetails.php');
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
		
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
		
		$stmt = $connection->prepare("SELECT strip_id, name, data, releasedate FROM strip ORDER BY releasedate DESC");
		$stmt->execute();
		$stmt->bind_result($id,$name,$file,$date);
		
		$i=0;
		
		while($stmt->fetch())
		{
			$result[$i]['id'] = $id;
			$result[$i]['name'] = $name;
			$result[$i]['file'] = $file;
			$result[$i]['date'] = $date;
			$i++;
		}
		
		$stmt->free_result();
		$stmt->close();
		$connection->close();
		
		if(!empty($result))
		{
			return $result;
		}
		else
		{
			return false;
		}
	}
	
	function deleteStrip($id)
	{
		require ('connDetails.php');
		
		if(!is_numeric($id))
		{
			die("Parameter unzul&auml;ssig");
		}
		
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
		
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
		
		$file = fetchStripAbs($id);
		if(!empty($file))
		{
			unlink($file);
			
			$stmt = $connection->prepare("DELETE FROM strip WHERE strip_id = ?");
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$stmt->free_result();
			$stmt->close();
			$connection->close();
		}
		else
		{
			echo "<p>Could not find strip to delete</p>";
		}
	}
	
	function getWebcomics()
	{
		require('connDetails.php');
	
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
	
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
	
		$stmt = $connection->prepare("SELECT title, webcomic_id FROM webcomic");
		$stmt->execute();
		$stmt->bind_result($title,$id);
	
		$i=0;
	
		while($stmt->fetch())
		{
			$webcomics[$i]['title']=$title;
			$webcomics[$i]['id']=$id;
			$i++;
		}
	
		$stmt->free_result();
		$stmt->close();
	
		$connection->close();
	
		return $webcomics;
	}
	
	
	function getAnnotation($stripId)
	{
		require('connDetails.php');
		
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
		
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
		
		$stmt = $connection->prepare("SELECT annotation FROM strip WHERE strip_id = ?");
		$stmt->bind_param('i', $stripId);
		$stmt->bind_result($annotation);
		$stmt->execute();
		$stmt->fetch();
		
		$stmt->close();
		$connection->close();
		
		return $annotation;
	}
	
	
	function commentStrip($text,$stripId)
	{
		require('connDetails.php');
		
		$text = addslashes($text); //this function escapes all logical symbols
		
		
		if($stripId == -1337)
		{
			return;
		}
		
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
		
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
		
		$stmt = $connection->prepare("SELECT user_id FROM user WHERE username = ?");
		$stmt->bind_param('s', $_SESSION['user_name']);
		$stmt->bind_result($userId);
		$stmt->execute();
		$stmt->fetch();
		
		$stmt->free_result();
		
		$stmt = $connection->prepare("INSERT INTO commentStrip (fk_user_id, fk_strip_Id, comment) VALUES (?, ?, ?)");
		$stmt->bind_param('iis', $userId, $stripId, $text);
		$stmt->execute();
		
		$stmt->close();
		$connection->close();
	}
	
	
	function getComments($stripId,$offset)
	{
		require('connDetails.php');
		
		
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
		
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
		
		$stmt = $connection->prepare("SELECT username, avatar, comment, timestamp, adminflag
									FROM commentStrip AS cs LEFT JOIN user AS u 
									ON cs.fk_user_id = u.user_id WHERE cs.fk_strip_Id = ? 
									ORDER BY cs.timestamp ASC LIMIT ?,20 ");
		$stmt->bind_param('ii', $stripId, $offset);
		$stmt->execute();
		
		$stmt->bind_result($username,$avatar,$comment,$timestamp,$adminflag);
	
		$i=0;
		$commentArray = array();
		
		while($stmt->fetch())
		{
			$commentArray[$i]['username'] = $username;
			$commentArray[$i]['avatar'] = $avatar;
			$commentArray[$i]['comment'] = $comment;
			$commentArray[$i]['timestamp'] = $timestamp;
			$commentArray[$i]['adminflag'] = $adminflag;
			$i++;
		}
		
		$stmt->free_result();
		$stmt->close();
		$connection->close();
		
		return $commentArray;
		
	}
	
	
	
	function createCommentDiv($username, $avatar, $timestamp, $comment, $adminflag)
	{
		if($adminflag)
		{
			$outputString  = "<div class=\"adminComment\">";
		}
		else
		{
			$outputString  = "<div class=\"comment\">";
		}
		$outputString .=	"<p class=\"commentHeader\">";
		$outputString .=		"<span class=\"commentTimestamp\">".$timestamp."</span>";
		$outputString .=		$username." wrote: ";
		$outputString .=	"</p>";
		$outputString .=	"<div class=\"commentContent\">";
		$outputString .=		stripslashes($comment);
		$outputString .=	"</div>";
		$outputString .= "</div>";
		
		return $outputString;
		
	}
	
	
	
	
	
	
	
	
	
?>