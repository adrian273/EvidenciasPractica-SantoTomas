
document.write("<script src='" + _js_prefix + "calendar/YAHOO.js'></script>");
document.write("<script src='" + _js_prefix + "calendar/event.js'></script>");
document.write("<script src='" + _js_prefix + "calendar/calendar.js'></script>");
document.write("<script src='" + _js_prefix + "calendar/calendar_init.js'></script>");
document.write("<link rel='stylesheet' type='text/css' href='" + _js_prefix + "calendar/calendar.css'></link>");
GRID_CALENDAR_DATE_MONTH = "MM";
GRID_CALENDAR_DATE_YEAR = "yyyy";
GRID_CALENDAR_DATE_DAY = "dd";
GRID_CALENDAR_OPEN_DATE_TEXT = "[OPEN]";
function eXcell_calendar(cell) {
	try {
		this.cell = cell;
		this.grid = this.cell.parentNode.grid;
	}
	catch (er) {
	}
	this.getDateFieldDelimiter = function () {
		var k = 0;
		while (this.cell._dateFormatMask.charAt(k) == GRID_CALENDAR_DATE_DAY.charAt(0) || this.cell._dateFormatMask.charAt(k) == GRID_CALENDAR_DATE_MONTH.charAt(0) || this.cell._dateFormatMask.charAt(k) == GRID_CALENDAR_DATE_YEAR.charAt(0)) {
			k++;
		}
		return this.cell._dateFormatMask.charAt(k);
	};
	this.getOpenDate = function () {
		return (this.cell._dateFormatMask).replace(GRID_CALENDAR_DATE_MONTH, "12").replace(GRID_CALENDAR_DATE_DAY, "12").replace(GRID_CALENDAR_DATE_YEAR, "9999");
	};
	this.getMDYMonthPosition = function () {
		var aMask = this.cell._dateFormatMask.split(this.cell._DateFieldDelimiter);
		var k = 0;
		while (aMask[k] != GRID_CALENDAR_DATE_MONTH) {
			k++;
		}
		return k + 1;
	};
	this.getMDYDayPosition = function () {
		var aMask = this.cell._dateFormatMask.split(this.cell._DateFieldDelimiter);
		var k = 0;
		while (aMask[k] != GRID_CALENDAR_DATE_DAY) {
			k++;
		}
		return k + 1;
	};
	this.getMDYYearPosition = function () {
		var aMask = this.cell._dateFormatMask.split(this.cell._DateFieldDelimiter);
		var k = 0;
		while (aMask[k] != GRID_CALENDAR_DATE_YEAR) {
			k++;
		}
		return k + 1;
	};
	// current cell formatting
	this.cell._dateFormatMask = this.grid._formats[this.cell._cellIndex];
	this.cell._DateFieldDelimiter = this.getDateFieldDelimiter();
	this.cell._MDYMonthPosition = this.getMDYMonthPosition();
	this.cell._MDYDayPosition = this.getMDYDayPosition();
	this.cell._MDYYearPosition = this.getMDYYearPosition();
	this.cell._MDMonthPostion = 1;
	this.cell._MDDayPostion = 2;
	this.cell._DateDelimiter = ",";
	this.cell._DateRangeDelimiter = "~";
	this.cell._OpenDate = this.getOpenDate();
	this.edit = function () {
		this.cell._cediton = true;
		this.val = this.cell.val;
		this.cell.atag = ((!this.grid.multiLine) && (_isKHTML || _isMacOS || _isFF)) ? "INPUT" : "TEXTAREA";
		this.obj = document.createElement(this.cell.atag);
		this.obj.className = "dhx_combo_edit";
		this.obj.value = this.getValue();
		this.cell.innerHTML = "";
		this.cell.appendChild(this.obj);
		this.obj.parent = this;
		this.obj.onselectstart = function (e) {
			if (!e) {
				e = event;
			}
			e.cancelBubble = true;
			return true;
		};
		this.obj.focus();
		// shows the popup calendar
		_grid_calendar_init(this.cell);
		this.obj.onclick = function (e) {
			_grid_calendar_init(this.parent.cell);
			var arPos = this.parent.getPosition(this.parent.cell);
			var pval = this.parent._date2str2(this.parent.cell.val || new Date());
			window._grid_calendar.render(arPos[0], arPos[1] + this.parent.cell.offsetHeight, this.parent, pval);
			dhtmlxEvent(window._grid_calendar.table.parentNode.parentNode, "click", function (e) {
				(e || event).cancelBubble = true;
			});
			window._grid_calendar.last_operation_calendar = false;
			(e || event).cancelBubble = true;
		};
	};
	this.getValue = function () {
		if (this.cell.val) {
			return this._date2str2(this.cell.val);
		}
		return this.cell.innerHTML.toString()._dhx_trim();
	};
	// raised after selecting a date in the popup calendar
	this.detach = function () {
		if (!window._grid_calendar) {
			return;
		}
		if (this.cell._cediton) {
			this.cell._cediton = false;
		} else {
			return;
		}
		var old = this.cell.val;
		if (window._grid_calendar._last_operation_calendar) {
			this.setValue(window._grid_calendar.getSelectedDates()[0]);
			window._grid_calendar._last_operation_calendar = false;
		} else {
			this.setValue(this.obj.value);
		}
		window._grid_calendar.hide();
		return old.valueOf() != this.cell.val.valueOf();
	};
	this._2dg = function (val) {
		if (val.toString().length == 1) {
			return ("0" + val.toString());
		}
		return val;
	};
	// parse a string date using current cell formating
	this._parseDate = function (sDate) {
		var aDate = sDate.split(this.cell._DateFieldDelimiter);
		var rArray;
		if(sDate.length == 6) {
			rArray = [sDate.slice(4,6),sDate.slice(0,2),sDate.slice(2,4)];
		}
		else
		{
			if (aDate.length == 2) {
				rArray = [aDate[this.cell._MDMonthPostion - 1], aDate[this.cell._MDDayPostion - 1]];
			} 
			else{
				rArray = [aDate[this.cell._MDYYearPosition - 1], aDate[this.cell._MDYMonthPosition - 1], aDate[this.cell._MDYDayPosition - 1]];
			}
		}
		if(rArray[0] && rArray[0].length == 2)
		{
			if(parseInt(rArray[0]) > 27)
			{
				rArray[0] = "19" + rArray[0];
			}
			else
			{
				rArray[0] = "20" + rArray[0];
			}
		}
		return rArray;
	};
	// convert a date field array to a date
	this._toDate = function (dateFieldArray) {
		if (dateFieldArray instanceof Date) {
			return dateFieldArray;
		} else {
			return new Date(dateFieldArray[0], dateFieldArray[1] - 1, dateFieldArray[2]);
		}
	};
	// convert a string to a date
	this._str2date = function (sDate) {
		return this._toDate(this._parseDate(sDate));
	};
	// convert a date to a string
	this._date2str2 = function (z) {
		return (this.cell._dateFormatMask).replace(GRID_CALENDAR_DATE_MONTH, this._2dg((z.getMonth() * 1 + 1))).replace(GRID_CALENDAR_DATE_DAY, this._2dg(z.getDate())).replace(GRID_CALENDAR_DATE_YEAR, this._2dg((z.getFullYear() * 1)));
	};
}
eXcell_calendar.prototype = new eXcell;
eXcell_calendar.prototype.setValue = function (val) {
	if (!val || val.toString()._dhx_trim() == "") {
		val = "";
	}
	this.cell.val = (val instanceof Date) ? val : this._str2date(val);
	if ((this.cell.val == "NaN") || (this.cell.val == "Invalid Date")) {
		this.cell.val = "";
		this.setCValue("&nbsp;", 0);
	} else {
		if (this._date2str2(this.cell.val) == this.cell._OpenDate) {
			this.setCValue(GRID_CALENDAR_OPEN_DATE_TEXT, this.cell.val);
		} else {
			this.setCValue(this._date2str2(this.cell.val), this.cell.val);
		}
	}
};