<?php
	//For now delete only
	require_once('authHandler.php');
	require_once ('stripFunctions.php');
	
	if(isAuthorized(ADMIN))
	{
		echo "	<head>
					<link type=\"text/css\" rel=\"stylesheet\" href=\"style.css\">
					<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"/>
					<title>
						W3BC0M1C
					</title>
				</head>";
		
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
		
		$strips = getStrips();
		echo "<h1> Edit your Strips </h1>";
		if($strips)
		{
			foreach($strips as $strip)
			{
				echo "<div class=\"strip\">";
				echo "<h2>".$strip['name']."</h2>";
				echo "<img class=\"editThumbs\" src=\"".$strip['file']."\"/>";
				echo "<p>Ver&ouml;ffentlichungsdatum: ".$strip['date']."</p>";
				echo "<form action=\"".htmlspecialchars($_SERVER['PHP_SELF']).'?selection='.$_GET['selection']."\" method=\"POST\">";
				echo "<button type=\"submit\" name=\"delete\" value=\"".$strip['id']."\">Delete this strip</button>";
				echo "</form>";
				echo "</div>";
			}
		}
		else
		{
			echo "<p>Keinen Comicstrip gefunden</p>";
		}
			
		
		
		
		
		
		
		
		
		
		
		
	}
?>