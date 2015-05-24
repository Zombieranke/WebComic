<?php 
	echo "<div id=\"options\">";
	echo 	"<div id=\"optionsListDiv\">";
	echo		"<ul id=\"optionsList\">";
	echo			"<li><a href=\"admin.php?selection=changeLogo\">Change Logo</a></li>";
	echo			"<li><a href=\"admin.php?selection=uploadStrip\">Upload Strip</a></li>";
	echo			"<li><a href=\"admin.php?selection=editStrip\">Edit Strip</a></li>";
	echo			"<li><a href=\"admin.php?selection=changePassword\">Change Password</a></li>";
	echo		"</ul>";	
	echo	"</div>";
	
	
	echo "<div id=\"activeApplication\">";
		if(isset($_GET['selection']))
		{
			if(($_GET['selection'] == "changeLogo"))
			{
				include "logoApplication.php";
			}
			else if($_GET['selection'] == "uploadStrip")
			{
				include "uploadModule.php";
			}
			else if($_GET['selection'] == "editStrip")
			{
				include "editApplication.php";
			}
			else if($_GET['selection'] == "changePassword")
			{
				include "profileApplication.php";
			}
			
		}
		
	echo "</div>";
	
?>
	
			