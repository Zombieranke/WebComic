<?php
	if(!defined('includeConnDetails'))
	{
		define('includeConnDetails', TRUE);
	}
	require_once ('stripFunctions.php');
	
	$comicList= "";
	
	$webcomics = getWebcomics();
	foreach ($webcomics as $comic)
	{
		$comicList .= '<option value='.$comic['id'].'>'.$comic['title'].'</option>';
	}

	echo '<fieldset id="changeLogoApplication">';
	echo 	'<h1>Select an uploaded Logo</h1>';
	echo 	'<form id="logoSelect" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'?selection='.$_GET['selection'].'" method="POST" > ';
				getLogo();
	echo 		'<select name="comicSelection" id="comicSelection">';
	echo 			$comicList;
	echo 		'</select>';
	echo 	'<input id="changeLogoButton" type="submit" name="changeLogoButton" value="Select Logo" />';
	echo 	'</form>';
	
	echo 	'<h1>Upload a new Logo</h1>';
	echo 	'<form id="logoForm" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'?selection='.$_GET['selection'].'" method="POST" enctype="multipart/form-data" onReset="purgeForm()">';
	
				
	echo		'<select name="webcomic" id="comicSelection">';
	echo 			$comicList;
	echo 		'</select>
				<input type="file" name="picture">
				<br/>
				<input type="reset" name="resetForm" value="Reset" />
				<input type="submit" name="uploadLogo" value="Upload Logo"/>
				
			</form>
		</fieldset>';
			
	if(isset($_POST['webcomic']) && isset($_FILES['picture']))
	{
		$fileupload = $_FILES['picture'];
		$mywebcomic = $_POST['webcomic'];
		
		upload_logo($fileupload);
		store_logo($fileupload, $mywebcomic);
	}
	
	if(isset($_POST['changeLogoButton'], $_POST['logoSelect'],$_POST['comicSelection']))
	{
		require ('connDetails.php');
		if(!is_numeric($_POST['comicSelection']))
		{
			die("Parameters wrong");
		}
			
		$newLogo = filter_var($_POST['logoSelect'], FILTER_SANITIZE_STRING);
		$newLogo = "./logos/".$newLogo;
			
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
			
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
			
		$stmt = $connection->prepare("UPDATE webcomic SET logo = ? WHERE webcomic_id = ?");
		$stmt->bind_param("si", $newLogo,$_POST['comicSelection']);
		$stmt->execute();
		$stmt->free_result();
		$stmt->close();
		$connection->close();
		echo '<p id="logoChangeSuccess">Done</p>';
	}
	
	function getLogo()
	{
		if(!is_dir("logos"))
		{
			return false;
		}
		else
		{
			$file = opendir("./logos/");
			echo "<select name=\"logoSelect\">";
			while($myfile = readdir($file))
			{
				if(!is_dir("./logos/".$myfile))
					echo "<option>".$myfile."</option> ";
			}
			echo "</select>";
	
	
		}
	}
	
	function upload_logo($fileupload)
	{
		$dir = "./logos/";
		
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
		
		require('connDetails.php');	//no idea why require_once does not work here for me
		
		$dir = "./logos/";
		$tostore = $dir.$fileupload['name'];
		
		$mydbobject = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
		

		$sql =	"UPDATE webcomic SET logo=? WHERE webcomic_id = ".$mywebcomic."";
		$eintrag = $mydbobject->prepare($sql);
		$eintrag->bind_param("s", $tostore);
		$eintrag->execute();
		
		$mydbobject->close();
	}
?>