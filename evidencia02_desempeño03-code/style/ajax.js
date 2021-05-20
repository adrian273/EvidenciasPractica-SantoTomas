function recallAjax(){

    ajax(ajaxLastUrl, ajaxLastFun, ajaxLastFrm);
    
}

function ajax(url, func, form){

    ajaxLastUrl = url;
    ajaxLastFun = func;
    ajaxLastFrm = form
    
    if (ajaxBusy) {
    
        setTimeout("recallAjax()", 300);
        
    }
    else {
    
        ajaxBusy = true;
        if (form) {
            ajaxPostMethod(url, func, form);
        }
        else {
            if (!func) {
                func = ajaxParseXML;
            }
            ajaxGetMethod(url, func);
        }
        
    }
    
}

function ajaxComboBox(url, value, comboBox){

    ajaxSelectBox = comboBox;
    ajax(url + value, ajaxCombox);
    
}

function ajaxGetMethod(url, func){

    if (window.XMLHttpRequest) {
    
        ajaxXmlHttp = new XMLHttpRequest();
        ajaxXmlHttp.onreadystatechange = func;
        ajaxXmlHttp.open("GET", url, true);
        ajaxXmlHttp.send(null);
        
    }
    else 
        if (window.ActiveXObject) {
        
            ajaxXmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            
            if (ajaxXmlHttp) {
                ajaxXmlHttp.onreadystatechange = func;
                ajaxXmlHttp.open("GET", url, true);
                ajaxXmlHttp.send();
            }
        }
    
}

function ajaxPostMethod(url, func, form){

    if (!document.getElementById(form)) {
        alert("The form " + form + " doesn't exist");
        return;
    }
    
    elform = document.getElementById(form);
    sendVar = '';
    
    for (i = 0; i < elform.elements.length; i++) {
        ele = elform.elements[i];
        
        if (ele.type == "select-multiple") {
            if (ele.length) 
                for (j = 0; j < ele.length; j++) {
                    if (ajaxSendSelected && ele.options[j].selected) {
                        sendVar = sendVar + '&' + ele.name + '%5B%5D=' + ele.options[j].value;
                    }
                    else 
                        if (!ajaxSendSelected) {
                            sendVar = sendVar + '&' + ele.name + '%5B%5D=' + ele.options[j].value;
                        }
                }
        }
        
        if (ele.type == "select-one") {
            if (ele.value != '' && !ajaxSendNull) {
                sendVar = sendVar + '&' + ele.name + '=' + url_encode(ele.value);
            }
            else 
                if (ajaxSendNull) {
                    sendVar = sendVar + '&' + ele.name + '=' + url_encode(ele.value);
                }
        }
        
        if (ele.type == "checkbox") {
            if (ele.checked) {
                sendVar = sendVar + '&' + ele.name + '=' + ele.value;
            }
        }
        
        if (ele.type == "radio") {
            if (ele.checked) {
                sendVar = sendVar + '&' + ele.name + '=' + ele.value;
            }
        }
        
        if (ele.type == "text") {
            if (ele.value != '' && !ajaxSendNull) {
                sendVar = sendVar + '&' + ele.name + '=' + url_encode(ele.value);
            }
            else 
                if (ajaxSendNull) {
                    sendVar = sendVar + '&' + ele.name + '=' + url_encode(ele.value);
                }
        }
        
        if (ele.type == "textarea") {
            sendVar = sendVar + '&' + ele.name + '=' + url_encode(ele.value);
        }
        
        if (ele.type == "password") {
            sendVar = sendVar + '&' + ele.name + '=' + ele.value;
        }
        
        if (ele.type == "hidden") {
            if (ele.value != '') {
                sendVar = sendVar + '&' + ele.name + '=' + url_encode(ele.value);
            }
        }
        
    }
    
    if (window.XMLHttpRequest) {
    
        ajaxXmlHttp = new XMLHttpRequest();
        ajaxXmlHttp.onreadystatechange = func;
        ajaxXmlHttp.open("POST", url, true);
        ajaxXmlHttp.setRequestHeader("Method", "POST " + url + " HTTP/1.1");
        ajaxXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajaxXmlHttp.send(sendVar);
        
    }
    else 
        if (window.ActiveXObject) {
        
            ajaxXmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            
            if (ajaxXmlHttp) {
                ajaxXmlHttp.onreadystatechange = func;
                ajaxXmlHttp.open("POST", url, true);
                ajaxXmlHttp.setRequestHeader("Method", "POST " + url + " HTTP/1.1");
                ajaxXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                ajaxXmlHttp.send(sendVar);
            }
            
        }
    
}



//	------------------------------------------------------------

function $import(path){
    var i, base, src = "ajax.js", scripts = document.getElementsByTagName("script");
    for (i = 0; i < scripts.length; i++) {
        if (scripts[i].src.match(src)) {
            base = scripts[i].src.replace(src, "");
            break;
        }
    }
    document.write("<" + "script src=\"" + base + path + "\"></" + "script>");
}


var ajaxRequest = {


    postRequest: function(url, vars, values, handler){
    
        var params = this.paras(vars, values);
        
        xmlhttp = this.getxmlhttpObj();
        
        xmlhttp.open("POST", url, true);
        
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        
        xmlhttp.setRequestHeader("Content-length", params.length);
        
        xmlhttp.setRequestHeader("Connection", "close");
        
        
        xmlhttp.onreadystatechange = function(){
        
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            
                var data = xmlhttp.responseText;
                
                if (data != null) 
                    handler(data);
                
            }
        }
        xmlhttp.send(params);
        
    },
    
    getRequest: function(url, vars, values, handler){
    
        xmlhttp = this.getxmlhttpObj();
        
        /*url = url + "?" + this.paras(vars, values);*/
        
        xmlhttp.open("GET", url, true);
        
        
        xmlhttp.onreadystatechange = function(){
        
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            
                var data = xmlhttp.responseText;
                
                if (data != null) 
                    handler(data);
                
            }
        }
        xmlhttp.send(null);
        
    },
    
    paras: function(vars, values){
    
        var par = vars[0] + "=" + values[0];
        
        for (i = 1; i < vars.length; i++) {
        
            par += "&" + vars[i] + "=" + values[i];
            
        }
        return par;
        
    },
    
    getxmlhttpObj: function(){
    
        return (window.XMLHttpRequest) ? new XMLHttpRequest() : ((window.ActiveXObject) ? new ActiveXObject("Microsoft.XMLHTTP") : null);
        
    }
}


var ajaxLastUrl = null;
var ajaxLastFun = null;
var ajaxLastFrm = null;
var ajaxBusy = false;
var ajaxSendSelected = false;
var ajaxSendNull = false;
var ajaxDebug = false;
var ajaxSelectBox = null;
