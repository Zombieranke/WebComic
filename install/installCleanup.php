<?php
	if(!defined('includeInstall'))
	{
		header('HTTP/1.0 403 Forbidden');
		die("It is forbidden to access this page directly");
	}
	//DELETE INSTALL
	
	$lockfile = fopen("install.lock","c");
	fwrite($lockfile, "Delete this file if you want to execute setup again");
	fclose($lockfile);

	$_SESSION['installStep'] = 0;
	header("Location: ..");
	die;
	
?>
	