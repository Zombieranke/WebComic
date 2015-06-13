<?php
	if(isset($_GET['resetKey']) && !isset($_GET['resetConfirm']))
	{
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
					else if(newPassConfirm !="")
					{
						document.getElementById("checkMark").innerHTML = "<img src=\"pictures/redCross.png\"/>";
						return false;
					}
				}
			
				</script>';
		
		
		echo 	'<form action="index.php?resetConfirm" method="POST" class="loginForm">'
			. 		'<fieldset>'
			. 			'<div class="loginIcon"><img src="pictures/profile-icon.png" alt="Profile-Icon for the username"></div>'
			. 			'<input type="text" name="username" placeholder="Username to confirm"/>'
			. 			'<input type="password" name="newPass" placeholder="New password" onInput="confirmPass()"/>'
			. 			'<input type="password" name="newPassConfirm" placeholder="Confirm password" onInput="confirmPass()"/>'
			.			'<input type="hidden" name="resetKey" value='.$_GET['resetKey'].'/>'
			. 			'<input type="submit" name="login" value="Reset Password" id="loginButton"/>'
			. 		'</fieldset>'
			.	'</form>';
	}
	
	if(isset($_GET['resetConfirm']))
	{
		if(isset($resetError))
		{
			switch($resetError)
			{
				case 0:
					echo '<p id="changeStatus">Password successfully changed</p>';
					break;
					
					
				case 1:
					echo '<p id="changeStatus">No match found for reset key and username</p>';
					break;
				
				case 2:
					echo '<p id="changeStatus">Passwords did not match</p>';
					break;
					
				default:
					break;
					
			}
		}
	}