<?php
	require_once ('authHandler.php');
	require_once ('stripFunctions.php');
	if(isAuthorized(ADMIN))
	{
		if(!defined('includeConnDetails'))
		{
			define('includeConnDetails', TRUE);
		}
		
		require_once ('connDetails.php');

		if(isset($_POST['changeCssButton'], $_POST['cssSelect'],$_POST['comicSelection']))
		{
			if(!is_numeric($_POST['comicSelection']))
			{
				die("Parameters wrong");
			}
			
			$newCss = filter_var($_POST['cssSelect'], FILTER_SANITIZE_STRING);
			$newCss = "./css/".$newCss;
			
			$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
			
			if($connection->errno != 0)
			{
				die("Database connection failed: ".$connection->connect_error);
			}
			
			$stmt = $connection->prepare("UPDATE webcomic SET css = ? WHERE webcomic_id = ?");
			$stmt->bind_param("si", $newCss,$_POST['comicSelection']);
			$stmt->execute();
			$stmt->free_result();
			$stmt->close();
			$connection->close();
			echo '<p id="cssChangeSuccess">Done</p>';
		}
		
		if(isset($_FILES['uplCss']))
		{			
			if(!is_dir("./css"))
			{
				mkdir("./css");
			}
			
			
			if(!$_FILES["uplCss"]['error'] &&
					$_FILES["uplCss"]['size']> 0 &&
					$_FILES["uplCss"]['tmp_name'] &&
					is_uploaded_file($_FILES["uplCss"]['tmp_name']))
			{
				$extension = pathinfo($_FILES["uplCss"]['name'], PATHINFO_EXTENSION);
				$realName = basename($_FILES["uplCss"]['name']);
				$newFilePath = 'css/'.$_FILES["uplCss"]['name'];
					
				if(strcmp($extension, "css") != 0 || !($_FILES["uplCss"]['type'] == "text/css"))
				{
					echo '<p class="uploadStatus">Filetype'.$extension.' rejected</p>';
				}
				elseif(move_uploaded_file($_FILES["uplCss"]['tmp_name'], $newFilePath))
				{			
					echo '<p class="uploadStatus">Upload of '.$realName.' completed</p>';
				}
					
					
			}
		}
		
		
		function getCss()
		{
			if(!is_dir("css"))
			{
				return false;
			}
			else
			{
				$file = opendir("./css/");
				echo "<select name=\"cssSelect\">";
				while($myfile = readdir($file))
				{
					if(!is_dir("./css/".$myfile))
					echo "<option>".$myfile."</option> ";
				}
				echo "</select>";
				
				
			}
		}
		
		echo '<form id="cssSelect" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'?selection='.$_GET['selection'].'" method="POST" > ';
			getCss();
		echo '<select name="comicSelection" id="comicSelection">'; 
				$webcomics = getWebcomics();
				foreach ($webcomics as $comic)
				{
					echo '<option value='.$comic['id'].'>'.$comic['title'].'</option>';
				}
		echo '</select>';
		echo '<input id="changeCssButton" type="submit" name="changeCssButton" value="Select Css" />';
		echo '</form>';
		
		echo ' <form id="uploadCssForm" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'?selection='.$_GET['selection'].'" method="POST" enctype="multipart/form-data">
					<fieldset>
						<input id="uploadCss" type="file" name="uplCss"/>
					</fieldset>
					<input id="uploadCssButton" type="submit" name="uploadCssButton" value="Upload new css file" />
				</form>';
	}
?>