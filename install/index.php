<?php
	session_start();
		
	if(!defined("includeInstall"))
	{
		define("includeInstall", TRUE);
	}
	
	if(!isset($_SESSION['installStep']))
	{
		$_SESSION['installStep'] = 0;
	}
	
	if(isset($_POST['progress']))
	{
		$_SESSION['installStep']++;
	}
	
	if(isset($_POST['back']))
	{
		$_SESSION['installStep']--;
	}
	
	echo '<!DOCTYPE html>
			<html>
				<head>
					<link type="text/css" rel="stylesheet" href="style.css">
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
					<title>
						W3BC0M1C
					</title>
				</head>
	
				<body>';
	
	echo '<div id="installHeader"></div>';
	
	if(file_exists("install.lock"))
	{
		echo 	'<h1 id="installHeadline">Webcomic already installed!</h1>';
	}
	else 
	{
		switch($_SESSION['installStep'])
		{
			case 0:
				include('installWelcome.php');
				break;
				
			case 1:
				include('installDatabase.php');
				break;
				
			case 2:
				include('installAdmin.php');
				break;
				
			case 3:
				include('installWebcomic.php');
				break;
				
			case 4:
				include('installFinished.php');
				break;
				
			case 5:
				include('installCleanup.php');
				break;
				
			default:
				break;
		}
	}
	echo '</body>';
?>