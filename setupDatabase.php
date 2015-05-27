<?php
	define("includeConnDetails",TRUE);
	
	require_once ('connDetails.php');
	
	$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword']);
	
	if($connection->errno != 0)
	{
		die("Database connection failed: ".$connection->connect_error);
	}
	
	$dbCreateStmt = $connection->prepare("CREATE DATABASE IF NOT EXISTS ".$database['dbName']);
	$dbCreateStmt->execute();
	
	if($dbCreateStmt->errno != 0)
	{
		die("Database creation failed: ".$dbCreateStmt->error);
	}
	
	$dbCreateStmt->free_result();
	$dbCreateStmt->close();
	
	$connection->select_db($database['dbName']);
	
	
	
	
	$adminCreateStmt = $connection->prepare
	(
		"CREATE TABLE IF NOT EXISTS admin
		(
			admin_id INT(11) AUTO_INCREMENT PRIMARY KEY,
			username VARCHAR(256) UNIQUE NOT NULL,
			password VARCHAR(256) NOT NULL,
			email VARCHAR(256)
		)"
	);
	
	$adminCreateStmt->execute();
	
	if($adminCreateStmt->errno != 0)
	{
		die("Admin creation failed: ".$adminCreateStmt->error);
	}
	
	$adminCreateStmt->free_result();
	$adminCreateStmt->close();
	
	
	
	
	
	$webcomicCreateStmt = $connection->prepare
	(
		"CREATE TABLE IF NOT EXISTS webcomic
		(
			webcomic_id INT(11) AUTO_INCREMENT PRIMARY KEY,
			logo VARCHAR(256),
			css VARCHAR(256)
			titel VARCHAR(256),
			fk_admin_id INT(11),
			FOREIGN KEY(fk_admin_id) REFERENCES admin(admin_id)
		)"
	);
	
	$webcomicCreateStmt->execute();
	
	if($webcomicCreateStmt->errno != 0)
	{
		die("Webcomic creation failed: ".$webcomicCreateStmt->error);
	}
	
	$webcomicCreateStmt->free_result();
	$webcomicCreateStmt->close();
	
	
	
	$userCreateStmt = $connection->prepare
	(
		"CREATE TABLE IF NOT EXISTS user
		(
			user_id INT(11) AUTO_INCREMENT PRIMARY KEY,
			username VARCHAR(256) UNIQUE NOT NULL,
			password VARCHAR(256) NOT NULL,
			email VARCHAR(256) NOT NULL,
			avatar VARCHAR(256),
			permaban BOOLEAN NOT NULL,
			timestamp TIMESTAMP,
			sperrflag BOOLEAN NOT NULL
		)"
	);
	
	$userCreateStmt->execute();
	
	if($userCreateStmt->errno != 0)
	{
		die("User creation failed: ".$userCreateStmt->error);
	}
	
	$userCreateStmt->free_result();
	$userCreateStmt->close();
	
	
	$stripsCreateStmt = $connection->prepare
	(
		"CREATE TABLE IF NOT EXISTS strips
		(
			strip_id INT(11) AUTO_INCREMENT PRIMARY KEY,
			name VARCHAR(256),
			bemerkung VARCHAR(512),
			datei VARCHAR(256) NOT NULL,
			veröffentlichungsdatum TIMESTAMP,
			fk_webcomic_id INT(11),
			FOREIGN KEY(fk_webcomic_id) REFERENCES webcomic(webcomic_id)
		)"
	);
	
	$stripsCreateStmt->execute();
	
	if($stripsCreateStmt->errno != 0)
	{
		die("Strips creation failed: ".$stripsCreateStmt->error);
	}
	
	$stripsCreateStmt->free_result();
	$stripsCreateStmt->close();
	
	
	$faveStripsCreateStmt = $connection->prepare
	(
		"CREATE TABLE IF NOT EXISTS faveStrips
		(
			fk_user_id INT(11) NOT NULL,
			fk_strip_Id INT(11) NOT NULL,
			PRIMARY KEY(fk_strip_id,fk_user_id),
			FOREIGN KEY(fk_strip_id) REFERENCES strips(strip_id),
			FOREIGN KEY(fk_user_id) REFERENCES user(user_id)
		)"
	);
	
	$faveStripsCreateStmt->execute();
	
	if($faveStripsCreateStmt->errno != 0)
	{
		die("Fave Strips creation failed: ".$faveStripsCreateStmt->error);
	}
	
	$faveStripsCreateStmt->free_result();
	$faveStripsCreateStmt->close();
	
	$commentStripsCreateStmt = $connection->prepare
	(
		"CREATE TABLE IF NOT EXISTS commentStrips
		(
			fk_user_id INT(11) NOT NULL,
			fk_strip_Id INT(11) NOT NULL,
			kommentar VARCHAR(512) NOT NULL,
			timestamp TIMESTAMP NOT NULL,
			PRIMARY KEY(fk_strip_id,fk_user_id),
			FOREIGN KEY(fk_strip_id) REFERENCES strips(strip_id),
			FOREIGN KEY(fk_user_id) REFERENCES user(user_id)
		)"
	);
	
	$commentStripsCreateStmt->execute();
	
	if($commentStripsCreateStmt->errno != 0)
	{
		die("Comment Strips creation failed: ".$commentStripsCreateStmt->error);
	}
	
	$commentStripsCreateStmt->free_result();
	$commentStripsCreateStmt->close();
	
	$connection->close();
?>