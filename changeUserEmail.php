<?php
  
	require_once ('authHandler.php');
	
	if(isAuthorized(USER))
	{
		if(isset($_POST['changeUserPass'],$_POST['newEmail'],$_POST['passForEmail']))
		{
			changeEmail($_SESSION['user_name'], $_POST['passForEmail'], $_POST['newEmail']);
		}
		

		
		
		echo '	<form id="newEmailForm" action="index.php?profile&selection='.$_GET['selection'].'" method="POST">
					<fieldset>
						<input id="newEmail" class="newEmail" type="password" name="newEmail" placeholder="New Email"/> </br>
						<input class="newEmail" type="password" name="passForEmail" placeholder="Enter your password here"/> </br>
						<input id="newEmailSubmit" type="submit" name="changeUserPass" value="Change Password" />
					</fieldset>
				</form>';
	}

?>