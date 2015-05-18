<?php		
		//Beginning only
		

		$fileupload = $picture;
		$dir = "./logos/";
		
		if($fileupload['type'] == "image/gif" || $fileupload['type'] == "image/png" || $fileupload['type'] == "image/jpeg"){
			$ispicture = true;
		}
		else{
			$ispicture = false;
		}
		
		if(!$fileupload['error'] && $fileupload['size'] > 0 && $fileupload['tmp_name'] && is_uploaded_file($fileupload['tmp_name']) && $ispicture == true){
			move_uploaded_file($fileupload['tmp_name'], $dir.$fileupload['name']);
		}
		else{
			echo "<script>alert('Fehler beim Upload');</script>";
		}
		
		$im = NULL;
		
		if($type == "image/png"){
			$im = imagecreatefrompng($dir.$fileupload['name']);
		}
		else if($type == "image/jpeg"){
			$im = imagecreatefromjpeg($dir.$fileupload['name']);
		}
		else if($type == "image/gif"){
			$im = imagecreatefromgif($dir.$fileupload['name']);
		}
		
		if($im != NULL){
			$im = imagescale($im, 200 , -1, IMG_BILINEAR_FIXED); //Bildgröße noch ändern
		}
		
		if($type == "image/png"){
				imagepng($im, $dir.$fileupload['name']);
			}
			else if($type == "image/jpeg"){
				imagejpeg($im, $dir.$fileupload['name']);
			}
			else if($type == "image/gif"){
				imagegif($im, $dir.$fileupload['name']);
		}
		
		if($im != NULL){
			imagedestroy($im);
		}
		
		
		define("includeConnDetails", TRUE);
		require_once("connDetails.php");
		
		$mydbobject = new mysqli($database['dbServer'], $database['dbUser'], $databse['dbPassword']);

		$sql =	"INSERT INTO webcomic (logo) VALUES(?)";
		$eintrag = $mydbobject->prepare($sql);
		$eintrag->bind_param("s", $dir.$fileupload);
		$eintrag->execute();
		
		$result->free();
		$mydbobject->close();
?>