document.onkeyup = KeyCheck;       
          
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
			case 37 : //arrow left

				if (comicsID > 1) {comicsID--;}
				else{ comicsID = 1;}

				str_comicsID = comicsID.toString();
				console.log(str_comicsID);
				window.location = "index.php?comicsID="+str_comicsID;
			break;
		
			case 39 : // arrow right

				if (comicsID >= 1) {comicsID++;}
				else{comicsID = 1;}
				
				str_comicsID = comicsID.toString();
				console.log(str_comicsID);
				window.location = "index.php?comicsID="+str_comicsID;
			break;
		}
    }