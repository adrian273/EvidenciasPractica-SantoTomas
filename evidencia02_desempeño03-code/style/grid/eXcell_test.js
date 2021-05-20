//TEST

function eXcell_test(cell){

		try{

			this.cell = cell;

			this.grid = this.cell.parentNode.grid;

		}catch(er){}

		/**

		*	@desc: method called by grid to start editing

		*/

		this.edit = function(){

			 this.cell.atag=((!this.grid.multiLine)&&(_isKHTML||_isMacOS||(_FFrv<=1.7)))?"INPUT":"TEXTAREA";

			 this.val = this.getValue();

			 this.obj = document.createElement(this.cell.atag);

			 this.obj.style.height =(this.cell.offsetHeight-(this.grid.multiLine?5:4))+"px";

			 if(_isFF)this.obj.style.overflow="visible";

			 this.obj.className="dhx_combo_edit";

			 this.obj.wrap = "soft";

			 this.obj.style.textAlign = this.cell.align;

			 this.obj.onclick = function(e){(e||event).cancelBubble = true}

			 this.obj.value = this.val

			 this.cell.innerHTML = "";

			 this.cell.appendChild(this.obj);

			 this.obj.onselectstart=function(e){if(!e)e=event;e.cancelBubble=true;return true;};

			 this.obj.focus()

			 this.obj.focus()

			 this.obj.select();

		}

		/**

		*	@desc: get real value of the cell

		*/

		this.getValue = function(val){

			return this.cell.innerHTML.toString().replace(/\$|\,/g,'');

		}

		

		this.setValue = function(val){

			

			this.cell.innerHTML = formatCurrency(val);

		}

		

		/**

		*	@desc: this method called by grid to close editor

		*/

		this.detach = function(){

			this.setValue(this.obj.value);

			return this.val!=this.getValue();

		}

}

eXcell_test.prototype = new eXcell;