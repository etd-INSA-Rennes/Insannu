function maillist(bcc) {
	/*****************************************************************
	Genere et affiche la maillist si elle n'est pas deja affichee.
	L'efface sinon.
	*****************************************************************/
	var p = $('#maillist');
	
	if(p.html()=='') {
		var inputs = $('input');
		p.html('');
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
		var results = $('#results');
		results.parent().insertBefore(p, results);
		
		// Mise a jour des statistiques :
		var search = $('#search').val();
		switch(search) {
			case '*':
				search = '  ';
			break;
			case 'etudiants':
				search = '  ';
			break;
		}
		if(search.length>1) {
			$.ajax({
	        	type: 'GET',
	        	url: 'ajax/search.php?search='+escape(search)+'&maillist=1'
        	});
		}
		
	} else {
		p.html('');
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
	$('#maillist').html('');
}

function deleteButtonsMaillists() {
	/**********************
	Efface la maillist.
	**********************/
	var buttonMaillist = $('#buttonMaillist');
	if(buttonMaillist) {
		buttonMaillist.parent().removeChild(buttonMaillist);
	}
	buttonMaillist = $('#buttonMaillistBcc');
	if(buttonMaillist) {
		buttonMaillist.parent().removeChild(buttonMaillist);
	}
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
	var listing = $('#maillist');
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
	listing.parent().insertBefore(button, listing);
}