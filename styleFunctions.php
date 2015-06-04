<?php

	if(!defined('includeConnDetails'))
	{
		define('includeConnDetails', TRUE);
	}
	
	function getAppliedCss($wId)
	{
		require('connDetails.php');
		
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
		
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
		
		$stmt = $connection->prepare("SELECT css FROM webcomic WHERE webcomic_id = ?");
		$stmt->bind_param("i", $wId);
		$stmt->execute();
		$stmt->bind_result($cssString);
		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();
		$connection->close();
		
		if(!empty($cssString))
		{
			return $cssString;
		}
		else
		{
			return false;
		}
	}
	
	function getAppliedLogo($wId)
	{
		require('connDetails.php');
	
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
	
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
	
		$stmt = $connection->prepare("SELECT logo FROM webcomic WHERE webcomic_id = ?");
		$stmt->bind_param("i", $wId);
		$stmt->execute();
		$stmt->bind_result($logoString);
		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();
		$connection->close();
	
		if(!empty($logoString))
		{
			return $logoString;
		}
		else
		{
			return false;
		}
	}