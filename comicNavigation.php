<?php
	define('includeConnDetails', TRUE);
	

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
		
		$stmt = $connection->prepare("SELECT datei FROM strips WHERE strip_id= ?");
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
		
		$stmt = $connection->prepare("SELECT strip_id FROM strips WHERE veröffentlichungsdatum > (SELECT veröffentlichungsdatum FROM strips WHERE strip_id = ?) ORDER BY veröffentlichungsdatum DESC LIMIT 1");
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
		
		$stmt = $connection->prepare("SELECT strip_id FROM strips WHERE veröffentlichungsdatum < (SELECT veröffentlichungsdatum FROM strips WHERE strip_id = ?) ORDER BY veröffentlichungsdatum ASC LIMIT 1");
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
		
		$stmt = $connection->prepare("SELECT strip_id FROM strips ORDER BY veröffentlichungsdatum ASC LIMIT 1");
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
		
		$stmt = $connection->prepare("SELECT strip_id FROM strips ORDER BY veröffentlichungsdatum DESC LIMIT 1");
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
		
		$stmt = $connection->prepare("SELECT strip_id FROM strips ORDER BY RAND() ASC LIMIT 1");
		$stmt->execute();
		$stmt->bind_result($resId);
		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();
		$connection->close();
		
		return $resId;
	}
	
	$latestId = getLatestId();
	if(empty($latestId))
	{
		echo "<p> Sorry there is nothing here yet</p>";
	}
	else
	{
		$curId = isset($_GET['id']) ? $_GET['id'] : $latestId;
		$curPath = fetchStrip($curId);
		
		if(empty($curPath))
		{
			echo "<p> Nothing found for this id!</p>";
		}
		else
		{
			$nextId = getNextId($curId);
			$previousId = getPreviousId($curId);
			$randomId = getRandomId();
			$firstId = getFirstId();
			$hasNext=false;
		
			$comicNavigation =	"<ul class=\"comic_navigation\">";
			
			if($firstId!=$curId)
			{
				$comicNavigation .=	"<li class=\"navFirst\">";
				$comicNavigation .=		"<a href=\"index.php?id=";
				$comicNavigation .= 	$firstId;
				$comicNavigation .= 	"\">";
				$comicNavigation .=			"<img src=\"pictures/doubleArrowLeft.png\" alt=\"Go to oldest strip\">"; 
				$comicNavigation .=		"</a>";
				$comicNavigation .= "</li>";
			}
			
			if(!empty($previousId))
			{
				$comicNavigation .=	"<li class=\"navPrevious\">";
				$comicNavigation .=		"<a href=\"index.php?id=";
				$comicNavigation .= 	$previousId;
				$comicNavigation .= 	"\">";
				$comicNavigation .=			"<img src=\"pictures/singleArrowLeft.png\" alt=\"Go to previous strip\">";
				$comicNavigation .=		"</a>";
				$comicNavigation .= "</li>";
			}
			if(!empty($randomId))
			{
				$comicNavigation .=	"<li class=\"navRandom\">";
				$comicNavigation .=		"<a href=\"index.php?id=";
				$comicNavigation .= 	$randomId;
				$comicNavigation .= 	"\">";
				$comicNavigation .=			"<img src=\"pictures/doubleArrowLeft.png\" alt=\"Go to random strip\">";
				$comicNavigation .=		"</a>";
				$comicNavigation .= "</li>";
			}
			if(!empty($nextId))
			{
				$comicNavigation .=	"<li class=\"navNext\">";
				$comicNavigation .=		"<a href=\"index.php?id=";
				$comicNavigation .= 	$nextId;
				$comicNavigation .= 	"\">";
				$comicNavigation .=			"<img src=\"pictures/singleArrowRight.png\" alt=\"Go to next strip\"";
				$comicNavigation .=		"</a>";
				$comicNavigation .= "</li>";
			}
			if($latestId!=$curId)
			{
				$comicNavigation .=	"<li class=\"navLatest\">";
				$comicNavigation .=		"<a href=\"index.php?id=";
				$comicNavigation .= 	$latestId;
				$comicNavigation .= 	"\">";
				$comicNavigation .=			"<img src=\"pictures/doubleArrowRight.png\" alt=\"Go to latest strip\">";
				$comicNavigation .=		"</a>";
				$comicNavigation .= "</li>";
				$hasNext = true;
			}
			$comicNavigation .= "</ul>";
			echo $comicNavigation;
			if($hasNext)
			{
				echo "<a href=\"index.php?id=".$nextId."\">";
			}
			echo "<img id=\"comicPicture\" src=\"".$curPath."\" alt=\"Comicstrip\">";
			if($hasNext)
			{
				echo "</a>";
			}
			echo $comicNavigation;
		}
		
		
	}