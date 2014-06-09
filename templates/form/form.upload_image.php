<?php /**
TEMPLATE FORMULAIRE
*/ ?>
<form method="post" action="archive.php" enctype="multipart/form-data">
	<fieldset class="formulaire_news">
        <p>
            <label for="ImageTitle">Titre :</label>
            <input type="text" name="ImageTitle" id="ImageTitle" />
        </p>
                       
        <p>
            <label for="ImageNews">Image : </label>
            <!-- We limit file size to 2Mo -->
            <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
            <input type="file" name="ImageNews" id="ImageNews" />
        </p>
                        
        <p>
            <label for="ImageClubs">Club :</label>
            <input type="text" name="ImageClubs" id="ImageClubs"/>
        </p>
 		<p>
 			<label for="tags">tags :</label>
 			<input type="checkbox" name='tags[]' value="logo"/>Logo
 			<input type="checkbox" name='tags[]' value="dessin"/>Dessin	
 			<input type="checkbox" name='tags[]' value="affiche"/>Affiche <br />	
 			<input type="checkbox" name='tags[]' value="typo"/>Typo	
 			<input type="checkbox" name='tags[]' value="divers"/>Divers	

 		</p>

        <p>
            <input type="submit" name="InsererNews" value="Insérer" />
        </p>
	</fieldset>
</form>

<?php /**
INCLUDE PHP
*/ 
require 'functions/image.fn.php';

?>


<?php /**
TRAITEMENT FORMULAIRE
*/

	//Si le tableau POST existe , alors le formulaire a été envoyé
    if(!empty($_POST)){
        //le titre a t'il été rempli ?
        if(empty($_POST['ImageTitle'])){
            $message = '<p>'.'Veuillez indiquer un titre !'.'</p>'."\n";
            echo $message;
        }
        else  $ImageTitle = htmlspecialchars($_POST['ImageTitle']);
        
        //Traitement de l'image
        $imageName = uploadImage('ImageNews');
        if($imageName){
            $message = '<p>'.'Image correctement transmise : '.$imageName.'</p>'."\n";
            echo $message;
        }
        //Le club a t'il été rentré
        $ImageClub = htmlspecialchars($_POST['ImageClubs']);

        //traitement des tags
        $tags_array = array(
            'logo'      => $_POST['tags'][0],
            'dessin'    => $_POST['tags'][1],
            'affiche'   => $_POST['tags'][2], 
            'typo'      => $_POST['tags'][3],
            'divers'    => $_POST['tags'][4]
        );

        /***************************************************\
                            TRAITEMENT SQL
        \***************************************************/
        


    }
?>