	
	function loadTree ( id ) {
	  
	    setInner("tree_area", "");
		tree = new dhtmlXTreeObject("tree_area","100%","100%",0);
		tree.enableTreeLines(false);
		tree.setImagePath(icon_dir + "node/");
		tree.enableDragAndDrop(admin);
		tree.setDragHandler(doOnBeforeDrop);
		tree.setOnClickHandler(doOnSelect);
		tree.setDblClickAction(getHandles);
		tree.setXMLAutoLoading(uri_entry + "/core/expand");
		tree.loadXML(uri_entry + "/core/expand/" + id);
		tree.enableCheckBoxes(false);
		
		//if (id == "A") { setTimeout("tree.openAllItems(0)", 300); }
		
		
		setTimeout("tree.selectItem(" + myid + ")", 320);
		//tree.setOnRightClickHandler(rightclick);
	  
	}
 	
    function callModules ( id ) {
  		
	    if (cargaplane && id != "0" && document.getElementById('enabledrawv') && document.getElementById('enabledrawv').value) {
		   if (document.getElementById('loadstatus') && document.getElementById('loadstatus').style.display == 'none') {
		   	   loadXMLDoc(uri_entry + '/graph/getPlane/' + id, parseGraph);
		   }
	    }
	    
	  	lastGuide = moduletabctrl;
	  	arrMdlNum = new Array();
	  	currentNavegationArea = null;
	  	
	    // refreshing tab row
	    switchImg ("tab_s_1", style_base + "h1_2.gif");
	    moduletabctrl = null;
	    tabctrl = 1;
	    setInner('tab_row', '');
	    setInner('module_area', '');
	    setInner('module_aux', '');
	    if (id != "0") {
		    loadXMLDoc(uri_entry + '/nodemodule/displaymodules/' + id + '/' + cargamodule, parseAction);
	    }

    }
    var nodeidtemp = 0;
    function callModulesBySearch ( id ) {
    	
    	moduletabctrl = currentAdvSearchMdl;
    	if (!tree._globalIdStorageFind(id)) {
    		nodeidtemp=id;
    		loadXMLDoc(uri_entry + '/nodemodule/getAllParentNode/' + id, parseAction);
    		return;
    	} else {
  			tree.selectItem(id);
  			tree.getSelectedItemId();
  			callModules(id);
    	}
    	
    }
    
    function callTreeBySearch ( ids ) {
    	
		for (var i=0; i<arguments.length; i++) {
			if (tree._globalIdStorageFind(arguments[i])) {
				tree.loadXML(uri_entry + "/core/expand/"+arguments[i]+","+nodeidtemp, callModules(nodeidtemp));
				nodeidtemp=0;
				return;
			}
		}
    	
    }
	
	function getHandles (itemId) {
		switchDisplay("navegationarea", "none");
		ableNodeForm2 (itemId, '');
	}
	
	function addNode () {
		
		if (getValue("node_name")) {
			sendForm('node_form', uri_entry + "/nodemodule/addNode/" + getValue("node_father_id") + "/" + getValue("node_type_id"), parseAction);
			switchShowOne("nodeform");
		}
		
	}
	
	function editNode () {
		
		if (getValue("node_namee")) {
			sendForm('node_form', uri_entry + "/nodemodule/editNode", parseAction);
			tree.setItemText(getValue("node_id"), getValue("node_namee"));
			switchShowOne("nodeform2");
		}
		
	}
	
	function ableNodeForm ( node_father_id ) {
		
		if (switchShowOne("nodeform")) {
			node_form.reset();
			setInner('node_type_id', '');
			switchShow('trnodetypelibrary', 'trnodetype');
			//ajaxComboBox(uri_entry + "/nodetype/getNodeType/", "", "node_type_id");
			ajaxComboBox(uri_entry + "/nodetypelibrary/getList2/" + node_father_id, "", "node_type_library_id");
			
			tree.openItem(node_father_id);
			setFocus("node_name");
			setValue("node_father_id", node_father_id);
		} else {
			switchShowOne("nodeform");
		}
		
	}
	
	function ableNodeForm2 ( node_id, display ) {
			
			if (document.getElementById("node_form")) {
			    switchDisplay("nodeform2", display);
				node_form.reset();
				if (display != "none") { setFocus("node_namee"); }
				setValue("node_namee", tree.getItemText(node_id));
				setValue("node_id", node_id);				
			}
		
	}	
	
	//what to do when item selected
	var currentNavegationAreaClicked = null;
	function doOnSelect(itemId){
  		
  		if (currentNavegationAreaClicked != null && tree.getParentId(itemId) == myid) {
  			loadXMLDoc(uri_entry + '/graph/updateNavegatorPlane/' + tree.getParentId(itemId) + '/' + itemId + "/" + currentNavegationAreaClicked.getAttribute("id"), parseGraph);
  			return false;
  		} else if (currentNavegationAreaClicked != null && tree.getParentId(itemId) == myid) {
  			return false;
  		}
  		
  		switchDisplay("navegationarea", "none");
  		return isFreeToLoadModule(itemId);
		
	}
	
	function isFreeToLoadModule (itemId) {
		
  		if (xmlLoadMdlBusy == true) {
  			tree.selectItem(myid);
  			return false;
  		} else if (xmlLoadMdlBusy == false) {
			xmlLoadMdlBusy = true;
			ableNodeForm2 ('', 'none');
			callModules(itemId);
			setValue("node_father_id", itemId);
			myid = itemId;
			return true;
  		}
		
	}
	
	function rightclick ( itemId ) {
		
		tree.setItemStyle(itemId, "standartTreeRow");
        tree.enableDragAndDrop("temporary_disabled");
		tree.setItemText(itemId, "<input id=nodeedit class=stxt type=text value=\"" + tree.getItemText(itemId) + "\">");
		setFocus("nodeedit");
		
	}
	
	//delete item (from database)
	function deleteNode () {
		
		if (tree.getSelectedItemId() && confirm(msg_del)){
			doDeleteTreeItem(tree.getSelectedItemId());
		}
		
	}
	
	//remove item from tree
	function doDeleteTreeItem ( id ) {

		var pId = tree.getParentId(id);
		getXML(uri_entry + "/nodemodule/deleteNode/" + id);
		tree.deleteItem(id);
		callModules(0);
		switchDisplay("navegationarea", "none");
			
	}	
	
	//save moved (droped) item to db. Cancel drop if save failed or item is new
	function doOnBeforeDrop ( id, parentId ) {
		var dropSaver = new dtmlXMLLoaderObject(null,null,false); // sync mode
		dropSaver.loadXML(uri_entry + "/nodemodule/moveNode/" + id + "/" + parentId);
		var root = dropSaver.getXMLTopNode("tree");
		var id  = root.getAttribute("id");
		var msg = root.getAttribute("msg");
        xmlBusy=false;
        switchDisplay('load', 'none');
		if(id == 0){
			alert(msg);
			return false;
		}
		return true;
	}
	
	function viewPlanH ( path ) {
		
		popup(uri_entry + '/graph/getPlaneHistoric/' + path, '', 600, 600)
		
	}
	
	var mytime = "";
	var myid   = null;
	var xmlLoadMdlBusy = false;
				 