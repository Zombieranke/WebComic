<?php
	require_once ('authHandler.php');
	if(isAuthorized(ADMIN))
	{
		if(isset($_POST['changeUserPass'],$_POST['newPass'],$_POST['newPassConfirm'], $_POST['oldPass']))
		{
			if($_POST['newPass'] == $_POST['newPassConfirm'])
			{
				changePassword($_SESSION['user_name'], $_POST['oldPass'], $_POST['newPass']);
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
				
				<form id="newPassForm" action="index.php?profile&'.$_GET['selection'].'" method="POST" onsubmit="return confirmPass();">
					<fieldset>
						<input id="newPass" class="newPass" type="password" name="newPass" placeholder="New password" onInput="confirmPass()"/> </br>
						<input id="newPassConfirm" class="newPass" type="password" name="newPassConfirm" placeholder="Confirm new password" onInput="confirmPass()"/>
						<div id="checkMark"></div> </br>
						<input class="newPass" type="password" name="oldPass" placeholder="Enter your old password here"/> </br>
						<input id="newPassSubmit" type="submit" name="changeUserPass" value="Change Password" />
					</fieldset>
				</form>';
	}
?>