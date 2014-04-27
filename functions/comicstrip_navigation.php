<?php 
	
	$directory = "img/comicstrip/";

	/**
	 * build the path to an image
	 */
	function build_path($img){
		$path = $GLOBALS["directory"]."/".$img.".jpg";
		return $path;
	}
	 	
	/**
	 * function that count the number of files in a directory
	 */
	 function count_files(){
	 	$res = 0;	
	 	if($dir = opendir($GLOBALS["directory"])){
	 		//we verify that no error is returned
	 		while(false !== ($file = readdir($dir))){
				if($file != '.' && $file != '..'){
						
					$res++;
				}
	 		}
			closedir($dir);
		}
		else
			//to remove
			echo "Le dossier".$dirname." n\'a pas pu être ouvert";
			
		return $res;	
	 }
	 
	 $img_max = count_files();
	 
	/**
	* function that return the next image in the comicstrip directory
	*/
	function next_img($img){
	  	if($img < $GLOBALS["img_max"])
			$img++;
		else
			$img = $GLOBALS["img_max"];

		return $img;
	}

	/**
	 * function that return the previous image in the comicstrip directory
	 */ 
	 function previous_img($img){
	 	if($img > 1)
			$img--;
		else 
			$img = 1;
		
		return $img;
	 }
	 
	/**
	* function that build the link to the correct page
	*/ 
	function build_link($img){
		if($img >= 1 && $img <= $GLOBALS["img_max"])
	  		$link = "index.php?comicsID=$img";
		else 
			$link = "";
		
		return $link;
	  }		
?>