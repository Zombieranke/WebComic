<?php
	//For now delete only
	require_once('authHandler.php');
	require_once ('stripFunctions.php');
	
	if(isAuthorized(ADMIN))
	{
		
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
		
		if(isset($_POST['stripBackward']))
		{
			if(is_numeric($_POST['stripBackward']))
			{
				repositionStrip($_POST['stripBackward'], MODE_BACKWARD);
			}
			else
			{
				echo "<p>Ungültige Parameter</p>";
			}
		}
		
		if(isset($_POST['stripForward']))
		{
			if(is_numeric($_POST['stripForward']))
			{
				repositionStrip($_POST['stripForward'], MODE_FORWARD);
			}
			else
			{
				echo "<p>Ungültige Parameter</p>";
			}
		}
		
		$strips = getStrips();
		
		if($strips)
		{
			echo "<div id=\"editStripModule\">";
			echo 	"<fieldset>";
			echo 		"<h1> Edit your Strips </h1>";
			
			foreach($strips as $strip)
			{
				echo 	"<div class=\"editStrip\">";
				echo 		"<h2>".$strip['stripname']."</h2>";
				echo 		"<img class=\"editThumb\" src=\"".$strip['file']."\"/>";
				echo 		"<p>Release Date: ".$strip['date']."</p>";
				echo 		"<p>".$strip['filename']."</p>";
				echo 		"<form class=\"editStripForwardButton\" action=\"".htmlspecialchars($_SERVER['PHP_SELF']).'?selection='.$_GET['selection']."\" method=\"POST\">";
				echo 			"<button  type=\"submit\" name=\"stripForward\" value=\"".$strip['id']."\"><img src=\"pictures/redArrows/singleArrowLeft.png\" alt=\"Move strip forward\"/></button>";
				echo 		"</form>";
				echo 		"<form class=\"editStripBackwardButton\" action=\"".htmlspecialchars($_SERVER['PHP_SELF']).'?selection='.$_GET['selection']."\" method=\"POST\">";
				echo 			"<button type=\"submit\" name=\"stripBackward\" value=\"".$strip['id']."\"><img src=\"pictures/redArrows/singleArrowRight.png\" alt=\"Move strip backward\"/></button>";
				echo 		"</form>";
				echo 		"<form action=\"".htmlspecialchars($_SERVER['PHP_SELF']).'?selection='.$_GET['selection']."\" method=\"POST\">";
				echo 			"<button class=\"editStripDeleteButton\" type=\"submit\" name=\"delete\" value=\"".$strip['id']."\">Delete this strip</button>";
				echo 		"</form>";
				echo 	"</div>";
			}
			echo 	"</fieldset>";
			echo "</div>";
		}
		else
		{
			echo "<p>No strips found</p>";
		}
			
		
		
		
		
		
		
		
		
		
		
		
	}
?>