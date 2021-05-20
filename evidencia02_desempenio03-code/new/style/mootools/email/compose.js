document.addEvent('domready', function() {
 
	
 
	// Our instance for the element with id "demo-local"
	new Autocompleter.Local('recipients', tokens, {
		'minLength': 1, // We need at least 1 character
		'multiple':true,
		'selectMode': 'type-ahead', // Instant completion
		'onSelection' : function(a,b,c,d){
						//	this.element=htmlString(c);
						}
	});
/*
	var fillrecipients = function(emails){
		$('recipients').value=emails;
	}
*/
});
	function fillrecipients(emails){
		$('recipients').value=toHtml(emails);
	}
function toHtml(myString){     
htmlString = String(myString).split("&lt;").join("<");     
htmlString = htmlString.split("&gt;").join(">");     
htmlString = htmlString.split("&quot;").join("\"");     
htmlString = htmlString.split("&apos;").join("\'");     
return htmlString; 
}