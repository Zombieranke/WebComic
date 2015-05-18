<?php
	define("ANON",0);
	define("USER",1);
	define("ADMIN",2);	


	function isAuthorized($reqPermLevel)
	{
		
		return true;
		//return $_SESSION['permLevel']>=$reqPermLevel;
	}

	function authorizeUser($username,$password)
	{
		
	}

?>