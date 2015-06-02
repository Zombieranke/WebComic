<?php
	require_once ('stripFunctions.php');
	
	$webcomic = getWebcomics();

	echo '<script type="text/javascript" src="uploadHelper.js"></script>
			<form id="logoForm" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'?selection='.$_GET['selection'].'" method="POST" enctype="multipart/form-data" onReset="purgeForm()">
				<fieldset>
					<select name="webcomic" id="comicSelection">';
					$webcomics = getWebcomics();
					foreach ($webcomics as $comic)
					{
						echo '<option value='.$comic['id'].'>'.$comic['title'].'</option>';
					}
	echo 			'</select>
					<input type="file" name="picture">
					<br/>
					<input type="reset" name="resetForm" value="Reset" />
					<input type="submit" name="uploadLogo" value="Upload Logo"/>
				</fieldset>
			</form>';
			
	if(isset($_POST['webcomic']) && isset($_FILES['picture']))
	{
		$fileupload = $_FILES['picture'];
		$mywebcomic = $_POST['webcomic'];
		
		upload_logo($fileupload);
		store_logo($fileupload, $mywebcomic);
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