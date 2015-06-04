<?php
	if( defined('PasswordReset') )
	{
		echo 'Password has been reset.';
		if( isset($resetkey) )
			echo 'Resetkey: ' . $resetkey;
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