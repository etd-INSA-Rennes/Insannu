function maillist(bcc) {
	/*****************************************************************
	Genere et affiche la maillist si elle n'est pas deja affichee.
	L'efface sinon.
	*****************************************************************/
	var p = $('#maillist');
	
	if(p.html()=='') {
		// Retrieve email addresses from inputs:
		var emails = new Array();
		var inputs = $('input');
		for(var i=1; i<inputs.length; i++) {
			if(inputs[i].getAttribute('type') == 'hidden') {
				emails.push(inputs[i].value);
			}
		}
		// Retrieve email addresses from stack:
		var stack = $('#stack').html();
		var pattern = /type="hidden" value="(.+?)"/g;
		pattern.compile(pattern);
		while(email = pattern.exec(stack)) {
			emails.push(email[1]);
		}

		p.html('');
		var maillist = '';
		for(var i=0; i<emails.length; i++) {
			maillist += emails[i] + ', ';
			if(i%400==399) {
				addMaillist(p, maillist, bcc);
				maillist = '';
			}
		}
		
		addMaillist(p, maillist, bcc);
		
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

function addMaillist(p, maillist, bcc) {
	maillist = maillist.slice(0, -2);
	if(bcc) {
		p.html(p.html()+'<br/><a href="mailto:?&bcc='+maillist+'">'+maillist+'</a><br/>');
	} else {
		p.html(p.html()+'<br/><a href="mailto:'+maillist+'">'+maillist+'</a><br/>');
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
		buttonMaillist.remove();
	}
	buttonMaillist = $('#buttonMaillistBcc');
	if(buttonMaillist) {
		buttonMaillist.remove();
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
	if(bcc) {
		var html = '<a class="button" id="buttonMaillistBcc" onclick="maillist(true);">Listing d\'emails en copie cachée</a>';
	} else {
		var html = '<a class="button" id="buttonMaillist" onclick="maillist(false);">Listing d\'emails</a>';
	}
	$('#maillist').before(html);
}
