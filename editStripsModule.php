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
				echo 		"<p>Release Date:</br>".$strip['date']."</p>";
				echo 		"<p>".$strip['filename']."</p>";
				echo		"<div class=\"editStripOrderDiv\">";
				echo 		"<form class=\"editStripForwardForm\" action=\"".htmlspecialchars($_SERVER['PHP_SELF']).'?selection='.$_GET['selection']."\" method=\"POST\">";
				echo 			"<button  class=\"editStripForwardButton\" type=\"submit\" name=\"stripForward\" value=\"".$strip['id']."\"><img src=\"pictures/redArrows/rearrangeOrderArrowLeft.png\" alt=\"Move strip forward\"/></button>";
				echo 		"</form>";
				echo		"<div class=\"editStripOrder\">Rearrange</br>Order</div>";
				echo 		"<form class=\"editStripBackwardForm\" action=\"".htmlspecialchars($_SERVER['PHP_SELF']).'?selection='.$_GET['selection']."\" method=\"POST\">";
				echo 			"<button class=\"editStripBackwardButton\" type=\"submit\" name=\"stripBackward\" value=\"".$strip['id']."\"><img src=\"pictures/redArrows/rearrangeOrderArrowRight.png\" alt=\"Move strip backward\"/></button>";
				echo 		"</form>";
				echo		"</div>";
				echo 		"<form class=\"editStripDeleteForm\" action=\"".htmlspecialchars($_SERVER['PHP_SELF']).'?selection='.$_GET['selection']."\" method=\"POST\">";
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