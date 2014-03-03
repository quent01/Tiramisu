document.onkeyup = KeyCheck;       
          
    function KeyCheck(e)
    {
		var KeyID = (window.event) ? event.keyCode : e.keyCode;
        var img = '<?php echo $img ?>'; 
        img = user.permissions;	
        var str_img;
        console.log(img);
         
		switch(KeyID)
		{
			case 37 : //arrow left
				img--;
				str_img = img.toString();
				console.log(str_img);
				window.location = "index.php?comicsID="+str_img;
			break;
		
			case 39 : // arrow right
				img++;
				str_img = img.toString();
				console.log(str_img);
				window.location = "index.php?comicsID="+str_img;
			break;
		}
    }