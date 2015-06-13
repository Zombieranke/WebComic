<?php
	if( defined('PasswordReset') )
	{
		echo '<div id="resetMessage">Password reset mail has been sent</div>';
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