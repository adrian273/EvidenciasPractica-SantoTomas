
	function ajaxErrorMsg () {
		
		alert("XML Error: " + ajaxXmlHttp.status);
		
	}
	
	function ajaxParseXML() {
	   if(ajaxXmlHttp.readyState==4) {
	        if(ajaxXmlHttp.status==200) {
	            xmlDoc = ajaxXmlHttp.responseXML;
	
	            if(ajaxDebug==true) alert(ajaxXmlHttp.responseText);
	            t = xmlDoc.documentElement;
	            for(i=0;i<t.childNodes.length;i++) {
	                n = t.childNodes[i];
	
	                if(n.nodeType == 3) continue;
	
	                if(!document.getElementById(n.nodeName)) continue;
	                obj = document.getElementById(n.nodeName);
	
	                for(x=0;x<n.childNodes.length;x++) {
	                   if(n.childNodes[x].nodeType == 3) continue;
	                    obj.innerHTML = n.childNodes[x].nodeValue;
	                }
	            }
	        } else {
	            ajaxErrorMsg();
	        }
	        ajaxBusy = false;
	    }
	}
	
	function ajaxCombox() {
	   if(ajaxXmlHttp.readyState==4) {
	        if(ajaxXmlHttp.status==200) {
		        xmlDoc = ajaxXmlHttp.responseXML;
		        if (typeof(ajaxSelectBox) == 'string') {
		        	ajaxSelectBox = ajaxSelectBox.split(',');
		        }
		        
		        if(ajaxDebug==true) alert(ajaxXmlHttp.responseText);
		        var __options = xmlDoc.getElementsByTagName("option");
		        
		        for(var index=0; index<ajaxSelectBox.length; index++) {
		        	document.getElementById(ajaxSelectBox[index]).innerHTML = "";
		        }
		        

		        
		        for (var i = 0; i < __options.length; i++) {
		            
			        for(var index=0; index<ajaxSelectBox.length; index++) {
			            newOption = createOption(__options[i]);
				        document.getElementById(ajaxSelectBox[index]).appendChild(newOption);
			        }
		            
		        }
		        
		        for(var index=0; index<ajaxSelectBox.length; index++) {
			        if (document.getElementById(ajaxSelectBox[index]).onfocus) {
			        	document.getElementById(ajaxSelectBox[index]).qqqq();
			        	document.getElementById(ajaxSelectBox[index]).blur();
			        }
		        }
		        
	        } else {
	            ajaxErrorMsg();
	        }
	        ajaxBusy = false;
	    }
	}
	
	function createOption(option) {		
	    var newOption = document.createElement("option");
    	var text = document.createTextNode(option.childNodes[0].data);
    	var value = option.getAttribute("value");
    	var selected = option.getAttribute("selected");
	    
	    newOption.setAttribute("value", value);
	    if (selected) {
	    	newOption.setAttribute("selected", selected);
	    }
	    newOption.appendChild(text);
	    return newOption;
	    
	}

	function ajaxParseAction () {
	   if(ajaxXmlHttp.readyState==4) {
	        if(ajaxXmlHttp.status==200) {
	            xmlDoc = ajaxXmlHttp.responseXML;
	            if(ajaxDebug==true) alert(ajaxXmlHttp.responseText);
	            t = xmlDoc.documentElement;
	            for(i=0;i<t.childNodes.length;i++) {
	                
	                tag = t.childNodes[i];
	                func = tag.nodeName;
	                
					switch (func) {
						/*
						case 'alert':
							alert(tag.childNodes[0].nodeValue);
							break;
						*/
						case 'alert':
							alert(tag.childNodes[0].data);
							break;
													
						case 'focus':
							setFocus(tag.getAttribute("id"));
							break;
							
						case 'inner':
							setInner(tag.getAttribute("id"), tag.getAttribute("cdata"));
							break;

						case 'xml':
							getXML(tag.getAttribute("url"));
							break;
							
						case 'setvalue':
							setValue(tag.getAttribute("id"), tag.getAttribute("value"));
							break;
							
						case 'js':
							eval(tag.getAttribute("script"));
							break;

						case 'js2':
							str = "opener.document." + tag.getAttribute("script");
							eval(str);
							break;
							
						default:
							break;
					}


	            }
	        } else {
	            ajaxErrorMsg();
	        }
	        ajaxBusy = false;
	    }
	}

	function ajaxParseFormFill () {
	   if(ajaxXmlHttp.readyState==4) {
	        if(ajaxXmlHttp.status==200) {
	            xmlDoc = ajaxXmlHttp.responseXML;
	
	            if(ajaxDebug==true) alert(ajaxXmlHttp.responseText);
	            t = xmlDoc.documentElement;
	            for(i=0;i<t.childNodes.length;i++) {
	                
	                tag = t.childNodes[i];
	                func = tag.nodeName;
	                
					switch (func) {
						case 'text':
							setValue(tag.getAttribute("target"), tag.getAttribute("value"));
							break;
													
						case 'logic':
							(tag.getAttribute("value") == 0 ? switchDisplay(tag.getAttribute("target"), '') : switchDisplay(tag.getAttribute("target"), 'none'));
							break;
							
						case 'inner':
							setInner(tag.getAttribute("id"), tag.getAttribute("cdata"));
							break;

						case 'xml':
							getXML(tag.getAttribute("url"));
							break;	
							
						case 'js':
							eval(tag.getAttribute("script"));
							break;
							
						default:
							break;
					}


	            }
	        } else {
	            ajaxErrorMsg();
	        }
	        ajaxBusy = false;
	    }
	}