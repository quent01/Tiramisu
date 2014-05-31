document.onkeyup = KeyCheck;       
	
	var ArrowLeft  = 37;
	var ArrowRight = 39;
	var comicsID_max = 7;

	function ChangeUrl(comicsID){
		str_comicsID = comicsID.toString();
		// console.log(str_comicsID);
		window.location = "index.php?comicsID="+str_comicsID;
	}

    function KeyCheck(e)
    {
		var KeyID = (window.event) ? event.keyCode : e.keyCode;
		// we get the current comicsID
		var url = window.location.search;
        var comicsID = url.substring(url.lastIndexOf("=")+1); 	
        var str_comicsID = comicsID.toString();
        // console.log("comicsID = " + str_comicsID);
        
        if(document.getElementsByTagName("body")[0].getAttribute("id")
        == "index"){

			switch(KeyID){
				case ArrowLeft :
					if(!comicsID) {
						comicsID = comicsID_max-1;
						ChangeUrl(comicsID)			
					}
					else if (comicsID > 1) {
						comicsID--;
						ChangeUrl(comicsID);
					}
				break;
			
				case ArrowRight : 
					if (comicsID >= 1 && comicsID < comicsID_max) {
						comicsID++;
						ChangeUrl(comicsID);	
					}
				break;
			}
		}
	}	