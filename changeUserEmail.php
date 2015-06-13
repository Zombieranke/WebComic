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
						<p> Your current Email-adress is: <b>'.getEmail().'</b> </p>
						<input id="newEmail" class="newEmail" type="email" name="newEmail" placeholder="New Email"/> </br>
						<input class="newEmail" type="password" name="passForEmail" placeholder="Enter your password here"/> </br>
						<input id="newEmailSubmit" type="submit" name="changeUserEmail" value="Change Email" />
					</fieldset>
				</form>';
	}
	
	function getEmail()
	{
		require('connDetails.php');
		
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
		
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
		
		$stmt = $connection->prepare("SELECT email FROM user WHERE username = ?");
		$stmt->bind_param('s', $_SESSION['user_name']);
		$stmt->bind_result($email);
		$stmt->execute();
		$stmt->fetch();
		
		$stmt->free_result();
		
		return $email;
	}

?>