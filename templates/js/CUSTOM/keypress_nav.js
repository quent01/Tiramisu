document.onkeyup = KeyCheck;       
          
	var ArrowLeft  = 37;
	var ArrowRight = 39;

    function KeyCheck(e)
    {
		var KeyID = (window.event) ? event.keyCode : e.keyCode;
		// we get the current comicsID
		var url = window.location.search;
        var comicsID = url.substring(url.lastIndexOf("=")+1); 	
        var str_comicsID = comicsID.toString();
        console.log(str_comicsID);
         
		switch(KeyID)
		{
			case ArrowLeft :

				if (comicsID > 1) {comicsID--;}
				else{ comicsID = 1;}

				str_comicsID = comicsID.toString();
				console.log(str_comicsID);
				window.location = "index.php?comicsID="+str_comicsID;
			break;
		
			case ArrowRight : 

				if (comicsID >= 1) {comicsID++;}
				else{comicsID = 1;}
				
				str_comicsID = comicsID.toString();
				console.log(str_comicsID);
				window.location = "index.php?comicsID="+str_comicsID;
			break;
		}
    }