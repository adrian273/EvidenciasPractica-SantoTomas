

function eXcell_linkc(cell){

 this.cell = cell;

 this.grid = this.cell.parentNode.grid;

 this.edit = function(){}

 this.getValue = function(){

 if(this.cell.firstChild.getAttribute)

 return this.cell.firstChild.innerHTML+"^"+this.cell.firstChild.getAttribute("href")

 else

 return "";

}

 this.setValue = function(val){

 var valsAr = val.split("^");

 this.setCValue("<a href='javascript:void()' onmouseup=\"if(confirm('"+valsAr[2]+"')) document.location='"+valsAr[1]+"'\" onclick='(isIE()?event:arguments[0]).cancelBubble = true;'>"+valsAr[0]+"</a>",valsAr);

}

}



eXcell_linkc.prototype = new eXcell;

eXcell_linkc.prototype.getTitle=function(){

 return this.cell.firstChild.getAttribute("href");

}





