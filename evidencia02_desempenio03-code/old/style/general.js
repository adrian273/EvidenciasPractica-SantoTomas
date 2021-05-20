
	function getWindowHeight() {
	  var myWidth = 0, myHeight = 0;
	  if( typeof( window.innerWidth ) == 'number' ) {
	    //Non-IE
	    myWidth = window.innerWidth;
	    myHeight = window.innerHeight;
	  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
	    //IE 6+ in 'standards compliant mode'
	    myWidth = document.documentElement.clientWidth;
	    myHeight = document.documentElement.clientHeight;
	  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
	    //IE 4 compatible
	    myWidth = document.body.clientWidth;
	    myHeight = document.body.clientHeight;
	  }
	  return myHeight;
	}
	
	function getWindowWidht() {
	  var myWidth = 0, myHeight = 0;
	  if( typeof( window.innerWidth ) == 'number' ) {
	    //Non-IE
	    myWidth = window.innerWidth;
	    myHeight = window.innerHeight;
	  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
	    //IE 6+ in 'standards compliant mode'
	    myWidth = document.documentElement.clientWidth;
	    myHeight = document.documentElement.clientHeight;
	  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
	    //IE 4 compatible
	    myWidth = document.body.clientWidth;
	    myHeight = document.body.clientHeight;
	  }
	  return myWidth;
	}

  	function include(arquivo){

	  	var novo = document.createElement("script");
	  	if(navigator.appName.indexOf("Microsoft")!=-1) {
	  		var doce = document.getElementsByTagName('head').item(0);
	  	} else {
	  		var doce = document.getElementsByTagName('head').item(0);
	  	}
	  	novo.setAttribute('type', 'text/javascript');
	  	novo.setAttribute('src', arquivo);
	  	doce.appendChild(novo);
	  	
	  	
  	}

	function switchDisplay ( id ) {

		obj = document.getElementById(id);

		if (obj.style.display == 'none') {
			obj.style.display= '';
		} else {
			obj.style.display= 'none';
		}

	}
	
    function switchImg (target, source) {
            
        if (document.images[target]) {
                var cur_img = document.images[target].src;
                document.images[target].src = source;
                return cur_img;
        }

    }
	
	function switchDisplay2 ( id, estatus ) {

		if (document.getElementById(id)) {
			return document.getElementById(id).style.display=estatus;
		}

	}
	
	function catchKey ( code, ev ) {
	  	
	  	var key = (window.event) ? window.event.keyCode : ev.keyCode;
	  	
	  	 if (key == code) {
	  	  	 return true;
	  	 } else {
	  	  	 return false;
	     }
	  	
	}
	
	function changeBackGround ( obj, color ) {
	  	
	  	obj.style.backgroundColor=color;
	  	
	}

	function getValue ( id ) {
		
		if (document.getElementById(id)) {
			return document.getElementById(id).value;
		}
		
	}
	
	function getText ( id )  {
		
		if (document.getElementById(id)) {
			var w = document.getElementById(id).selectedIndex;
			var selected_text = document.getElementById(id).options[w].text;
			return selected_text;
		}
		
	}
	
	function setValue ( id, val ) {
		
		if (document.getElementById(id)) {
			return document.getElementById(id).value = val;
		}
		
	}
	
	function setDisabled ( target, estatus ) {
		
		if (document.getElementById(target)) {
			document.getElementById(target).disabled = estatus;
		}
		
	}
	
	function setInner ( target, value ) {
		
		if (document.getElementById(target)) {
			document.getElementById(target).innerHTML = value;
		}
		
	}
	
	function getInner ( target ) {
		
		if (document.getElementById(target)) {
			return document.getElementById(target).innerHTML;
		}
		
		return false;
		
	}
	
	function isChecked ( target ) {
		
	   for (i=0; i<document.forms[0].elements.length; i++) {
	      if (document.forms[0].elements[i].id == target) {
	         return document.forms[0].elements[i].checked;
	      }
	   }
	   return false;
	}
	
	function setCheck ( target, stt ) {
		
	   for (i=0; i<document.forms[0].elements.length; i++) {
	      if (document.forms[0].elements[i].id == target) {
	         document.forms[0].elements[i].checked = stt;
	      }
	   }
		
	}
	
	function setFocus (target) {
	
			document.getElementById(target).focus();
	
	}
	
	function setClass (target, classn) {
	
			document.getElementById(target).className = classn;
	
	}
	
    // url_encode version 1.0  
    function url_encode(str) {  
        var hex_chars = "0123456789ABCDEF";  
        var noEncode = /^([a-zA-Z0-9\_\-\.])$/;  
        var n, strCode, hex1, hex2, strEncode = "";  

        for(n = 0; n < str.length; n++) {  
            if (noEncode.test(str.charAt(n))) {  
                strEncode += str.charAt(n);  
            } else {  
                strCode = str.charCodeAt(n);  
                hex1 = hex_chars.charAt(Math.floor(strCode / 16));  
                hex2 = hex_chars.charAt(strCode % 16);  
                strEncode += "%" + (hex1 + hex2);  
            }  
        }  
        return strEncode;  
    } 
    
	function selectAllOptions ( selectbox ) {
		if (!document.getElementById(selectbox)) { return }

		var ele = document.getElementById(selectbox);
		for(var i=0; i<document.getElementById(selectbox).length; i++) {
			ele.options[i].selected = true;
		}
	}
	
	function checkAll ( checkname, value ){ 
	   for (i=0; i<document.gform.elements.length; i++) {
	      if (document.gform.elements[i].type == "checkbox" && document.gform.elements[i].name == checkname) {
	         document.gform.elements[i].checked = value;
	      }
	   }
	} 
	
	function clearOptions ( selectbox ) {
        if (!document.getElementById(selectbox)) { return }
        var i;
        for (i = document.getElementById(selectbox).options.length-1; i>=0; i--) {
                document.getElementById(selectbox).remove(i); 
        }

	}
	
	function collapse ( mode ) {
	
		if (mode == 1) {
			
			opacity('leftbar', 100, 0, 900);
			setTimeout("switchDisplay('leftbar');switchDisplay('decollpase');document.getElementById('contentframe').colSpan='2'", 1000);
		
		} else {
		
			switchDisplay('leftbar');switchDisplay('decollpase');document.getElementById('contentframe').colSpan='1';
			opacity('leftbar', 0, 100, 900);
			
		}
	}

    function getMSelectValue ( obj ) {
                    
            var aux = new Array();
            var ele = document.getElementById(obj);
            var pointer = 0;
            for(var i=0; i<document.getElementById(obj).length; i++) {
                    if (ele.options[i].selected) {
                            aux[pointer] = ele.options[i].value;
                            pointer++;
                    }
            }
            return aux.join(',');
    }
    
	function selectOption ( obj, values ) {
		
		var aux = values.split(",");
		var ele = document.getElementById(obj);
		
		for(var i=0; i<document.getElementById(obj).length; i++) {
			for(var b=0; b<aux.length; b++) {
				if (ele.options[i].value == aux[b]) {
					ele.options[i].selected = true;
				}
			}
		}
		
	}
    
	defaultStep=15 
	step=defaultStep 
	
	function scrollDivDown(id){ 
		
		document.getElementById(id).scrollTop+=step 
		
	} 
	
	function scrollDivUp(id) { 
		
		document.getElementById(id).scrollTop-=step 
		
	} 

	
	jsdebug = false;
	var submitCheck = false;
	
