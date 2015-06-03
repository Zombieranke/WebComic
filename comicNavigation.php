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
	
	if(isset($_POST['createComment']) && isset($_POST['commentContent']))
	{
		$latestId = getLatestId();
		$curId = isset($_GET['id']) ? $_GET['id'] : $latestId;
		
		commentStrip($_POST['commentContent'],$curId);
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
				$comicNavigation .=			"<img src=\"pictures/greenArrows/doubleArrowLeft.png\" alt=\"Go to oldest strip\">"; 
				$comicNavigation .=		"</a>";
				$comicNavigation .= "</li>";
				$hasPrevious = true;
			}
			else
			{
				$comicNavigation .=	"<li class=\"blank\"> <img src=\"pictures/blank.png\" alt=\"placeholder\"> </li>";
			}
			
			if(!empty($previousId))
			{
				$comicNavigation .=	"<li class=\"navPrevious\">";
				$comicNavigation .=		"<a href=\"index.php?id=";
				$comicNavigation .= 	$previousId;
				$comicNavigation .= 	"\">";
				$comicNavigation .=			"<img src=\"pictures/greenArrows/singleArrowLeft.png\" alt=\"Go to previous strip\">";
				$comicNavigation .=		"</a>";
				$comicNavigation .= "</li>";
			}
			else
			{
				$comicNavigation .=	"<li class=\"blank\"> <img src=\"pictures/blank.png\" alt=\"placeholder\"> </li>";
			}
			
			if(!empty($randomId))
			{
				$comicNavigation .=	"<li class=\"navRandom\">";
				$comicNavigation .=		"<a href=\"index.php?id=";
				$comicNavigation .= 	$randomId;
				$comicNavigation .= 	"\">";
				$comicNavigation .=			"<img src=\"pictures/greenArrows/random.png\" alt=\"Go to random strip\">";
				$comicNavigation .=		"</a>";
				$comicNavigation .= "</li>";
			}
			if(!empty($nextId))
			{
				$comicNavigation .=	"<li class=\"navNext\">";
				$comicNavigation .=		"<a href=\"index.php?id=";
				$comicNavigation .= 	$nextId;
				$comicNavigation .= 	"\">";
				$comicNavigation .=			"<img src=\"pictures/greenArrows/singleArrowRight.png\" alt=\"Go to next strip\">";
				$comicNavigation .=		"</a>";
				$comicNavigation .= "</li>";
			}
			else
			{
				$comicNavigation .=	"<li class=\"blank\"> <img src=\"pictures/blank.png\" alt=\"placeholder\"> </li>";
			}
			
			if($latestId!=$curId)
			{
				$comicNavigation .=	"<li class=\"navLatest\">";
				$comicNavigation .=		"<a href=\"index.php?id=";
				$comicNavigation .= 	$latestId;
				$comicNavigation .= 	"\">";
				$comicNavigation .=			"<img src=\"pictures/greenArrows/doubleArrowRight.png\" alt=\"Go to latest strip\">";
				$comicNavigation .=		"</a>";
				$comicNavigation .= "</li>";
				$hasNext = true;
			}
			else
			{
				$comicNavigation .=	"<li class=\"blank\"> <img src=\"pictures/blank.png\" alt=\"placeholder\"> </li>";
			}
			$comicNavigation .= "</ul>";
			
			echo $comicNavigation;
			
			if($hasNext) //If there is a strip after this one, the current strip turns into a clickable image which leads to the next strip
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
			
			echo '<script type="text/javascript">
					body = document.getElementsByTagName("body")[0];
					body.addEventListener("keyup",function(e){navigate(e)});
			
					
					function navigate(event)
					{

						switch(event.keyCode)
						{
						case 37:';
							echo empty($previousId) ? '' : 'location.search="id='.$previousId.'";';
							echo 'break; 
						case 39:';
							echo empty($nextId) ? '' : 'location.search="id='.$nextId.'";';
							echo 'break;
						default:
							break;
						}
					}
					
					</script>';
			
			if(isAuthorized(ADMIN) || isAuthorized(USER))
			{
				$commentForm  = "<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."?id=".$curId."\" method=\"POST\" id=\"commentForm\">";
				$commentForm .=		"<fieldset>";
				$commentForm .=			"<input type=\"text\" name=\"commentContent\" placeholder=\"Write your comment here\"/>";
				$commentForm .=			"<input type=\"submit\" name=\"createComment\" value=\"Share\"/>";
				$commentForm .=		"</fieldset>";
				$commentForm .=	 "</form>";
				
				echo $commentForm;
			}
			
			$offset = 0;
			
			$commentArray = getComments($curId,$offset);
			
			$i = 0;
			
			for($i=0; $i<20; $i++)
			{
				if(!empty($commentArray[$i]['username']))
				{
					echo createCommentDiv($commentArray[$i]['username'],$commentArray[$i]['avatar'],$commentArray[$i]['timestamp'],$commentArray[$i]['comment'],$commentArray[$i]['adminflag']);
				}
				
			}
			
			

		}
	}
	
	
	
	
	
	