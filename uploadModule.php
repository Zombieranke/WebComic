<?php
	require_once ('authHandler.php');
	
	define("includeConnDetails",TRUE);
	
	require_once ('connDetails.php');
	
	if(isAuthorized(ADMIN))
	{
		if(isset($_FILES['upload']))
		{
			$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
	
			if($connection->errno != 0)
			{
				die("Database connection failed: ".$connection->connect_error);
			}
			
			if(!is_dir("./strips"))
			{
				mkdir("./strips");
			}
			
			$allowedExtensions = array("jpg","png", "gif");

			
			for ($i = 0; $i<count($_FILES["upload"]["name"]);$i++)
			{
				if(!$_FILES["upload"]['error'][$i] && 
					$_FILES["upload"]['size'][$i]> 0 && 
					$_FILES["upload"]['tmp_name'][$i] && 
					is_uploaded_file($_FILES["upload"]['tmp_name'][$i]))
				{
					$extension = pathinfo($_FILES["upload"]['name'][$i], PATHINFO_EXTENSION);
					$realName = basename($_FILES["upload"]['name'][$i]);
					$newFileName = uniqid("strip_",TRUE).".".$extension;
					$newFilePath = 'strips/'.$newFileName;
					
					if(!in_array(strtolower($extension), $allowedExtensions) || !($_FILES["upload"]['type'][$i] == "image/png" || $_FILES["upload"]['type'][$i] == "image/gif" || $_FILES["upload"]['type'][$i] == "image/jpeg"))
					{
						echo '<p class="uploadStatus">Filetype'.$extension.' rejected</p>';
					}
					elseif(move_uploaded_file($_FILES["upload"]['tmp_name'][$i], $newFilePath))
					{
						$stmt = $connection->prepare("INSERT INTO strips (name,datei,bemerkung,ver�ffentlichungsdatum,fk_webcomic_id) VALUES (?,?,?,?,?)");
						$stmt->bind_param("ssssi", $realName, $newFilePath,$_POST['annotation'][$i],$_POST['releaseDate'][$i],$_POST['webcomic']);
						$stmt->execute();
						
						$stmt->free_result();
						$stmt->close();
						
						
						echo '<p class="uploadStatus">Upload of '.$realName.' completed</p>';
					}
					
					
				}
			}
			
		}
	}
	
	function getWebcomics()
	{	
		require('connDetails.php');
		
		$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
		
		if($connection->errno != 0)
		{
			die("Database connection failed: ".$connection->connect_error);
		}
		
		$stmt = $connection->prepare("SELECT titel, webcomic_id FROM webcomic");
		$stmt->execute();
		$stmt->bind_result($title,$id);
		
		$i=0;
		
		while($stmt->fetch())
		{
			$webcomics[$i]['title']=$title;
			$webcomics[$i]['id']=$id;
			$i++;
		}
		
		$stmt->free_result();
		$stmt->close();
		
		return $webcomics;
	}
	
?>
<script type="text/javascript" src="uploadHelper.js"></script>
<form id="uploadForm" action="uploadModule.php" method="POST" enctype="multipart/form-data" onReset="purgeForm()">
	<fieldset>
		<input type="file" name="upload[]" onChange="addUpload(this)"/>
		<label>Ver&ouml;ffentlichungsdatum:
			<input type="datetime-local" name="releaseDate[]" placeholder="YYYY-MM-DD HH:DD:SS"/>
		</label>
		<label>Kommentar:
			<textarea name="annotation[]"></textarea>
		</label>
	</fieldset>
	<select name="webcomic" id="comicSelection">
		<?php 
			$webcomics = getWebcomics();
			foreach ($webcomics as $comic)
			{
				echo '<option value='.$comic['id'].'>'.$comic['title'].'</option>';
			}
		?>
	</select>
	<input id="resetButton" type="reset" name="resetForm" value="Reset" />
	<input id="submitButton" type="submit" name="uploadFiles" value="Upload Files"/>
</form>
	