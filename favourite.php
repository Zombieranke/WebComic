<?php
	require('connDetails.php');
	
	$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
	
	if($connection->errno != 0)
	{
		die("Database connection failed: ".$connection->connect_error);
	}
	
	$stmt = $connection->prepare("SELECT user_id FROM user WHERE username = ?");
	$stmt->bind_param('s', $_SESSION['user_name']);
	$stmt->bind_result($userId);
	$stmt->execute();
	$stmt->fetch();
	
	$stmt->free_result();
	
	$stmt = $connection->prepare("SELECT fk_strip_id,stripname FROM faveStrip JOIN strip ON fk_strip_id = strip_id WHERE fk_user_id = ?");
	$stmt->bind_param('i', $userId);
	$stmt->bind_result($stripId, $stripName);
	$stmt->execute();
	
	$favouriteArray = array();
	
	$i= 0;
	
	while($stmt->fetch())
	{
		$favouriteArray[$i]['stripId'] = $stripId;
		$favouriteArray[$i]['stripName'] = $stripName;
		$i++;
	}
	
	$stmt->free_result();
	$connection->close();
	
	echo "<ul id=favouriteList>";
	
	for($i=0; $i<40; $i++)
	{
		if(!empty($favouriteArray[$i]))
		{
			echo createFavouriteEntry($favouriteArray[$i]['stripId'],$favouriteArray[$i]['stripName']);
		}
		
	}
	
	echo "</ul>";
	
	
	function createFavouriteEntry($stripId, $stripName)
	{
		$outputString  = "<div>";
		$outputString .= 	"<li><a class=\"favouriteLink\" href=\"index.php?id=".$stripId."#comic\">".$stripName."</a></li>";
		$outputString .= "<div>";
		return $outputString;
	}

?>
