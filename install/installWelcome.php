<?php
	if(!defined('includeInstall'))
	{
		header('HTTP/1.0 403 Forbidden');
		die("It is forbidden to access this page directly");
	}
	echo '<div id ="installWelcome">Webcomic </br> Installation...</div>';
	
	echo 	'<form action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="POST">
				<button id="installButton" type="submit" name="progress" value="progress">Proceed</button>
			</form>';
?>