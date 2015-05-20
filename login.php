<form action="admin.php" method="POST" id="loginForm">
	<fieldset>
		<div class=loginIcon><img src="pictures/profile-icon.png" alt="Profile-Icon damit man daneben den Benutzernamen eingibt"></div>
		<input type="text" name="username" placeholder="Username"/>
		<div class=loginIcon><img src="pictures/lock-icon.png" alt="Schloss-Icon damit man daneben das Passwort eingibt"></div>
		<input type="password" name="password" placeholder="Password"/>
		<label>	Angemeldet bleiben<input type="checkbox" name="stillAlive" value="true"/></label>
		<p>Noch keinen Account? <a href="index.php?registration=true">Hier Registrieren</a> </p>
		<p><a href="index.php?forgotPassword=true">Passwort vergessen?</a></p>
		<input type="submit" name="login" value="Login" id="loginButton"/>
	</fieldset>
</form>