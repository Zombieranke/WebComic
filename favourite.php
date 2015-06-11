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
	
	$stmt = $connection->prepare("SELECT fk_strip_Id FROM faveStrip WHERE fk_user_id = ?");
	$stmt->bind_param('i', $userId);
	$stmt->bind_result($stripId);
	$stmt->execute();
	
	$favouriteArray = array();
	
	$i= 0;
	
	while($stmt->fetch())
	{
		$favouriteArray[$i] = $stripId;
		$i++;
	}
	
	$stmt->free_result();
	$connection->close();
	
	echo "<ul>";
	
	for($i=0; $i<40; $i++)
	{
		if(!empty($favouriteArray[$i]))
		{
			echo createFavouriteEntry($favouriteArray[$i]);
		}
		
	}
	
	echo "</ul>";
	
	
	function createFavouriteEntry($stripId)
	{
		$outputString  = "<div>";
		$outputString .= 	"<li><a class=\"favouriteLink\" href=\"index.php?id=".$stripId."#comic\">".$stripId."</a></li>";
		$outputString .= "<div>";
		return $outputString;
	}

?>
