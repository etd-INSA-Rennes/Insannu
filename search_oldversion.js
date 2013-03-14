var t;

function recherche() {
	/*********************************************************
	Interroge le serveur 0.3sec apres la derniere saisie.
	*********************************************************/
	var previous_search = document.getElementById('previous_search');
	var search = document.getElementById('search');
	if(previous_search.value!=search.value) {
		window.clearTimeout(t);
		t = window.setTimeout(askServeur, 300);
		previous_search.value = search.value;
	}
}

function askServeur() {
	/*************************************************************************
	Interroge le script PHP search.php pour obtenir la liste des resultats.
	Gere le gif de chargement.
	*************************************************************************/
	var search = document.getElementById('search').value;
	var ul = document.getElementById('results');
	
	switch(search) {
		case '*':
			search = '  ';
		break;
		case 'geeks':
			search = 'info';
		break;
		case 'chomeurs':
			search = 'mnt';
		break;
		case 'geekettes':
			search = 'info -fille';
		break;
		case 'brouettes':
			search = 'gc'
		break;
	}
	if(search=='*') {
		search = '  ';
	}
	
	ul.innerHTML = '';
	clearMaillist();
	deleteButtonMaillist();
	
	if(search.length!=1 && search!='') {
		var xhr = getXMLHttpRequest();
		
		// Research :
		xhr.onreadystatechange = function() {
			if (xhr.readyState==4 && (xhr.status == 200 || xhr.status == 0)) {
				// On est pret pour traiter les resultats.
				affichResults(xhr.responseXML);
				// On cache le gif de chargement.
				// document.getElementById("loader").style.cssText = "display:none;";
			} else if(xhr.readyState<4) {
				// Affichage du gif de chargement
				// document.getElementById("loader").style.cssText = "display:inline;";
			}
		};
		
		xhr.open("GET", "ajax/search.php?search="+escape(search));
		xhr.send();
	}
}

