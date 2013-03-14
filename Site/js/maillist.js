function maillist(bcc) {
	/*****************************************************************
	Genere et affiche la maillist si elle n'est pas deja affichee.
	L'efface sinon.
	*****************************************************************/
	var p = document.getElementById('maillist');
	
	if(p.innerHTML=='') {
		var inputs = document.getElementsByTagName('input');
		p.innerHTML = '';
		var maillist = '';
		for(var i=1 ; i<inputs.length ; i++) {
			if(inputs[i].getAttribute('type')=='hidden') {
				maillist += inputs[i].value+', ';
			}
			if(i%400==0) {
				add_maillist(p, maillist, bcc);
				maillist = '';
			}
		}
		
		add_maillist(p, maillist, bcc);
		var results = document.getElementById('results');
		results.parentNode.insertBefore(p, results);
		
		// Mise a jour des statistiques :
		var search = document.getElementById('search').value;
		switch(search) {
			case '*':
				search = '  ';
			break;
			case 'etudiants':
				search = '  ';
			break;
		}
		if(search.length>1) {
			var xhr = getXMLHttpRequest();
			xhr.onreadystatechange = function() {};
			xhr.open("GET", "ajax/search.php?search="+escape(search)+"&maillist=1");
			xhr.send();
		}
		
	} else {
		p.innerHTML = '';
	}
}

function add_maillist(p, maillist, bcc) {
	maillist = maillist.slice(0, -2);
	if(bcc) {
		p.innerHTML += '<br/><a href="mailto:?&bcc='+maillist+'">'+maillist+'</a><br/>';
	} else {
		p.innerHTML += '<br/><a href="mailto:'+maillist+'">'+maillist+'</a><br/>';
	}
}

function clearMaillist() {
	/**********************
	Efface la maillist.
	**********************/
	document.getElementById('maillist').innerHTML = '';
}

function deleteButtonsMaillists() {
	/**********************
	Efface la maillist.
	**********************/
	var buttonMaillist = document.getElementById('buttonMaillist');
	if(buttonMaillist) buttonMaillist.parentNode.removeChild(buttonMaillist);
	buttonMaillist = document.getElementById('buttonMaillistBcc');
	if(buttonMaillist) buttonMaillist.parentNode.removeChild(buttonMaillist);
}

function addButtonsMaillists() {
	/********************************
	Ajoute les boutons "maillist".
	********************************/
	addButtonMaillist(false);
	addButtonMaillist(true);
}

function addButtonMaillist(bcc) {
	/********************************
	Ajoute un bouton "maillist".
	********************************/
	var listing = document.getElementById('maillist');
	var button = document.createElement('a');
	var textButton;
	button.setAttribute('class', 'button');
	if(bcc) {
		button.setAttribute('id', 'buttonMaillistBcc');
		button.setAttribute('onclick', 'maillist(true);');
		textButton = document.createTextNode('Listing d\'emails en copie caché');
	} else {
		button.setAttribute('id', 'buttonMaillist');
		button.setAttribute('onclick', 'maillist(false);');
		textButton = document.createTextNode('Listing d\'emails');
	}
	button.appendChild(textButton);
	listing.parentNode.insertBefore(button, listing);
}