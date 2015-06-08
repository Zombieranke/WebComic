<?php
	//handle input from before
	if(isset($_POST['progress']))
	{
		$error=true;
		
		
		
		
		
		if($error)
		{
			$_SESSION['error']= true;
			$_SESSION['errorCode'] = 1;
			$_SESSION['installStep']--;
			header("Location: ".htmlspecialchars($_SERVER['PHP_SELF']));
			die;
		}
	}