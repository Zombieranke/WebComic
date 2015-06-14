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
	
	if(isset($_POST['favourite']))
	{
		$latestId = getLatestId();
		$curId = isset($_GET['id']) ? $_GET['id'] : $latestId;
		
		favouriteStrip($curId);
	}
	
	if(isset($_POST['unfavourite']))
	{
		$latestId = getLatestId();
		$curId = isset($_GET['id']) ? $_GET['id'] : $latestId;
	
		unfavouriteStrip($curId);
	}
	
	if(isset($_POST['createComment']) && isset($_POST['commentContent']))
	{
		$latestId = getLatestId();
		$curId = isset($_GET['id']) ? $_GET['id'] : $latestId;
		if(!empty($_POST['commentContent']))
		{
			commentStrip($_POST['commentContent'],$curId);
		}
	}
	
	if(isset($_POST['deleteComment']) && isAuthorized(ADMIN))
	{
		deleteComment($_POST['deleteComment']);
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
			$maxRand=50;
			$count = 0;
			$nextId = getNextId($curId);
			$previousId = getPreviousId($curId);
			do
			{
				$randomId = getRandomId();
				$count++;
			}
			while($randomId==$curId && $count < $maxRand);
			$firstId = getFirstId();
			$hasNext=false;
			$hasPrevious = false;
		
			$comicNavigation =	"<a Name=\"comic\"></a><ul class=\"comic_navigation\">";
			
			if($firstId!=$curId)
			{
				$comicNavigation .=	"<li class=\"navFirst\">";
				$comicNavigation .=		"<a href=\"index.php?id=";
				$comicNavigation .= 	$firstId;
				$comicNavigation .= 	"#comic\">";
				$comicNavigation .=			"<img src=\"pictures/redArrows/doubleArrowLeft.png\" alt=\"Go to oldest strip\">"; 
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
				$comicNavigation .= 	"#comic\">";
				$comicNavigation .=			"<img src=\"pictures/redArrows/singleArrowLeft.png\" alt=\"Go to previous strip\">";
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
				$comicNavigation .= 	"#comic\">";
				$comicNavigation .=			"<img src=\"pictures/redArrows/random_calibri.png\" alt=\"Go to random strip\">";
				$comicNavigation .=		"</a>";
				$comicNavigation .= "</li>";
			}
			if(!empty($nextId))
			{
				$comicNavigation .=	"<li class=\"navNext\">";
				$comicNavigation .=		"<a href=\"index.php?id=";
				$comicNavigation .= 	$nextId;
				$comicNavigation .= 	"#comic\">";
				$comicNavigation .=			"<img src=\"pictures/redArrows/singleArrowRight.png\" alt=\"Go to next strip\">";
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
				$comicNavigation .= 	"#comic\">";
				$comicNavigation .=			"<img src=\"pictures/redArrows/doubleArrowRight.png\" alt=\"Go to latest strip\">";
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
			
			getFavouriteDiv($curId);
			
			echo getStripTitle($curId);
			
			if($hasNext) //If there is a strip after this one, the current strip turns into a clickable image which leads to the next strip
			{
				echo "<a href=\"index.php?id=".$nextId."#comic\">";
			}
			
			echo "<img id=\"comicPicture\" src=\"".$curPath."\" alt=\"Comicstrip\">";
			
			if($hasNext)
			{
				echo "</a>";
			}
			
			$annotation = getAnnotation($curId);
			
			echo "<p id=stripAnnotation>".$annotation."</p>";
			
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
				echo "<button id=\"stripDeleteButton\" type=\"submit\" name=\"delete\" value=\"".$curId."\"><img src=\"pictures/redCross.png\" alt=\"Delete Strip\">Delete this strip</button>";
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
							echo empty($previousId) ? '' : 'location.hash="#comic";location.search="id='.$previousId.'"; ';
							echo 'break; 
						case 39:';
							echo empty($nextId) ? '' : 'location.hash="#comic";location.search="id='.$nextId.'";';
							echo 'break;
						default:
							break;
						}
					}
					
					</script>';
			
			if(isset($_SESSION['user_name']))
			{
				$commentForm  = "<form id=\"commentForm\" action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."?id=".$curId."\" method=\"POST\" >";
				$commentForm .=		"<fieldset>";
				$commentForm .=			"<textarea name=\"commentContent\" placeholder=\"Write your comment here\" rows=\"3\" cols=\"50\"/></textarea>";
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
					echo createCommentDiv($curId,$commentArray[$i]['commentId'],$commentArray[$i]['username'],$commentArray[$i]['avatar'],$commentArray[$i]['timestamp'],$commentArray[$i]['comment'],$commentArray[$i]['adminflag']);
				}
				
			}
			
			

		}
	}
	
	
	
	
	
	