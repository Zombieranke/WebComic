<?php		
	function upload_logo($fileupload){
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
	}
		
	function store_logo($fileupload){
		$dir = "./logos/";
		
		define("includeConnDetails", TRUE);
		require_once("connDetails.php");
		
		$mydbobject = new mysqli($database['dbServer'], $database['dbUser'], $databse['dbPassword']);

		$sql =	"INSERT INTO webcomic (logo) VALUES(?)";
		$eintrag = $mydbobject->prepare($sql);
		$eintrag->bind_param("s", $dir.$fileupload);
		$eintrag->execute();
		
		$result->free();
		$mydbobject->close();
	}
?>