function chkform(val){ 
	if(val){
		document.termsform.submit.disabled='';
	}else{
		document.termsform.submit.disabled='disabled';
	}
}
	
	//vanlt add
	function setCheckedValue(target, newValue) {
		
		  

		    	 var radioObj=document.getElementsByName(target);
		  		var radioLength = radioObj.length;
		  		if(radioLength == undefined) {
		  			radioObj.checked = (radioObj.value == newValue.toString());
		  			return;
		  		}
		  		for(var i = 0; i < radioLength; i++) {
		  			radioObj[i].checked = false;
		  			if(radioObj[i].value == newValue.toString()) {
		  				radioObj[i].checked = true;
		  			}
		  		}
		    	  

		
	}
	
	//end
	
	
	function insertValueQuery ( field, text ) {
		var myQuery = document.getElementById(field);
		
		//IE support
		if (document.selection) {
		    myQuery.focus();
		    sel = document.selection.createRange();
		    sel.text = text;
		    document.getElementById(field).focus();
		}
		//MOZILLA/NETSCAPE support
		else if (document.getElementById(field).selectionStart || document.getElementById(field).selectionStart == "0") {
		    var startPos = document.getElementById(field).selectionStart;
		    var endPos = document.getElementById(field).selectionEnd;
		    var chaineSql = document.getElementById(field).value;
		
		    myQuery.value = chaineSql.substring(0, startPos) + text + chaineSql.substring(endPos, chaineSql.length);
		} else {
		    myQuery.value += text;
		}
	}


$.fn.getType = function(){ return this[0].tagName == "INPUT" ? $(this[0]).attr("type").toLowerCase() : this[0].tagName.toLowerCase(); }