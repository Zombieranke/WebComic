<?php
	require_once('fileOperations.php');
	require_once ('authHandler.php');
	
	if(isAuthorized(ADMIN))
	{
		if(isset($_FILES['upload']))
		{
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
					
					if(!in_array(strtolower($extension), $allowedExtensions) || !($_FILES["upload"]['type'][$i] == "image/png" || $_FILES["upload"]['type'][$i] == "image/gif" || $_FILES["upload"]['type'][$i] == "image/jpeg"))
					{
						echo '<p class="uploadStatus">Filetype'.$extension.' rejected</p>';
					}
					elseif(move_uploaded_file($_FILES["upload"]['tmp_name'][$i], 'strips/'.$newFileName))
					{
						echo '<p class="uploadStatus">Upload of'.$realName.' completed</p>';
					}
					
					
				}
			}
			
		}
	}
	
	?>
	<script type="text/javascript" src="uploadHelper.js"></script>
	<form id="uploadForm" action="uploadModule.php" method="POST" enctype="multipart/form-data" onReset="purgeForm()">
		<fieldset>
			<input type="file" name="upload[]" onChange="addUpload()"/>
			<input type="text" name="releaseDate[]"/>
		</fieldset>
		<input id="resetButton" type="reset" name="resetForm" value="Reset" />
		<input id="submitButton" type="submit" name="uploadFiles" value="Upload Files"/>
	</form>
	