<?php
	session_start();
	
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
			include('installCleanup.php');
			break;
			
		default:
			break;
	}
?>