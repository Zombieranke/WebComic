<?php
if(isset($_POST['password']))
{
	echo "Der bcrypt hash zu  diesem Password lautet: ".password_hash($_POST['password'],PASSWORD_DEFAULT);
}
else 
{
	echo "Enter password to hash: ";
	echo "<form action=\"".htmlspecialchars($_SERVER['PHP_SELF'])."\" method=\"post\" enctype=\"multipart/form-data\">";
	echo "<input type=\"password\" name=\"password\"/>";
	echo "<input type=\"submit\" name=\"login\" value=\"Calculate hash\" id=\"loginButton\"/>";
	echo "</form>";
}
?>