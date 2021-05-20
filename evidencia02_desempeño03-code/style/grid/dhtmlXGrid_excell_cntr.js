 

function eXcell_cntr(cell){

 this.cell = cell;

 this.grid = this.cell.parentNode.grid;

 this.edit = function(){}

 this.getValue = function(){

 return this.cell.parentNode.rowIndex;

}

 this.setValue = function(val){

 this.cell.style.paddingRight = "2px";

 var cell=this.cell;

 window.setTimeout(function(){

 cell.innerHTML = cell.parentNode.rowIndex;

 cell=null;

},100);

}

}

eXcell_cntr.prototype = new eXcell;



