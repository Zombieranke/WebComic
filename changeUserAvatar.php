<?php

if(!defined('includeConnDetails'))
{
	define('includeConnDetails', TRUE);
}
 
require_once ('authHandler.php');
require ('connDetails.php');

if(isAuthorized(USER))
{
	if(isset($_POST['changeUserAvatar'],$_FILES['avatar']))
	{
		if(loginUser($_SESSION['user_name'], $_POST['passForAvatar']))
		{
			$connection = new mysqli($database['dbServer'],$database['dbUser'],$database['dbPassword'],$database['dbName']);
	
			if($connection->errno != 0)
			{
				die("Database connection failed: ".$connection->connect_error);
			}
				
			if(!is_dir("./avatars"))
			{
				mkdir("./avatars");
			}
				
			$allowedExtensions = array("jpg","png", "gif");
				
			if(!$_FILES["avatar"]['error'] &&
					$_FILES["avatar"]['size']> 0 &&
					$_FILES["avatar"]['tmp_name'] &&
					is_uploaded_file($_FILES["avatar"]['tmp_name']))
			{
				$extension = pathinfo($_FILES["avatar"]['name'], PATHINFO_EXTENSION);
				$fileName = basename($_FILES["avatar"]['name']);
				$newFileName = uniqid("avatar_",TRUE).".".$extension;
				$newFilePath = 'avatars/'.$newFileName;
					
				if(!in_array(strtolower($extension), $allowedExtensions) || !($_FILES["avatar"]['type'] == "image/png" || $_FILES["avatar"]['type'] == "image/gif" || $_FILES["avatar"]['type'] == "image/jpeg"))
				{
					echo '<p class="uploadStatus">Filetype'.$extension.' rejected</p>';
				}
				elseif(move_uploaded_file($_FILES["avatar"]['tmp_name'], $newFilePath))
				{
					$stmt = $connection->prepare("SELECT avatar FROM user WHERE username = ?");
					$stmt->bind_param("s", $_SESSION['user_name']);
					$stmt->execute();
					$stmt->bind_result($avatar);
					$stmt->fetch();
					$stmt->free_result();
					$stmt->close();
						
					if(!empty($avatar))
					{
						unlink("avatars/".$avatar);
					}
					
					$img = create_thumb($newFileName, 100, 100);
					
					$stmt = $connection->prepare("UPDATE user SET avatar = ? WHERE username = ?");
					$stmt->bind_param("ss", $img, $_SESSION['user_name']);
					$stmt->execute();
	
					$stmt->free_result();
					$stmt->close();
					
					$connection->close();
	
	
					echo '<p class="uploadStatus">Succesfully changed avatar to '.$fileName.'</p>';
				}
			}		
		}
		else
		{
			echo '<p class="idVerification">User verification failed...</p>';
		}
	}




	echo '	<form id="newEmailForm" action="index.php?profile&selection='.$_GET['selection'].'" method="POST" enctype="multipart/form-data">
					<fieldset>
						<input id="avatar" class="avatar" type="file" name="avatar"/> </br>
						<input class="newAvatar" type="password" name="passForAvatar" placeholder="Enter your password here"/> </br>
						<input id="newAvatarSubmit" type="submit" name="changeUserAvatar" value="Change Avatar" />
					</fieldset>
				</form>';
}



function create_thumb($imgfile,$targetWidth,$targetHeight)
{
	$imgsize = getimagesize('avatars/'.$imgfile);
	$imgwidth = $imgsize[0];
	$imgheight = $imgsize[1];
	$img = create_image_from_file($imgfile);

	if($img)
	{
		$maxthumbwidth = $targetWidth;
		$maxthumbheight = $targetHeight;

		$thumbwidth = $imgwidth;
		$thumbheight = $imgheight;

		if ($thumbwidth > $maxthumbwidth)
		{
			$factor = $maxthumbwidth / $thumbwidth;
			$thumbwidth *= $factor;
			$thumbheight *= $factor;
		}

		if ($thumbheight > $maxthumbheight)
		{
			$factor = $maxthumbheight / $thumbheight;
			$thumbwidth *= $factor;
			$thumbheight *= $factor;
		}

		$thumb = imagecreatetruecolor($targetWidth, $targetHeight);
		imagealphablending($thumb, false);
		$col=imagecolorallocatealpha($thumb,255,255,255,127);
		imagefilledrectangle($thumb, 0, 0, $targetWidth, $targetHeight,$col);
		imagealphablending($thumb,true);

		imagecopyresampled($thumb, $img, ($targetWidth-$thumbwidth)/2, ($targetHeight-$thumbheight)/2, 0, 0, $thumbwidth, $thumbheight, $imgwidth, $imgheight);
		imagealphablending($thumb,true);
		
		
		imagealphablending($thumb,false);
		imagesavealpha($thumb,true);
		
		unlink("avatars/".$imgfile);
		
		$imgfile = pathinfo($imgfile, PATHINFO_FILENAME).".png";

		imagepng($thumb,'avatars/'.$imgfile);
		imagedestroy($img);
		imagedestroy($thumb);
		return $imgfile;
	}
	else
	{
		return false;
	}
}


function create_image_from_file($imgfile)
{
	$imgsize = getimagesize('avatars/'.$imgfile);
	$imgtype = $imgsize[2];

	switch($imgtype)
	{
		case IMG_GIF:
			$img = imagecreatefromgif('avatars/'.$imgfile);
			break;
		case IMG_JPG:
			$img = imagecreatefromjpeg('avatars/'.$imgfile);
			break;
		case IMG_PNG: case 3:
			$img = imagecreatefrompng('avatars/'.$imgfile);
			break;
		default:
			echo "Unsupported format";
			return false;
	}

	return $img;
}
?>