<!DOCTYPE html>
<html>
	<head>
		<?php require 'templates/_head.php'; ?>

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
		
	</body>	
</html>	