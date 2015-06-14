<?php
	if(!defined('includeConnDetails'))
	{
		define('includeConnDetails', TRUE);
	}
	if(!defined("MODE_FORWARD"))
	{
		define("MODE_FORWARD", 1);
		define("MODE_BACKWARD",-1);
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
		
		$stmt = $connection->prepare("SELECT datapath FROM strip WHERE strip_id= ? AND releasedate < CURRENT_TIMESTAMP");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->bind_result($file);
		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();
		$connection->close();
	
		return $file;
	}
	
	function getStripTitle($stripId)
	{
		require ('connDetails.php');
		
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
		
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
		
		$stmt = $connection->prepare("SELECT stripname FROM strip WHERE strip_id= ?");
		$stmt->bind_param("i", $stripId);
		$stmt->execute();
		$stmt->bind_result($title);
		$stmt->fetch();
		$stmt->free_result();
		
		$stmt->close();
		$connection->close();
		
		$outputString  = "<div id=\"titleDiv\">";
		$outputString .=	$title;
		$outputString .= "</div>";
		
		return $outputString;
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
	
		$stmt = $connection->prepare("SELECT datapath FROM strip WHERE strip_id= ?");
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
		
		$stmt = $connection->prepare("SELECT strip_id, stripname, filename, datapath, releasedate FROM strip ORDER BY releasedate DESC");
		$stmt->execute();
		$stmt->bind_result($id,$stripname,$filename,$file,$date);
		
		$i=0;
		
		while($stmt->fetch())
		{
			$result[$i]['id'] = $id;
			$result[$i]['stripname'] = $stripname;
			$result[$i]['filename'] = $filename;
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
	
	function getDefaultWebcomic()
	{
		require('connDetails.php');
	
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
	
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
	
		$stmt = $connection->prepare("SELECT webcomic_id FROM webcomic LIMIT 1");
		$stmt->execute();
		$stmt->bind_result($id);
		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();
	
		$connection->close();
	
		return $id;
	}
	
	function repositionStrip($stripId, $mode)
	{
		$changeStrip;
		if($mode == MODE_FORWARD)
		{
			$changeStrip = getNextId($stripId);	
		}
		elseif($mode == MODE_BACKWARD)
		{
			$changeStrip = getPreviousId($stripId);	
		}
		
		if(empty($changeStrip))
		{
			return;
		}
		
		require('connDetails.php');
		
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
		
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
		
		$stmt = $connection->prepare("SELECT releasedate FROM strip WHERE strip_id = ?");
		$stmt->bind_param("i", $stripId);
		$stmt->execute();
		$stmt->bind_result($source);
		$stmt->fetch();
		
		$stmt->bind_param("i", $changeStrip);
		$stmt->execute();
		$stmt->bind_result($target);
		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();
		
		$stmt2 = $connection->prepare("UPDATE strip SET releasedate = ? WHERE strip_id = ?");
		$stmt2->bind_param("si",$source, $changeStrip);
		$stmt2->execute();
		
		$stmt2->bind_param("si",$target, $stripId);
		$stmt2->execute();
		$stmt2->free_result();
		$stmt2->close();
		$connection->close();
	}
	
	function sanitizeDatabase()
	{
		require('connDetails.php');
		
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
		
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
		
		$stmt = $connection->prepare("SELECT releasedate, COUNT(*) AS c FROM strip GROUP BY releasedate HAVING c>1");
		$stmt->execute();
		$stmt->bind_result($timestamp,$c);
		$stmt->store_result();
		if($stmt->num_rows == 0)
		{
			return true;
		}
		
		$stmt2 = $connection->prepare("UPDATE strip SET releasedate = DATE_ADD(releasedate, INTERVAL 1 second) WHERE releasedate = ? LIMIT 1");
		
		while($stmt->fetch())
		{
			$stmt2->bind_param("s", $timestamp);
			$stmt2->execute();
		}
		$stmt->close();
		$stmt2->close();
		
		$connection->close();
		
		
		return false;
		
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
	
	
	function getFavouriteDiv($stripId)
	{
		
		require('connDetails.php');
		
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
		
		$stmt = $connection->prepare("SELECT fk_strip_Id FROM faveStrip WHERE fk_strip_Id = ?");
		$stmt->bind_param('i', $stripId);
		$stmt->execute();
		$stmt->store_result();
		
		$count = $stmt->num_rows;
		
		$stmt->free_result();
		
		$stmt = $connection->prepare("SELECT fk_user_id FROM faveStrip WHERE fk_strip_Id = ? AND fk_user_id = ?");
		$stmt->bind_param('ii', $stripId, $userId);
		$stmt->execute();
		$stmt->store_result();
		
		$alreadyFavourited = 0;
	
		if($stmt->num_rows == 1)
		{
			$alreadyFavourited = 1;
		}
		else
		{
			$alreadyFavourited = 0;
		}
		$stmt->free_result();
		
		$stmt->close();
		$connection->close();
		
		$outputString = '
			<script type="text/javascript">
				function favouriteSelected()
				{
					favButton = document.getElementById("favouriteButton");
				
					favButton.innerHTML = "<img id=\"favouriteStar\" src=\"pictures/redFavouriteStar/favouriteStar_selected.jpg\"/>Favourite";
					
				}
					
				function favouriteUnselected()
				{
					favButton = document.getElementById("favouriteButton");
				
					favButton.innerHTML = "<img id=\"favouriteStar\" src=\"pictures/redFavouriteStar/favouriteStar_unselected.jpg\"/>Favourite";
					
				}
					
				function unfavouriteSelected()
				{
					favButton = document.getElementById("unfavouriteButton");
				
					favButton.innerHTML = "<img id=\"favouriteStar\" src=\"pictures/redFavouriteStar/favouriteStar_unselected.jpg\"/>Unfavourite";
					
				}
					
				function unfavouriteUnselected()
				{
					favButton = document.getElementById("unfavouriteButton");
				
					favButton.innerHTML = "<img id=\"favouriteStar\" src=\"pictures/redFavouriteStar/favouriteStar_selected.jpg\"/>Favourited";
					
				}
				
			</script>';
		
		$outputString .=  "<div id=\"favouriteDiv\">";
		
		if(isAuthorized(USER))
		{
			$outputString .= "<form action=\"index.php?id=".$stripId."\" method=\"POST\">";
			
			if($alreadyFavourited == 1)
			{
				$outputString .= 	"<button id=\"unfavouriteButton\" onMouseOver=\"unfavouriteSelected()\" onMouseOut=\"unfavouriteUnselected()\" type=\"submit\" name=\"unfavourite\"  value=\"".$userId."\"><img id=\"favouriteStar\" src=\"pictures/redFavouriteStar/favouriteStar_selected.jpg\" alt=\"A star to indicate the favourite option\">Favourited</button>";
				
			}
			else
			{
				$outputString .= 	"<button id=\"favouriteButton\" onMouseOver=\"favouriteSelected()\" onMouseOut=\"favouriteUnselected()\" type=\"submit\" name=\"favourite\"  value=\"".$userId."\"><img id=\"favouriteStar\" src=\"pictures/redFavouriteStar/favouriteStar_unselected.jpg\" alt=\"A star to indicate the favourite option\">Favourite</button>";
			}
			
			$outputString .= "</form>";
		}
		$outputString .=	"<div id=\"favouriteCountDiv\">";
		$outputString .=		"<span id=\"favouriteCount\">";
		$outputString .=			$count;
		$outputString .=		"</span>";
		$outputString .= 		" users have favourited this strip";
		$outputString .=	"</div>";
		$outputString .= "</div>";
		echo $outputString;
	}
	
	function favouriteStrip($stripId)
	{
		
		if($stripId == -1337)
		{
			return;
		}
		
		require('connDetails.php');
		
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
		
		$stmt = $connection->prepare("INSERT INTO faveStrip (fk_user_id, fk_strip_Id) VALUES (?, ?)");
		$stmt->bind_param('ii', $userId, $stripId);
		$stmt->execute();
		
		$stmt->close();
		$connection->close();
	}
	
	function unfavouriteStrip($stripId)
	{
		if($stripId == -1337)
		{
			return;
		}
		
		require('connDetails.php');
		
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
	
		$stmt = $connection->prepare("DELETE FROM faveStrip WHERE fk_user_id = ? AND fk_strip_Id = ?");
		$stmt->bind_param('ii', $userId, $stripId);
		$stmt->execute();
	
		$stmt->close();
		$connection->close();
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
		
		$stmt = $connection->prepare("SELECT comment_id,username, avatar, comment, timestamp, adminflag
									FROM commentStrip AS cs LEFT JOIN user AS u 
									ON cs.fk_user_id = u.user_id WHERE cs.fk_strip_Id = ? 
									ORDER BY cs.timestamp ASC LIMIT ?,20 ");
		$stmt->bind_param('ii', $stripId, $offset);
		$stmt->execute();
		
		$stmt->bind_result($commentId,$username,$avatar,$comment,$timestamp,$adminflag);
	
		$i=0;
		$commentArray = array();
		
		while($stmt->fetch())
		{
			$commentArray[$i]['commentId'] = $commentId;
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
	
	function createCommentDiv($stripId, $commentId, $username, $avatar, $timestamp, $comment, $adminflag)
	{
		$outputString  = "<div class=\"commentBox\">";
		if(empty($avatar))
		{
			$outputString .=	"<img class=\"commentAvatar\" src=\"avatars/standardAvatar.jpg\" alt=\"Avatar of the author of this comment\">";
		}
		else
		{
			$outputString .=	"<img class=\"commentAvatar\" src=\"avatars/".$avatar."\" alt=\"Avatar of the author of this comment\">";
		}
		
		if($adminflag)
		{
			$outputString  .= "<div class=\"adminComment\">";
		}
		else
		{
			$outputString  .= "<div class=\"comment\">";
		}
		$outputString .=	"<span class=\"commentHeader\">";
		$outputString .=		$username." wrote: ";
		if(isAuthorized(ADMIN))
		{
			$outputString .= "<form action=\"index.php?id=".$stripId."\" method=\"POST\">";
			$outputString .= 	"<button class=\"deleteCommentButton\" type=\"submit\" name=\"deleteComment\"  value=\"".$commentId."\"><img src=\"pictures/redCross.png\" alt=\"Delete Comment\"></button>";
			$outputString .= "</form>";
		}
		$outputString .=		"<span class=\"commentTimestamp\">".$timestamp."</span>";
		$outputString .=	"</span>";
		$outputString .=	"<div class=\"commentContent\">";
		$outputString .=		stripslashes($comment);
		$outputString .=	"</div>";
		$outputString .= "</div>";
		$outputString .= "</div>";
		
		
		return $outputString;
		
	}
	
	
	function deleteComment($commentId)
	{
		require('connDetails.php');
		
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
		
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
		
		$stmt = $connection->prepare("DELETE FROM commentStrip WHERE comment_id = ?");
		$stmt->bind_param('i', $commentId);
		$stmt->execute();
		
		$stmt->free_result();
		$stmt->close();
		$connection->close();
	}
	
	
	
	
	
	
?>
