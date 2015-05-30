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
	
	
	
	$userCreateStmt = $connection->prepare  //permaban is now realised by setting the timestamp to the year 2100
	(
		"CREATE TABLE IF NOT EXISTS user
		(
			user_id INT(11) AUTO_INCREMENT PRIMARY KEY,
			username VARCHAR(256) UNIQUE NOT NULL,
			password VARCHAR(256) NOT NULL,
			email VARCHAR(256) NOT NULL,
			avatar VARCHAR(256),
			adminflag BOOLEAN NOT NULL,
			timestamp TIMESTAMP,			
			suspendflag BOOLEAN NOT NULL
		)"
	);
	
	$userCreateStmt->execute();
	
	if($userCreateStmt->errno != 0)
	{
		die("User creation failed: ".$userCreateStmt->error);
	}
	
	$userCreateStmt->free_result();
	$userCreateStmt->close();
	
	
	
	
	$webcomicCreateStmt = $connection->prepare
	(
		"CREATE TABLE IF NOT EXISTS webcomic
		(
			webcomic_id INT(11) AUTO_INCREMENT PRIMARY KEY,
			logo VARCHAR(256),
			css VARCHAR(256),
			titel VARCHAR(256),
			fk_user_id INT(11),
			FOREIGN KEY(fk_user_id) REFERENCES user(user_id)
		)"
	);
	
	$webcomicCreateStmt->execute();
	
	if($webcomicCreateStmt->errno != 0)
	{
		die("Webcomic creation failed: ".$webcomicCreateStmt->error);
	}
	
	$webcomicCreateStmt->free_result();
	$webcomicCreateStmt->close();
	
	
	
	$stripCreateStmt = $connection->prepare
	(
		"CREATE TABLE IF NOT EXISTS strip
		(
			strip_id INT(11) AUTO_INCREMENT PRIMARY KEY,
			name VARCHAR(256),
			annotation VARCHAR(512),
			data VARCHAR(256) NOT NULL,
			releasedate TIMESTAMP,
			fk_webcomic_id INT(11),
			FOREIGN KEY(fk_webcomic_id) REFERENCES webcomic(webcomic_id)
		)"
	);
	
	$stripCreateStmt->execute();
	
	if($stripCreateStmt->errno != 0)
	{
		die("Strips creation failed: ".$stripCreateStmt->error);
	}
	
	$stripCreateStmt->free_result();
	$stripCreateStmt->close();
	
	
	$faveStripCreateStmt = $connection->prepare
	(
		"CREATE TABLE IF NOT EXISTS faveStrip
		(
			fk_user_id INT(11) NOT NULL,
			fk_strip_Id INT(11) NOT NULL,
			PRIMARY KEY(fk_strip_id,fk_user_id),
			FOREIGN KEY(fk_strip_id) REFERENCES strip(strip_id),
			FOREIGN KEY(fk_user_id) REFERENCES user(user_id)
		)"
	);
	
	$faveStripCreateStmt->execute();
	
	if($faveStripCreateStmt->errno != 0)
	{
		die("Fave Strips creation failed: ".$faveStripCreateStmt->error);
	}
	
	$faveStripCreateStmt->free_result();
	$faveStripCreateStmt->close();
	
	$commentStripCreateStmt = $connection->prepare
	(
		"CREATE TABLE IF NOT EXISTS commentStrip
		(
			fk_user_id INT(11) NOT NULL,
			fk_strip_Id INT(11) NOT NULL,
			comment VARCHAR(512) NOT NULL,
			timestamp TIMESTAMP NOT NULL,
			PRIMARY KEY(fk_strip_id,fk_user_id),
			FOREIGN KEY(fk_strip_id) REFERENCES strip(strip_id),
			FOREIGN KEY(fk_user_id) REFERENCES user(user_id)
		)"
	);
	
	$commentStripCreateStmt->execute();
	
	if($commentStripCreateStmt->errno != 0)
	{
		die("Comment Strips creation failed: ".$commentStripCreateStmt->error);
	}
	
	$commentStripCreateStmt->free_result();
	$commentStripCreateStmt->close();
	
	$connection->close();
?>