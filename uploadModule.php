<?php
	require_once ('authHandler.php');
	require_once ('stripFunctions.php');
	if(isAuthorized(ADMIN))
	{
		if(!defined('includeConnDetails'))
		{
			define('includeConnDetails', TRUE);
		}
		
		require ('connDetails.php');
		require_once ('stripFunctions.php');
		
	
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
					$fileName = basename($_FILES["upload"]['name'][$i]);
					$newFileName = uniqid("strip_",TRUE).".".$extension;
					$newFilePath = 'strips/'.$newFileName;
					
					if(!in_array(strtolower($extension), $allowedExtensions) || !($_FILES["upload"]['type'][$i] == "image/png" || $_FILES["upload"]['type'][$i] == "image/gif" || $_FILES["upload"]['type'][$i] == "image/jpeg"))
					{
						echo '<p class="uploadStatus">Filetype'.$extension.' rejected</p>';
					}
					elseif(move_uploaded_file($_FILES["upload"]['tmp_name'][$i], $newFilePath))
					{
						if(!is_numeric($_POST['webcomic']))
						{
							unlink($newFilePath);
							echo '<p class="uploadStatus">File '.$extension.' could not be linked with webcomic</p>';
						}
						else
						{
							$stripName = $_POST['stripName'][$i];
							$annotation = $_POST['annotation'][$i];
							$releaseDate = $_POST['releaseDate'][$i];
							
							if(empty($releaseDate) )
							{
								$releaseDate = date('Y-m-d G:i:s');
								//sleep(1);
							}
							
							$stmt = $connection->prepare("INSERT INTO strip (stripname,filename,datapath,annotation,releasedate,fk_webcomic_id) VALUES (?,?,?,?,?,?)");
							$stmt->bind_param("sssssi", $stripName, $fileName, $newFilePath, $annotation, $releaseDate, $_POST['webcomic']);
							$stmt->execute();
							
							$stmt->free_result();
							$stmt->close();
							
							
							echo '<p class="uploadStatus">Upload of '.$fileName.' completed</p>';
						}
					}
					
					
				}
			}
			
			while(!sanitizeDatabase());
			$connection->close();
			
		}


	echo '<script type="text/javascript" src="uploadHelper.js"></script>
	<form id="uploadForm" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'?selection='.$_GET['selection'].'" method="POST" enctype="multipart/form-data" onReset="purgeForm()">
		<fieldset id="containerFieldset">
			<fieldset>
				<input type="file" class="inputFile" name="upload[]" onChange="addUpload(this)"/> </br>
				<div>
					Strip Name:
				</div>
				<input type="text" class="inputText" name="stripName[]" placeholder="Enter the name of the strip"/> </br>
				<div>
					Release Date:
				</div>
				<input type="datetime-local" class="inputDate" name="releaseDate[]" placeholder="YYYY-MM-DD HH:DD:SS"/> </br>
				<div>
					Annotation:
				</div>
				<textarea class="uploadAnnotation" name="annotation[]" rows="2"></textarea> </br>
			</fieldset>
			
			<select name="webcomic" id="comicSelection">'; 
				$webcomics = getWebcomics();
				foreach ($webcomics as $comic)
				{
					echo '<option value='.$comic['id'].'>'.$comic['title'].'</option>';
				}
		echo '</select>
			<input id="resetButton" type="reset" name="resetForm" value="Reset" />
			<input id="submitButton" type="submit" name="uploadFiles" value="Upload Files"/>
		</fieldset>
	</form>';
	}
?>
	