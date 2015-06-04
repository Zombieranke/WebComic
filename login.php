<form action="index.php" method="POST" class="loginForm">
	<fieldset>
		<div class="loginIcon"><img src="pictures/profile-icon.png" alt="Profile-Icon for the username"></div>
		<input type="text" name="username" placeholder="Username"/>
		<div class="loginIcon"><img src="pictures/lock-icon.png" alt="Lock-Icon for the password"></div>
		<input type="password" name="password" placeholder="Password"/>
		<a href="index.php?reset">Forgot your password?</a>
		<input type="submit" name="login" value="Login" id="loginButton"/>		
	</fieldset>
</form>