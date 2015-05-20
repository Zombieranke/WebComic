<!DOCTYPE html>
<head>
	<link type="text/css" rel="stylesheet" href="style.css">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<title>
		W3BC0M1C
	</title>
</head>

<body>
	<div id="wrapper">
		<div class="logo">
			<img src="pictures/logo.jpg" alt="Very cool Logo">
		</div>
		
		<?php 
			echo "<div id=\"options\">";
			echo 	"<div id=\"optionsListDiv\">";
			echo		"<ul id=\"optionsList\">";
			echo			"<li><a href=\"options.php?selection=changeLogo\">Change Logo</a></li>";
			echo			"<li><a href=\"options.php?selection=uploadStrip\">Upload Strip</a></li>";
			echo			"<li><a href=\"options.php?selection=editStrip\">Edit Strip</a></li>";
			echo			"<li><a href=\"options.php?selection=changePassword\">Change Password</a></li>";
			echo		"</ul>";	
			echo	"</div>";
			echo "</div>";
			
			echo "<div id=\"activeApplication\">";
				if(isset($_GET['selection']))
				{
					if(strcasecmp($_GET['selection'], "changeLogo"))
					{
						include "logoApplication.php";
					}
					else if(strcasecmp($_GET['selection'], "uploadStrip"))
					{
						include "uploadModule.php";
					}
					else if(strcasecmp($_GET['selection'], "editStrip"))
					{
						include "editApplication.php";
					}
					else if(strcasecmp($_GET['selection'], "changePassword"))
					{
						include "profileApplication.php";
					}	
					
				}
			
		?>
	</div>
</body>
				
			