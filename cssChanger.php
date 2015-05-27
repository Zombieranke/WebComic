<?php
	require_once ('authHandler.php');
	if(isAuthorized(ADMIN))
	{
		
		
		
		
		
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
		
		
		getCss();
		
		echo '
				<div id="checkMark"></div>
				<form id="uploadForm" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'?selection='.$_GET['selection'].'" method="POST" ">
					<fieldset>
						<input id="uploadCss" type="file" name="uplCss""/>
					</fieldset>
					<input id="submitButton" type="submit" name="submitUploadCss" value="Upload new css file" />
				</form>';
	}
?>