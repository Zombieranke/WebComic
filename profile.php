<?php
	
	require_once ('authHandler.php');
	if(isAuthorized(USER)){
	echo "<div id=\"options\">";
	echo 	"<div id=\"optionsListDiv\">";
	echo		"<ul id=\"optionsList\">";
	//echo			"<li><a href=\"admin.php?selection=editStrip\">Edit Strip</a></li>";
	echo			"<li><a href=\"index.php?profile&selection=changePassword\">Change Password</a></li>";
	echo			"<li><a href=\"index.php?profile&selection=changeEmail\">Change E-Mail adress</a></li>";
	echo		"</ul>";
	echo	"</div>";


	echo "<div id=\"activeApplication\">";
	if(isset($_GET['selection']))
	{
		if($_GET['selection'] == "changePassword")
		{
			include "changeUserPass.php";
		}
		else if($_GET['selection'] == "changeEmail")
		{
			include "changeUserEmail.php";
		}

	}
		
	echo "</div>";
}

	
	
	
	
?>