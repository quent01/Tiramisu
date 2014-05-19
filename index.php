<!DOCTYPE html>
<html>
	<head>
		<?php require 'templates/_head.php'; ?>
		<link rel="stylesheet" href="templates/css/style.css">

		<!--Google analytics-->
		<!--plus tard-->
		<!--Fin google analytics-->

		<title>Tiramisù</title>
	</head>
	
	<body>	
		<header id="header_principal">
			<?php include 'templates/_header.php'; ?>	
		</header>
		
		<div id="main" class="shadow">
			
			<?php include_once 'functions/comicstrip_navigation.php'; ?>
			<?php
				if(isset($_GET['comicsID'])){	
					$img = $_GET['comicsID'];
					if($img >= 1 && $img <= $img_max)
						$path = build_path($img);
					else {
						$img = count_files();
						$path = build_path($img);
					}
				}
				else{
					$img = count_files();
					$path = build_path($img);
				}
			
			?>
			<div id="comicstrip">
				<h1></h1>
				<img src=<?php echo "\"".$path."\""; ?>/>
				<div class="comics_nav">
					<ul id="nav_page">
						<li><a href=<?php echo build_link(previous_img($img)); ?>>previous</a></li>
						<li><a href=<?php echo build_link(next_img($img)); ?>>next</a></li>
					</ul>
					<span id="page"><?php echo "$img / $img_max"; ?></span>
				</div>
			</div>
		</div>
		
		<footer>
			<?php include 'templates/_footer.php'; ?>			
		</footer>
		
		<!--jQuery-->
		<script type="text/javascript" src="templates/js/jquery.min.js"></script>
		<script type="text/javascript" src="templates/js/keypress_nav.js"></script>
		<!--end jQuery-->
	</body>	
</html>	