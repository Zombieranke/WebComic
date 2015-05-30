<!DOCTYPE html>
<html>
	<head>
		<link type="text/css" rel="stylesheet" href="style.css">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title>
			W3BC0M1C
		</title>
	</head>
	
	<body>
		<div class="wrapper">
			<div class="logo">
				<img src="pictures/logo.jpg" alt="Very cool Logo">
			</div>
			<ul id="navigation">
				<li><a href="index.php">Comic</a> </li>
				<li><a href="index.php">About me</a> </li>
				<li><a href="index.php">Merchandise</a>	</li>
				<li><a href="index.php">Contact</a>	</li>
			</ul>
			
			<div id="content">
				<?php 		
					include('comicNavigation.php');
				?>
			</div>
		</div>
	</body>
</html>