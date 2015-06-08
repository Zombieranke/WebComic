<?php	
	echo '<div id ="installWelcome">Webomic </br> Installation...</div>';
	
	echo 	'<form action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="POST">
				<button id="installButton" type="submit" name="progress" value="progress">Proceed</button>
			</form>';
?>