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
				
				<form id="newPassForm" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'?selection='.$_GET['selection'].'" method="POST" onsubmit="return confirmPass();">
					<fieldset>
						<p> <input id="newPass" class="newPass" type="password" name="newPass" placeholder="New password" onInput="confirmPass()"/> </p>
						<p> <input id="newPassConfirm" class="newPass" type="password" name="newPassConfirm" placeholder="Confirm new password" onInput="confirmPass()"/> <div id="checkMark"></div> </p>
						<p> <input class="newPass" type="password" name="oldPass" placeholder="Enter your old password here"/> </p>
						<p> <input id="newPassSubmit" type="submit" name="changeAdminPass" value="Change Password" /> </p>
					</fieldset>
				</form>';
	}
?>