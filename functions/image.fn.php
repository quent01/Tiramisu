<?php
// File that contain the function that permit to handle image_gallery
//
//
	//name of the directory where we store the img
	$DIR_STORAGE 		= 'Archive_Gallery';
	$DIR_BIG 			= 'big';  //folder with the original img
	$DIR_MINI			= 'mini'; //folder with the resized img
	
	$PATH_TO_STORAGE 	= 'img'.'/'.$DIR_STORAGE; 

	$MAX_WIDTH 			= 300;
	$MAX_HEIGHT 		= 200;

	$img_obj = array(
		'url'			=> 'url',
		'title' 		=> 'title',
		'date' 			=> 'date',
		'owner_id' 		=> 'owner_id',
		'club_assoc' 	=> 'club_assoc',
		'tags'			=> array(
							'logo'		=> true,
							'dessin'	=> true,
							'affiche'	=> true,
							'typo'		=> true,
							'divers'	=> true	
		)
	);

	// ================================== 
	// AJOUT D'IMAGE
	// ================================== 

		/**
		* AJOUTE UNE IMAGE
		* @param title 
		* @param date
		* @param owner
		* @param array of tag
		* Vérifier le format du fichier transmis (jpeg, gif ou png)
		* Create a directory in img/$storage with the name of the year if necessary
		* We store 2 version of the image, the original and a smaller one
		* Insert the url, title, date, owner in the table 'images'
		* Retrieve an array of id of the tags
		* Insert for each tags in tags_array; image_id, tag_id   
		*/

		function upload($index,$destination,$maxsize=FALSE,$extensions=FALSE){
		   //Test1: fichier correctement uploadé
		     if (!isset($_FILES[$index]) OR $_FILES[$index]['error'] > 0) return FALSE;
		   //Test2: taille limite
		     if ($maxsize !== FALSE AND $_FILES[$index]['size'] > $maxsize) return FALSE;
		   //Test3: extension
		     $ext = substr(strrchr($_FILES[$index]['name'],'.'),1);
		     if ($extensions !== FALSE AND !in_array($ext,$extensions)) return FALSE;
		   //Déplacement
		     return move_uploaded_file($_FILES[$index]['tmp_name'],$destination);
		}
		 
		// //EXEMPLES
		//   $upload1 = upload('icone','uploads/monicone1',15360, array('png','gif','jpg','jpeg') );
		//   $upload2 = upload('mon_fichier','uploads/file112',1048576, FALSE );
		 
		//   if ($upload1) "Upload de l'icone réussi!<br />";
		//   if ($upload2) "Upload du fichier réussi!<br />";


		/**
		* VÉRIFIER LE FORMAT DU FICHIER TRANSMIS (jpeg, JPEG, jpg, JPG, gif, GIF,  ou png, PNG)
		* @param file index
		* @return path to the file
		* Test if the file was correctly send and if there is no error
		* Test if the size limit os ok
		* Test the extension of the file
		* Resize the file if necessary
		* Save the file in a specific folder
		*/
		// We create an array with only the right extensions associated to their type MIME (that change with IE) :
		function uploadImage($Image_Index){	

			echo '<p>'.'debut fonction upload'.'</p>'."\n";
			
			$ListeExtension = array('jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif');
			$ListeExtensionIE = array('jpg' => 'image/pjpg', 'jpeg'=>'image/pjpeg'); // Because IE is such a pain as always

			//Test 1 : We verify if the file was correctly send and if there is no error
		    if (!isset($_FILES[$Image_Index]) OR $_FILES[$Image_Index]['error'] > 0){ 
		    	echo '<p>'.'Le fichier n\'a pas été correctement envoyé'.'</p>'."\n" ;
		    	return FALSE;
		    }
		    echo 'fichier envoyé'."\n" ;

		    //Test2: taille limite 2Mo
		    if ($_FILES[$Image_Index]['size'] >= 2097152){
		    	echo '<p>'.'La taille du fichier est supérieur à 2Mo'.'</p>'."\n";
		    	return FALSE;	
		    }
		    echo '<p>'.'taille de fichier inférieur à 2Mo'.'</p>'."\n";  

			$ImageNews = $_FILES[$Image_Index]['name'];

			// Je vérifie l'extension présumée du fichier :
			$ExtensionPresumee 	= explode('.', $ImageNews);
			$ImageName 			= $ExtensionPresumee; //this is an array
			$ExtensionPresumee = strtolower($ExtensionPresumee[count($ExtensionPresumee)-1]);//in case the image name is mon.image.jpg

			if ($ExtensionPresumee == 'jpg' || $ExtensionPresumee == 'jpeg' || 
				$ExtensionPresumee == 'pjpg' || $ExtensionPresumee == 'pjpeg' || 
				$ExtensionPresumee == 'gif' || $ExtensionPresumee == 'png'){
				
				echo 'extension valide'."\n" ;

				// We verify the mime type
				$ImageNews = getimagesize($_FILES[$Image_Index]['tmp_name']);
				
				if($ImageNews['mime'] == $ListeExtension[$ExtensionPresumee]  ||//if browser is Chrome or Firefox 
					$ImageNews['mime'] == $ListeExtensionIE[$ExtensionPresumee]){//if browser is IE
						
						echo '<p>'.'type mime valide'.'</p>'."\n" ;
						/**
						* The current year, it has to be changed dynamically
						*/						
						$Year = '2013-2014';
						echo '<p>'.'MAX_WIDTH = '.$GLOBALS['MAX_WIDTH'].'</p>';
						return ResizeAndSave($Image_Index, $ExtensionPresumee, $ImageName, 
							$GLOBALS['MAX_WIDTH'], $GLOBALS['MAX_HEIGHT'], $Year);

				}
				return FALSE;
			}
			return FALSE;
		}
		//exemple
		// $upload1 = upload('icone');
		// if ($upload1) "Upload de l'icone réussi!<br />";			

		/**
		* Resize the image if necessary
		* Save the resied image and the copy in a specific directory
		* @return the name of the image
		*/
		function ResizeAndSave($Image_Index, $ExtensionPresumee, $ImageName, 
			$max_width, $max_height, $Year){
			
			//we change the name of the file
			$oldName = $ImageName[0];
			$newName = time(); 

			//if jpg
			if(	$ExtensionPresumee == 'jpg' || $ExtensionPresumee == 'jpeg' || 
				$ExtensionPresumee == 'pjpg' || $ExtensionPresumee == 'pjpeg'){

				//we save the img	
				$imageChoisie 		= imagecreatefromjpeg($_FILES[$Image_Index]['tmp_name']);
				$TailleImageChoisie = getimagesize($_FILES[$Image_Index]['tmp_name']);
				$ImageWidth = $TailleImageChoisie[0];
				$ImageHeight 	= $TailleImageChoisie[1];

				//si l'image est trop grande
				if($ImageWidth > $max_width || $ImageHeight > $max_height){
					//si format portrait
					if($ImageWidth > $ImageHeight){
					
						$newWidth 	= $max_width;
						$ratio 		= $newWidth/$ImageWidth;
						$newHeight	= $ImageHeight*$ratio;

						$newImage = imagecreatetruecolor($newWidth, $newHeight) or die("erreur lors du redimensionnement");
						imagecopyresampled($newImage, $imageChoisie, 0, 0, 0, 0,$newWidth, $newHeight, $ImageWidth, $ImageHeight);

						//we save the resized img 
						imagejpeg($newImage, $GLOBALS['PATH_TO_STORAGE'].'/'.$GLOBALS['DIR_MINI'].'/'.$Year.'/'.$newName.'.'.$ExtensionPresumee , 100);

						//we save the original
						imagejpeg($imageChoisie, $GLOBALS['PATH_TO_STORAGE'].'/'.$GLOBALS['DIR_BIG'].'/'.$Year.'/'.$newName.'.'.$ExtensionPresumee , 100);
					}
					//format paysage
					else{
						$newHeight	= $max_height;
						$ratio		= $newHeight/$ImageHeight;
						$newWidth	= $ImageWidth*$ratio;

						$newImage = imagecreatetruecolor($newWidth, $newHeight) or die("erreur lors du redimensionnement");
						imagecopyresampled($newImage, $imageChoisie, 0, 0, 0, 0,$newWidth, $newHeight, $ImageWidth, $ImageHeight);
						
						//we save the resized img
						imagejpeg($newImage, $GLOBALS['PATH_TO_STORAGE'].'/'.$GLOBALS['DIR_MINI'].'/'.$Year.'/'.$newName.'.'.$ExtensionPresumee , 100);

						//we save the original
						imagejpeg($imageChoisie, $GLOBALS['PATH_TO_STORAGE'].'/'.$GLOBALS['DIR_BIG'].'/'.$Year.'/'.$newName.'.'.$ExtensionPresumee , 100);
					}
						
				}
				else{
					//we save the original
					imagejpeg($imageChoisie, $GLOBALS['PATH_TO_STORAGE'].'/'.$GLOBALS['DIR_MINI'].'/'.$Year.'/'.$newName.'.'.$ExtensionPresumee , 100);
					imagejpeg($imageChoisie, $GLOBALS['PATH_TO_STORAGE'].'/'.$GLOBALS['DIR_BIG'].'/'.$Year.'/'.$newName.'.'.$ExtensionPresumee , 100);
				} 

			}
			//if png
			else if($ExtensionPresumee == 'png'){
				//we save the img	
				$imageChoisie 		= imagecreatefrompng($_FILES[$Image_Index]['tmp_name']);
				$TailleImageChoisie = getimagesize($_FILES[$Image_Index]['tmp_name']);
				$ImageWidth = $TailleImageChoisie[0];
				$ImageHeight 	= $TailleImageChoisie[1];

				//si l'image est trop grande
				if($ImageWidth > $max_width || $ImageHeight > $max_height){
					//si format portrait
					if($ImageWidth > $ImageHeight){
					
						$newWidth 	= $max_width;
						$ratio 		= $newWidth/$ImageWidth;
						$newHeight	= $ImageHeight*$ratio;

						$newImage = imagecreatetruecolor($newWidth, $newHeight) or die("erreur lors du redimensionnement");
						imagecopyresampled($newImage, $imageChoisie, 0, 0, 0, 0,$newWidth, $newHeight, $ImageWidth, $ImageHeight);

						//we save the resized img
						imagepng($newImage, $GLOBALS['PATH_TO_STORAGE'].'/'.$GLOBALS['DIR_MINI'].'/'.$Year.'/'.$ImageName[0].'.'.$ExtensionPresumee , 100);

						//we save the original
						imagepng($imageChoisie, $GLOBALS['PATH_TO_STORAGE'].'/'.$GLOBALS['DIR_BIG'].'/'.$Year.'/'.$ImageName[0].'.'.$ExtensionPresumee , 100);
					}
					//format paysage
					else{
						$newHeight	= $max_height;
						$ratio		= $newHeight/$ImageHeight;
						$newWidth	= $ImageWidth*$ratio;

						$newImage = imagecreatetruecolor($newWidth, $newHeight) or die("erreur lors du redimensionnement");
						imagecopyresampled($newImage, $imageChoisie, 0, 0, 0, 0,$newWidth, $newHeight, $ImageWidth, $ImageHeight);
						
						//we save the resized img
						imagepng($newImage, $GLOBALS['PATH_TO_STORAGE'].'/'.$GLOBALS['DIR_MINI'].'/'.$Year.'/'.$ImageName[0].'.'.$ExtensionPresumee , 100);

						//we save the original
						imagepng($imageChoisie, $GLOBALS['PATH_TO_STORAGE'].'/'.$GLOBALS['DIR_BIG'].'/'.$Year.'/'.$ImageName[0].'.'.$ExtensionPresumee , 100);
					}
						
				}
				else{
					//we save the original
					imagepng($imageChoisie, $GLOBALS['PATH_TO_STORAGE'].'/'.$GLOBALS['DIR_MINI'].'/'.$Year.'/'.$ImageName[0].'.'.$ExtensionPresumee , 100);
					imagepng($imageChoisie, $GLOBALS['PATH_TO_STORAGE'].'/'.$GLOBALS['DIR_BIG'].'/'.$Year.'/'.$ImageName[0].'.'.$ExtensionPresumee , 100);
				}				
			}
			//if gif
			else{
				//we save the img	
				$imageChoisie 		= imagecreatefromgif($_FILES[$Image_Index]['tmp_name']);
				$TailleImageChoisie = getimagesize($_FILES[$Image_Index]['tmp_name']);
				$ImageWidth = $TailleImageChoisie[0];
				$ImageHeight 	= $TailleImageChoisie[1];

				//si l'image est trop grande
				if($ImageWidth > $max_width || $ImageHeight > $max_height){
					//si format portrait
					if($ImageWidth > $ImageHeight){
					
						$newWidth 	= $max_width;
						$ratio 		= $newWidth/$ImageWidth;
						$newHeight	= $ImageHeight*$ratio;

						$newImage = imagecreatetruecolor($newWidth, $newHeight) or die("erreur lors du redimensionnement");
						imagecopyresampled($newImage, $imageChoisie, 0, 0, 0, 0,$newWidth, $newHeight, $ImageWidth, $ImageHeight);

						//we save the resized img
						imagepng($newImage, $GLOBALS['PATH_TO_STORAGE'].'/'.$GLOBALS['DIR_MINI'].'/'.$Year.'/'.$newName.'.'.$ExtensionPresumee , 100);

						//we save the original
						imagepng($imageChoisie, $GLOBALS['PATH_TO_STORAGE'].'/'.$GLOBALS['DIR_BIG'].'/'.$Year.'/'.$newName.'.'.$ExtensionPresumee , 100);
					}
					//format paysage
					else{
						$newHeight	= $max_height;
						$ratio		= $newHeight/$ImageHeight;
						$newWidth	= $ImageWidth*$ratio;

						$newImage = imagecreatetruecolor($newWidth, $newHeight) or die("erreur lors du redimensionnement");
						imagecopyresampled($newImage, $imageChoisie, 0, 0, 0, 0,$newWidth, $newHeight, $ImageWidth, $ImageHeight);
						
						//we save the resized img
						imagepng($newImage, $GLOBALS['PATH_TO_STORAGE'].'/'.$GLOBALS['DIR_MINI'].'/'.$Year.'/'.$newName.'.'.$ExtensionPresumee , 100);

						//we save the original
						imagepng($imageChoisie, $GLOBALS['PATH_TO_STORAGE'].'/'.$GLOBALS['DIR_BIG'].'/'.$Year.'/'.$newName.'.'.$ExtensionPresumee , 100);
					}						
				}
				else{
					//we save the original
					imagepng($imageChoisie, $GLOBALS['PATH_TO_STORAGE'].'/'.$GLOBALS['DIR_MINI'].'/'.$Year.'/'.$newName.'.'.$ExtensionPresumee , 100);
					imagepng($imageChoisie, $GLOBALS['PATH_TO_STORAGE'].'/'.$GLOBALS['DIR_BIG'].'/'.$Year.'/'.$newName.'.'.$ExtensionPresumee , 100);
				}	
				 
			}	

			return $newName.'.'.$ExtensionPresumee;
		}


		/**
		* CREATE A DIRECTORY IF NECESARY 
		* @param name of the directory
		* @param path
		* @return true or false
		*/
		function createDir($dirName, $path){
			$res = false;
			$dirName = strtolower($dirName);
			$pathToDirectory = $path.'/'.$dirName;
			
			if(!is_dir($pathToDirectory)){
				$res = mkdir($pathToDirectory);
			}
			return $res;	
		}

		/**
		* RETRIEVE AN ARRAY OF ID OF THE TAGS
		* @param array of tag name
		* @return array of tag id
		*/

		/**
		* INSERT FOR EACH TAGS IN TAGS_ARRAY; IMAGE_ID, TAG_ID   
		* @param image_id, array of tag_id
		*/



	// ================================== 
	// LISTE D'IMAGES
	// ==================================

		/**
		* LISTE LES IMAGES PAR 'ID'
		*
		*/

		/**
		* LISTE LES IMAGES PAR 'DATE'
		*
		*
		*/

		/**
		* LISTE LES IMAGES PAR 'OWNER'
		*
		*/

		/**
		* LISTE LES IMAGES PAR 'TAG'
		*
		*/

		/**
		* LISTE LES IMAGES PAR 'CLUB_ASSOC'
		*
		*/

		/**
		* LISTE LES IMAGES PAR 'VALEUR DU CHAMP DE RECHERCHE' (tags, date, owner, club_assoc, title)
		*
		*/

	// ================================== 
	// TÉLÉCHARGEMENT D'IMAGE
	// ==================================

		/**
		* TÉLÉCHARGER UN ZIP
		* @param array of image_id
		* @return lien de téléchargement du zip
		* retrouver les url de chaque image
		* créer un dossier de téléchargement temporaire et copier les fichiers dedans
		* faire un zip du dossier
		* renvoyer le lien de téléchargement du zip
		*/
		function createZip($url_array){
			$zip = new ZipArchive();
			$res = $zip->open('Tiramisu.zip', ZipArchive::CREATE);
			if($res === TRUE){
				foreach ($url_array as $key => $value) {
					if(!$zip->addFile($value)){
						echo 'impossible d\'ajouter '.$value.'<br />';	
					}
				}
				$zip->close();
			}
			else{
				echo 'Impossible de créer l\'Archive';
			}
			return $res;
		}