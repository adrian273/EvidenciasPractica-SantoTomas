	

	var arrSources  = new Array();

	var arrObjs     = new Array();

	

	function drawGrid ( source, target, editable ) {

	  

    	arrObjs[moduletabctrl] = new dhtmlXGridObject(target);

    	arrObjs[moduletabctrl].setImagePath(gridImgPath);

    	arrObjs[moduletabctrl].setSkin("light");

    	arrObjs[moduletabctrl].setEditable(editable);

    	arrObjs[moduletabctrl].loadXML(source);

    	

    	arrSources[moduletabctrl] = source;

    	

    	//arrObjs[moduletabctrl].enableDragAndDrop(true);

	  

	}

	

	function drawReGrid ( source, target, objname ) {

	   

	  	if (!arrObjs[objname]) {

	  		

	    	arrObjs[objname] = new dhtmlXGridObject(target);

	    	arrObjs[objname].setImagePath(gridImgPath);

	    	arrObjs[objname].setSkin("light");

	    	arrObjs[objname].loadXML(source);

//	    	arrObjs[objname].setEditable(false);

	    	

	    	arrSources[objname] = source;

	  		

	  	} else {

	  		

	  		if (arrSources[objname] != source) { 

	  			arrSources[objname] = source;

	  		}

//			arrObjs[objname].clearAll();

			arrObjs[objname].updateFromXMl(arrSources[objname], false, false, arrObjs[objname].clearAll());

			arrObjs[objname].setSizes();

	  		

	  	}

		

	}	

	

	alert(223344);

 	

	var gridImgPath = www + "style/javascript/grid/imgs/";