<?php
	require_once ('stripFunctions.php');
	require_once ('authHandler.php');
	
	if(isset($_POST['delete']))
	{
		if(is_numeric($_POST['delete']))
		{
			deleteStrip($_POST['delete']);
		}
		else
		{
			echo "<p>Ungültige Parameter</p>";
		}
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
			$hasPrevious = false;
		
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
				$hasPrevious = true;
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
			
			if(isAuthorized(ADMIN))
			{
				if($hasPrevious)
				{
					echo "<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."?id=".$previousId."\" method=\"POST\">";
				}
				elseif($hasNext)
				{
					echo "<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."?id=".$nextId."\" method=\"POST\">";
				}
				else
				{
					echo "<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."\" method=\"POST\">";
				}
				echo "<button type=\"submit\" name=\"delete\" value=\"".$curId."\">Delete this strip</button>";
				echo "</form>";
			}
		}
		
		
	}