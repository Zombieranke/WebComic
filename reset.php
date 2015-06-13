<?php
	if( defined('PasswordReset') )
	{
		echo '<p id="resetStatus">Password reset mail has been sent.</p>';
	}
	else
	{
		echo '<form action="index.php?reset" method="POST" class="loginForm">'
			. '<fieldset>'
			. '<div class="loginIcon"><img src="pictures/profile-icon.png" alt="Profile-Icon for the username"></div>'
			. '<input type="text" name="username" placeholder="Username"/>'
			. '<input type="submit" name="login" value="Reset Password" id="loginButton"/>'		
			. '</fieldset>'
			. '</form>';
	}
?>