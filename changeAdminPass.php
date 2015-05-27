<?php
	require_once ('authHandler.php');
	if(isAuthorized(ADMIN))
	{
		if(isset($_POST['changeAdminPass'],$_POST['newPass'],$_POST['newPassConfirm'], $_POST['oldPass']))
		{
			if($_POST['newPass'] == $_POST['newPassConfirm'])
			{
				changeAdminPass($_SESSION['username'], $_POST['oldPass'], $_POST['newPass']);
			}
			else
			{
				echo "<p>Passwort konnte nicht bestätigt werden</p>";
			}
		}
		
		
		
		
		
		echo '	<script type="text/javascript">
					function confirmPass()
				{
					newPass = document.getElementById("newPass").value;
					newPassConfirm = document.getElementById("newPassConfirm").value;
					if(newPass == newPassConfirm && newPass != "")
					{
						document.getElementById("checkMark").innerHTML = "<img src=\"pictures/Checkmark.png\"/>";
						return true;
					}
					else
					{
						document.getElementById("checkMark").innerHTML = "<img src=\"pictures/redCross.png\"/>";
						return false;
					}
				}
					
				</script>
				<div id="checkMark"></div>
				<form id="uploadForm" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'?selection='.$_GET['selection'].'" method="POST" onsubmit="return confirmPass();">
					<fieldset>
						<input id="newPass" type="password" name="newPass" placeholder="Neues Passwort" onInput="confirmPass()"/>
						<input id="newPassConfirm" type="password" name="newPassConfirm" placeholder="Best&auml;tige neues Passwort" onInput="confirmPass()"/>
						<input type="password" name="oldPass" placeholder="Gib altes Passwort ein"/>
					</fieldset>
					<input id="submitButton" type="submit" name="changeAdminPass" value="Change Password" />
				</form>';
	}
?>