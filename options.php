<?php
	require_once ('authHandler.php');
	if(isAuthorized(ADMIN)){
		echo "<div id=\"options\">";
		echo 	"<div id=\"optionsListDiv\">";
		echo		"<ul id=\"optionsList\">";
		echo 			"<li><a href=\"index.php\">Back to Comic</a></li>";
		echo			"<li><a href=\"admin.php?selection=uploadStrip\">Upload Strip</a></li>";
		echo			"<li><a href=\"admin.php?selection=editStrip\">Edit Strip</a></li>";
		echo			"<li><a href=\"admin.php?selection=userOverview\">User Overview</a></li>";
		echo			"<li><a href=\"admin.php?selection=changeLogo\">Change Logo</a></li>";
		echo			"<li><a href=\"admin.php?selection=changeCss\">Change Stylesheet</a></li>";
		echo			"<li><a href=\"admin.php?selection=changePassword\">Change Password</a></li>";
		echo			"<li><a href=\"admin.php?selection=changeEmail\">Change Email</a></li>";
		echo			"<li><a href=\"admin.php?selection=changeAvatar\">Change Avatar</a></li>";
		echo 			"<li><a href=\"index.php?logout\">Logout</a></li>";
		echo		"</ul>";	
		echo	"</div>";
		
		
		echo "<div id=\"activeApplication\">";
			if(isset($_GET['selection']))
			{
				if($_GET['selection'] == "uploadStrip")
				{
					include "uploadModule.php";
				}
				else if($_GET['selection'] == "editStrip")
				{
					include "editStripsModule.php";
				}
				else if($_GET['selection'] == "userOverview")
				{
					include "userOverview.php";
				}
				else if(($_GET['selection'] == "changeLogo"))
				{
					include "logoApplication.php";
				}
				else if($_GET['selection'] == "changePassword")
				{
					include "changeAdminPass.php";
				}
				else if($_GET['selection'] == "changeEmail")
				{
					include "changeUserEmail.php";
				}
				else if($_GET['selection'] == "changeAvatar")
				{
					include "changeUserAvatar.php";
				}
				else if($_GET['selection'] == "changeCss")
				{
					include "cssChanger.php";
				}
				
			}
			
		echo "</div>";
		echo "</div>";
	}
	
?>
	
			