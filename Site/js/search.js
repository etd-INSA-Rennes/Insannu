var t;

function recherche() {
	/**
	 * Requests the server 0.4sec after the last input.
	 */
	var previous_search = $('#previous_search');
	var search = $('#search');
	if(previous_search.val() != search.val()) {
		window.clearTimeout(t);
		t = window.setTimeout(askServeur, 400);
		previous_search.val(search.val());
	}
}

function askServeur() {
	/**
	 * Requests the PHP script search.php to obtain the list of results.
	 */
	var search = $('#search').val();
	var ul = $('#results');
	
	switch(search) {
		case '*':
			search = '  ';
		break;
		case 'etudiants':
			search = '  ';
		break;
	}
	
	ul.html('');
	clearMaillist();
	deleteButtonsMaillists();
	
	if(search.length > 1) {
		$.ajax({
	        type: 'GET',
	        url: 'ajax/search.php?search='+escape(search),
	        success: function(data, textStatus, jqXHR) {
	        	if(jqXHR.getResponseHeader('Content-Type').indexOf('text/plain')!=-1) {
					affichTextResults(data);
				} else {
					loadJSONResults(data);
				}
	        }
        });
	}
}

function loadJSONResults(oData) {
	/**
	 * Updates the list of results depending on the response of the PHP script.
	 * Every results are rewritten.
	 */
	var students = $.parseJSON(oData);
	var ul = $('#results');
	var stack = $('#stack');
	var search = $('#search').val();
	var student_id, last_name, first_name, department, year, room, picture, gender, mail, groupe;
	
	// Adds the new students:
	for(var i=0 ; i<students.length ; i++) {
		// Selects information of students:
		student_id = students[i][0];
		last_name = students[i][1];
		first_name = students[i][2];
		department = students[i][3];
		year = students[i][4];
		room = students[i][5];
		picture = students[i][6];
		gender = students[i][7];
		mail = students[i][8];
		groupe = students[i][9];
		if(last_name=='Doghri' && (search=='Aziz' || search=='aziz')) {
			last_name = "Doghri (dit 'Aziz')";
		}
		
		// Displays information:
		var li = document.createElement('li');
		if(first_name!='') {
			li.setAttribute('id', 'n'+student_id);
			if(picture==1) {
				var innerHTML = '<img height="192" width="144" src="photos/'+student_id+'.jpg" alt="'+first_name+' '+last_name+'" title="'+first_name+' '+last_name+'"/>';
			} else if(gender=='Female') {
				var innerHTML = '<img height="192" width="144" src="photos/default_female.jpg" alt="Photo par défaut" title="Photo par défaut"/>';
			} else {
				var innerHTML = '<img height="192" width="144" src="photos/default_male.jpg" alt="Photo par défaut" title="Photo par défaut"/>';
			}
			innerHTML += first_name+' '+last_name+'<br/>';
			/*if(room==null || room=='') {
				innerHTML += 'Chambre inconnue<br/>';
			} else if(room=='Externe') {
				innerHTML += 'Externe<br/>';
			} else {
				var couloir = (room.substr(0, 2)=='BN')? room.substr(0, 4) : room.substr(0, 3);
				innerHTML += '<a href="index.php?search='+couloir+'">'+room+'</a><br/>';
			}*/
			if(groupe != '') {
				innerHTML += '<a href="index.php?search='+year+groupe+'">'+year+department+'-'+groupe+'</a>';
			} else {
				if(department!='Doctorant' && department!='Master') {
					innerHTML += year;
				}
				innerHTML += department;
			}
			innerHTML += '<br/><input type="hidden" value="'+mail+'"/><br/>';
			li.innerHTML = '<!--'+innerHTML+'-->';
		}
		
		// Adds the student to the ul for immediate display or to the stack for futur display:
		if(i<18) {
			li.innerHTML = li.innerHTML.substring(4, li.innerHTML.length-3);
			ul.append(li);
		} else {
			stack.append(li);
		}
	}
	
	if(students.length>0) {	
		addButtonsMaillists();
	}
}

function affichTextResults(text) {
	/**
	 * Displays the HTML code sent by the PHP page.
	 */
	$('#results').html(text);
}

function affichXMLResults() {
	/**
	 * Loads new students from the stack and displays them.
	 */
	var stack_li = $('#stack').children();
	if(stack_li.length>0) {
		var ul = $('#results');
		var loadedResults = '';
		for(var i=0 ; i<18 ; i++) {
			loadedResults += '<li id="'+stack_li[i].getAttribute('id')+'">'+stack_li[i].innerHTML.substring(4, stack_li[i].innerHTML.length-3)+'</li>';
			$('#stack li:first').remove();
			if(stack_li.length==i+1) {
				break;
			}
		}
		ul.html(ul.html()+loadedResults);
	}
}

function infiniteScroll() {
	/**
	 * Calls affichXMLResults to load new students from the stack
	 * when the user reachs the bottom of the page.
	 */
	var offset = 20;
	var size;
    $(window).data('scrollready', true);
	var agentID = navigator.userAgent.toLowerCase().match(/(iphone|ipod|ipad)/);
	$(window).scroll(function() {
		if ($(window).data('scrollready') == false) return;
 
		size = $(window).scrollTop()+$(window).height();
		if(size+400>=$(document).height() || (agentID && size+550>$(document).height())) {
			$(window).data('scrollready', false);
			affichXMLResults();
			$(window).data('scrollready', true);
		}
	});
};

function ucwords(chaine) {
	/**
	 * Equivalent to ucwords in PHP.
	 * Puts the first letter of a word in upper case and the rest in lower case.
	 */
	return chaine.substr(0,1).toUpperCase() + chaine.substr(1,chaine.length).toLowerCase();
}

function adapter_taille() {
	/**
	 * Used for the first easter egg.
	 * Increases the size of the small image hidden in the corner of the page.
	 */
	var hauteur = $(document).height();
	var largeur = $(document).width();
	var style = 'height: '+hauteur+'px; width: '+largeur+'px;';
	$('#transp').setAttribute('style', style);
}

function voiler(opacity) {
	/**
	 * Used for the first easter egg.
	 * Slowly increases the opacity of the screen.
	 */
	var style = $('#transp').getAttribute('style');
	style += ' filter: alpha(opacity='+opacity+'); -moz-opacity: .'+opacity+'; opacity: .'+opacity+';';
	$('#transp').setAttribute('style', style);
	if(opacity<95) {
		var timer = setTimeout('voiler('+(opacity+0.25)+')', 37);
	}
}
