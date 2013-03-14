/*

*/
function getXMLHttpRequest() {
	var xhr = null;
	if (window.XMLHttpRequest || window.ActiveXObject) {
		if (window.ActiveXObject) {
			try {
				xhr = new ActiveXObject("Msxml2.XMLHTTP");
			} catch(e) {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
		} else {
			xhr = new XMLHttpRequest(); 
		}
	} else {
		alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
		return null;
	}
	return xhr;
}

function urldecode(str) {
	return stripslashes(unescape(str.replace(/\+/g, " ")));
}

function stripslashes (str) {
	return (str + '').replace(/\\(.?)/g, function (s, n1) {
    switch (n1) {
        case '\\':
            return '\\';
        case '0':
			return '\u0000';
        case '':
            return '';
        default:
            return n1;        
	}
    });
}