function affichResults(oData) {
	/*****************************************************************************
	Met a jour la liste des resultats en fonction de la reponse du script PHP.
	Supprime d'abord les resultats qui ne correspondent plus a la requete.
	Ajoute ensuite les nouveaux resultats ( a la fin de la liste ).
	Les resultats sont donc desordonnes ( temporaire ).
	*****************************************************************************/	
	nodes = oData.getElementsByTagName('item');
	var ul = document.getElementById('results');
	old_nodes = ul.getElementsByTagName('li');
	var search = document.getElementById('search').value;
	
	// On supprime les eleves qui ne correspondent plus aux criteres de recherche :
	/*for(var i=0 ; i<old_nodes.length ; i++) {
		var a_retirer = true;
		for(var j=0 ; j<nodes.length && a_retirer ; j++) {
			var student_id = nodes[j].getAttribute('student_id');
			if('n'+student_id==old_nodes[i].getAttribute('id')) {
				a_retirer = false;
			}
		}
		if(a_retirer) {
			old_nodes[i].parentNode.removeChild(old_nodes[i]);
			i--; // Il y a un element de moins.
		}
	}*/
	
	// NEW :
	// ATTENTION : DERNIERE NOUVELLE NODE A AJOUTER A LA TOUTE FIN.
	// On ajoute les nouveaux eleves :
	/*for(var i=0 ; i<nodes.length-1 ; i++) {
		var student_id = nodes[i].getAttribute('student_id');
		var next_student_id = nodes[i+1].getAttribute('student_id');
		var done = false;
		for(var j=0 ; j<old_nodes.length && !done ; j++) {
			if(old_nodes[j].getAttribute('id')=='n'+student_id) {
				// L'eleve est deja present.
				done = true;
			} else if(old_nodes[j].getAttribute('id')=='n'+next_student_id) {
				// Selection des informations sur l'etudiant :
				var last_name = nodes[i].getAttribute('last_name');
				var first_name = nodes[i].getAttribute('first_name');
				var department = nodes[i].getAttribute('department');
				var year = nodes[i].getAttribute('year');
				var room = nodes[i].getAttribute('room');
				var ip_address = nodes[i].getAttribute('ip_address');
				var picture = nodes[i].getAttribute('picture');
				var gender = nodes[i].getAttribute('gender');
				var mail = nodes[i].getAttribute('mail');
				
				// Affichage de ces informations :
				var li = document.createElement('li');
				li.setAttribute('id', 'n'+student_id);
				if(picture) {
					var innerHTML = '<img height="192" width="144" src="photos/'+student_id+'.jpeg" alt="'+first_name+' '+last_name+'" title="'+first_name+' '+last_name+'"/>';
				} else if(gender=='female') {
					var innerHTML = '<img height="192" width="144" src="photos/defaut_female.jpeg" alt="'+first_name+' '+last_name+'" title="'+first_name+' '+last_name+'"/>';
				} else {
					var innerHTML = '<img height="192" width="144" src="photos/defaut_male.jpeg" alt="'+first_name+' '+last_name+'" title="'+first_name+' '+last_name+'"/>';
				}
				innerHTML += first_name+' '+ucwords(last_name)+'<br/>';
				if(room=='Unknown') {
					innerHTML += 'Externe<br/>';
				} else {
					var couloir = (room.substr(0, 2)=='BN')? room.substr(0, 4) : room.substr(0, 3);
					innerHTML += '<a href="index.php?search='+couloir+'">'+room+'</a><br/>';
					innerHTML += ip_address+'<br/>';
				}
				if(department!='Doctorant' && department!='Master') innerHTML += year;
				innerHTML += department+'<br/>';
				innerHTML += '<input type="hidden" value="'+mail+'"/><br/>';
				li.innerHTML = innerHTML;
				
				// On insert l'eleve dans la page :
				ul.insertBefore(li, old_nodes[j]);
				done = true;
			}
		}
		
		if(!done) {
			// Selection des informations sur l'etudiant :
			var last_name = nodes[i].getAttribute('last_name');
			var first_name = nodes[i].getAttribute('first_name');
			var department = nodes[i].getAttribute('department');
			var year = nodes[i].getAttribute('year');
			var room = nodes[i].getAttribute('room');
			var ip_address = nodes[i].getAttribute('ip_address');
			var picture = nodes[i].getAttribute('picture');
			var gender = nodes[i].getAttribute('gender');
			var mail = nodes[i].getAttribute('mail');
			
			// Affichage de ces informations :
			var li = document.createElement('li');
			li.setAttribute('id', 'n'+student_id);
			if(picture) {
				var innerHTML = '<img height="192" width="144" src="photos/'+student_id+'.jpeg" alt="'+first_name+' '+last_name+'" title="'+first_name+' '+last_name+'"/>';
			} else if(gender=='female') {
				var innerHTML = '<img height="192" width="144" src="photos/defaut_female.jpeg" alt="'+first_name+' '+last_name+'" title="'+first_name+' '+last_name+'"/>';
			} else {
				var innerHTML = '<img height="192" width="144" src="photos/defaut_male.jpeg" alt="'+first_name+' '+last_name+'" title="'+first_name+' '+last_name+'"/>';
			}
			innerHTML += first_name+' '+ucwords(last_name)+'<br/>';
			if(room=='Unknown') {
				innerHTML += 'Externe<br/>';
			} else {
				var couloir = (room.substr(0, 2)=='BN')? room.substr(0, 4) : room.substr(0, 3);
				innerHTML += '<a href="index.php?search='+couloir+'">'+room+'</a><br/>';
				innerHTML += ip_address+'<br/>';
			}
			if(department!='Doctorant' && department!='Master') innerHTML += year;
			innerHTML += department+'<br/>';
			innerHTML += '<input type="hidden" value="'+mail+'"/><br/>';
			li.innerHTML = innerHTML;
			
			// On ajoute l'eleve a la fin de la page :
			ul.appendChild(li);
		}
	}
	
	if(nodes.length>0) {
		// On verifie qu'il n'est pas deja present :
		var num = nodes.length-1;
		var student_id = nodes[num].getAttribute('student_id');
		if(old_nodes[old_nodes.length-1].getAttribute('id')!='n'+student_id) {
			// Selection des informations sur l'etudiant :
			var last_name = nodes[num].getAttribute('last_name');
			var first_name = nodes[num].getAttribute('first_name');
			var department = nodes[num].getAttribute('department');
			var year = nodes[num].getAttribute('year');
			var room = nodes[num].getAttribute('room');
			var ip_address = nodes[num].getAttribute('ip_address');
			var picture = nodes[num].getAttribute('picture');
			var gender = nodes[num].getAttribute('gender');
			var mail = nodes[num].getAttribute('mail');
			
			// Affichage de ces informations :
			var li = document.createElement('li');
			li.setAttribute('id', 'n'+student_id);
			if(picture) {
				var innerHTML = '<img height="192" width="144" src="photos/'+student_id+'.jpeg" alt="'+first_name+' '+last_name+'" title="'+first_name+' '+last_name+'"/>';
			} else if(gender=='female') {
				var innerHTML = '<img height="192" width="144" src="photos/defaut_female.jpeg" alt="'+first_name+' '+last_name+'" title="'+first_name+' '+last_name+'"/>';
			} else {
				var innerHTML = '<img height="192" width="144" src="photos/defaut_male.jpeg" alt="'+first_name+' '+last_name+'" title="'+first_name+' '+last_name+'"/>';
			}
			innerHTML += first_name+' '+ucwords(last_name)+'<br/>';
			if(room=='Unknown') {
				innerHTML += 'Externe<br/>';
			} else {
				var couloir = (room.substr(0, 2)=='BN')? room.substr(0, 4) : room.substr(0, 3);
				innerHTML += '<a href="index.php?search='+couloir+'">'+room+'</a><br/>';
				innerHTML += ip_address+'<br/>';
			}
			if(department!='Doctorant' && department!='Master') innerHTML += year;
			innerHTML += department+'<br/>';
			innerHTML += '<input type="hidden" value="'+mail+'"/><br/>';
			li.innerHTML = innerHTML;
			
			// On ajoute l'eleve a la fin de la page :
			ul.appendChild(li);
		}
	}*/
	
	// On ajoute les nouveaux eleves :
	for(var i=0 ; i<nodes.length ; i++) {
		// On verifie que cet eleve n'est pas deja affiche :
		var student_id = nodes[i].getAttribute('student_id');
		// var absent = true;
		/*for(var j=0 ; j<old_nodes.length && absent ; j++) {
			if(old_nodes[j].getAttribute('id')=='n'+student_id) {
				absent = false;
			}
		}*/
		
		// if(absent) {				
			// Selection des informations sur l'etudiant :
			var last_name = nodes[i].getAttribute('last_name');
			var first_name = nodes[i].getAttribute('first_name');
			var department = nodes[i].getAttribute('department');
			var year = nodes[i].getAttribute('year');
			var room = nodes[i].getAttribute('room');
			var ip_address = nodes[i].getAttribute('ip_address');
			var picture = nodes[i].getAttribute('picture');
			var gender = nodes[i].getAttribute('gender');
			var mail = nodes[i].getAttribute('mail');
			var groupe = nodes[i].getAttribute('groupe');
			if(last_name=='Doghri' && (search=='Aziz' || search=='aziz')) last_name = "Doghri (dit 'Aziz')";
			
			// Affichage de ces informations :
			var li = document.createElement('li');
			if(first_name!='') {
				li.setAttribute('id', 'n'+student_id);
				if(picture==1) {
					var innerHTML = '<img height="192" width="144" src="photos/'+student_id+'.jpeg" alt="'+first_name+' '+last_name+'" title="'+first_name+' '+last_name+'"/>';
				} else if(gender=='Female') {
					var innerHTML = '<img height="192" width="144" src="photos/default_female.jpeg" alt="'+first_name+' '+last_name+'" title="'+first_name+' '+last_name+'"/>';
				} else {
					var innerHTML = '<img height="192" width="144" src="photos/default_male.jpeg" alt="'+first_name+' '+last_name+'" title="'+first_name+' '+last_name+'"/>';
				}
				innerHTML += first_name+' '+last_name+'<br/>';
				if(room=='Externe') {
					innerHTML += room+'<br/>';
				} else {
					var couloir = (room.substr(0, 2)=='BN')? room.substr(0, 4) : room.substr(0, 3);
					innerHTML += '<a href="index.php?search='+couloir+'">'+room+'</a><br/>';
					innerHTML += ip_address+'<br/>';
				}
				if(department!='Doctorant' && department!='Master') innerHTML += year;
				innerHTML += department;
				if(groupe!='') innerHTML += '-'+groupe;
				innerHTML += '<br/><input type="hidden" value="'+mail+'"/><br/>';
				li.innerHTML = innerHTML;
			}
			ul.appendChild(li);
		// }
	}
	
	if(nodes.length>0) {
		addButtonMaillist();
	}
}

function ucwords(chaine) {
	/************************************************************************
	Equivalent a ucwords de PHP.
	Met la premier lettre d'un mot en majuscule et le reste en minuscule.
	************************************************************************/
	return chaine.substr(0,1).toUpperCase()+chaine.substr(1,chaine.length).toLowerCase();
}