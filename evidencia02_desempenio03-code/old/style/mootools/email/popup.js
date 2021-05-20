document.addEvent('domready', function() {
	var finish = function(){
		ret = '';
		$$('#msg_to option').each(function(el){
			if(el.selected)ret+=el.innerHTML.standardize()+", ";
		});
		w=window.opener;
		w.fillrecipients(ret);
		close();
	} 
	var close = function(){
		window.close();
	}
 
	$('finish').addEvent('click',finish);
	$('cancel').addEvent('click',close);
 
});
