<?php
	require_once('authHandler.php');
	require_once ('userFunctions.php');
	
	if(isAuthorized(ADMIN))
	{
	
		if(isset($_POST['delete']))
		{
			if(is_numeric($_POST['delete']))
			{
				deleteUser($_POST['delete']);
			}
			else
			{
				echo "<p>Request not valid</p>";
			}
		}
		
		if(isset($_POST['ban']))
		{
			if(is_numeric($_POST['ban']))
			{
				banUser($_POST['ban']);
			}
			else
			{
				echo "<p>Request not valid</p>";
			}
		}
		
		if(isset($_POST['unban']))
		{
			if(is_numeric($_POST['unban']))
			{
				unbanUser($_POST['unban']);
			}
			else
			{
				echo "<p>Request not valid</p>";
			}
		}
		
		if(isset($_POST['suspend'],$_POST['suspendtime']))
		{
			if(is_numeric($_POST['suspend']) && is_numeric($_POST['suspendtime']))
			{
				suspendUser($_POST['suspend'], $_POST['suspendtime']);
			}
			else
			{
				echo "<p>Request not valid</p>";
			}
		}
	
		$users = getUsers();
	
		if($users)
		{
			echo "<div id=\"userOverview\">";
			echo 	"<fieldset>";
			echo 		"<h1> Manage your users </h1>";
				
			foreach($users as $user)
			{
				if(!empty($user['avatar']))
				{
					$avatar = $user['avatar'];
				}
				else 
				{
					$avatar = "avatars/standardAvatar.jpg";
				}
				echo 	"<div class=\"manageUser\">";
				echo		"<fieldset>";
				echo 		"<img class=\"manageUserAvatar\" src=\"avatars/".$avatar."\"/>";
				echo 	"<div class=\"manageUserData\">";
				echo 		"<h2>".$user['name']."</h2>";
				
				echo 		"<p>Email: ".$user['email']."</p>";
				
				echo 		"<form action=\"".htmlspecialchars($_SERVER['PHP_SELF']).'?selection='.$_GET['selection']."\" method=\"POST\">";
				echo 			"<label>Suspend user (days): </label>";
				echo 			"<input type=\"number\" class=\"manageUserSuspendTime\" name=\"suspendtime\"/>";
				echo 			"<button class=\"manageUserSuspendButton\" type=\"submit\" name=\"suspend\" value=\"".$user['id']."\">Suspend this user</button>";
				echo 		"</form>";
				
				if(!isSuspended($user['id']))
				{
					echo 		"<form action=\"".htmlspecialchars($_SERVER['PHP_SELF']).'?selection='.$_GET['selection']."\" method=\"POST\">";
					echo 			"<button class=\"manageUserBanButton\" type=\"submit\" name=\"ban\" value=\"".$user['id']."\">Ban this user</button>";
					echo			"<div class=\"userBanInfo\">You may unban at any time</div>";
					echo 		"</form>";
				}
				else
				{
					echo 		"<form action=\"".htmlspecialchars($_SERVER['PHP_SELF']).'?selection='.$_GET['selection']."\" method=\"POST\">";
					echo 			"<button class=\"manageUserUnbanButton\" type=\"submit\" name=\"unban\" value=\"".$user['id']."\">Unban this user</button>";
					echo 		"</form>";
				}
				
				echo 		"<form action=\"".htmlspecialchars($_SERVER['PHP_SELF']).'?selection='.$_GET['selection']."\" method=\"POST\">";
				echo 			"<button class=\"manageUserDeleteButton\" type=\"submit\" name=\"delete\" value=\"".$user['id']."\">Delete this user</button>";
				echo			"<div class=\"userDeleteInfo\">This is irreversible!</div>";
				echo 		"</form>";
				echo		"</div>";
				echo		"</fieldset>";
				echo 	"</div>";
			}
			echo 	"</fieldset>";
			echo "</div>";
		}
		else
		{
			echo "<p>No user found</p>";
		}
	}
	?>