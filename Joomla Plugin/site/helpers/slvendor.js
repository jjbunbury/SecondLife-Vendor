function ajaxRequest(id, url, method, content)
{
	var xhr_object = null;
		
	if ( typeof( window.XMLHttpRequest ) != 'undefined' ) {  	//FIREFOX AND OPERA
		try {
			xhr_object = new XMLHttpRequest();
		} catch( e ) { }
	} else {
		try {													//INTERNET EXPLORER, DIFFERENT VERSIONS
			xhr_object = new ActiveXObject( 'Microsoft.XMLHTTP' );
		} catch( e ) {
			xhr_object = new ActiveXObject( 'Msxml2.XMLHTTP' );
		}
	}		
	xhr_object.open(method, url, true); 
		 
	xhr_object.onreadystatechange = function() { 
		if(xhr_object.readyState == 4) {
			var resultat = xhr_object.responseText;
			if (document.getElementById(id).innerHTML != resultat) {
				document.getElementById(id).innerHTML = resultat;
			}
		//window.setTimeout(ajaxRequest(id, url, method, content), 90000);
		}
	} 
	 
	xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); 
	var data = ""; 
	xhr_object.send(data);
}