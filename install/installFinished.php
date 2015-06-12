<?php
	if(!defined('includeInstall'))
	{
		header('HTTP/1.0 403 Forbidden');
		die("It is forbidden to access this page directly");
	}
	if(!defined('includeConnDetails'))
	{
		define('includeConnDetails', TRUE);
	}
	//handle input from before
	if(isset($_POST['progress']))
	{
		if(isset($_POST['comicName']))
		{
			$_SESSION['error']= false;
	
			if(empty($_POST['comicName']))
			{
				$_SESSION['error']= true;
				$_SESSION['errorCode']= 1;
			}
			else
			{
				require("../connDetails.php");
					
				$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
	
				if($connection->errno != 0)
				{
					die("Database connection failed: ".$connection->connect_error);
				}
				
				$stmt = $connection->prepare("SELECT COUNT(*) FROM webcomic");
				$stmt->execute();
				$stmt->bind_result($needUpdate);
				$stmt->fetch();
				$stmt->free_result();
				$stmt->close();
				
				if($needUpdate != 0)
				{
					$stmt = $connection->prepare("SELECT logo FROM webcomic");
					$stmt->execute();
					$stmt->bind_result($logo);
					$stmt->fetch();
					$stmt->free_result();
					$stmt->close();
					
					if(!empty($logo))
					{
						unlink(".".$logo);
					}
					$stmt = $connection->prepare("DELETE FROM webcomic");
					$stmt->execute();
					$stmt->fetch();
					$stmt->free_result();
					$stmt->close();	
				}
	
				$comicname = filter_var($_POST['comicName'],FILTER_SANITIZE_STRING);
	
				$sqlCommand = "INSERT INTO webcomic (title) VALUES (?)";
				$entry = $connection->prepare($sqlCommand);
				$entry->bind_param('s', $comicname);
	
				$entry->execute();
				
				$mywebcomic = $entry->insert_id;
					
				$entry->free_result();
				$entry->close();
	
				$connection->close();
				
				if(isset($_FILES['comicLogo']))
				{
					if(is_uploaded_file($_FILES['comicLogo']['tmp_name']))
					{
						$fileupload = $_FILES['comicLogo'];
					
						upload_logo($fileupload);
						store_logo($fileupload, $mywebcomic);
					}
				}
			}
		}
		else
		{
			$_SESSION['error']= true;
			$_SESSION['errorCode']= 1;
		}
	
		if($_SESSION['error'])
		{
			$_SESSION['installStep']--;
			header("Location: ".htmlspecialchars($_SERVER['PHP_SELF']));
			die;
		}
	}
	
	
	echo '<div id ="installWelcome">Installation </br> finished!</div>';
	
	echo 	'<form action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="POST">
				<button id="installButton" type="submit" name="progress" value="progress">Finish</button>
			</form>';
	
	echo 	'<form action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="POST">
				<button id="installButton2" type="submit" name="back" value="back">Back</button>
			</form>';
	
	
	
	
	
	function upload_logo($fileupload)
	{
		$dir = "../logos/";
	
		if(!is_dir($dir))
		{
			mkdir($dir);
		}
	
		if($fileupload['type'] == "image/gif" || $fileupload['type'] == "image/png" || $fileupload['type'] == "image/jpeg")
		{
			$ispicture = true;
		}
		else
		{
			$ispicture = false;
		}
	
		if(!$fileupload['error'] && $fileupload['size'] > 0 && $fileupload['tmp_name'] && is_uploaded_file($fileupload['tmp_name']) && $ispicture == true)
		{
			move_uploaded_file($fileupload['tmp_name'], $dir.$fileupload['name']);
		}
		else
		{
			echo "<script>alert('Fehler beim Upload');</script>";
		}
	}
	
	function store_logo($fileupload, $mywebcomic)
	{
		if(!defined('includeConnDetails'))
		{
			define('includeConnDetails', TRUE);
		}
	
		require('../connDetails.php');	//no idea why require_once does not work here for me
	
		$dir = "./logos/";
		$tostore = $dir.$fileupload['name'];
	
		$mydbobject = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
	
	
		$sql =	"UPDATE webcomic SET logo=? WHERE webcomic_id = ".$mywebcomic."";
		$eintrag = $mydbobject->prepare($sql);
		$eintrag->bind_param("s", $tostore);
		$eintrag->execute();
	
		$mydbobject->close();
	}
