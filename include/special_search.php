<?php

function specialSearchInsannu($db) {
	// Selection des etudiants :
	try {
		$fields = 'student_id, last_name, first_name, department, year, room, picture, gender, mail, groupe';
		$query = $db->prepare('SELECT '.$fields.' FROM students WHERE mail IN (?, ?, ?) ORDER BY last_name, first_name;');
		$query->execute(array('Nicolas.Busseneau@insa-rennes.fr', 'Paul.Chaignon@insa-rennes.fr'));
	} catch(Exception $e) {
		exit('Error : '.$e->getMessage());
	}
	
	$students = $query->fetchAll(PDO::FETCH_NUM);
	$query->closeCursor();
	
	// Return the results:
	return json_encode($students)."\n";
}

function specialSearchLoualiche() {
	//header("Content-Type: text/plain; charset=utf-8");
	$result = '<style>
		div#transp {
			position: absolute;
			top: 0px;
			left: 0px;
			background: black;
			height: 0px;
			width: 0px;
			filter: alpha(opacity=10);
			-moz-opacity: .10;
			opacity: .10;
		}
	</style>';
	$result .= '<div id="transp"></div>';
	$result .= '<audio autoplay>';
	$result .= '<source src="/berceuse.mp3"/>';
	$result .= '<source src="/berceuse.ogg"/>';
	$result .= '</audio>';
	$result .= '<img src="/images/logo.gif" height="0px" width="0px" onload="adapter_taille(); voiler(10);"/>';
	return $result;
}

function specialSearchBIIP($db) {
	// Selection des etudiants :
	try {
		$fields = 'student_id, last_name, first_name, department, year, room, picture, gender, mail, groupe';
		$query = $db->prepare('SELECT '.$fields.' FROM students WHERE mail IN (?, ?, ?) ORDER BY last_name, first_name;');
		$query->execute(array('Raphael.Baron@insa-rennes.fr', 'Anis.Doghri@insa-rennes.fr', 'Corentin.Nicole@insa-rennes.fr'));
	} catch(Exception $e) {
		exit('Error : '.$e->getMessage());
	}
	
	$students = $query->fetchAll(PDO::FETCH_NUM);
	$query->closeCursor();
	
	// Return the results:
	return json_encode($students)."\n";
}

function specialSearchRandom($db) {
	// Selection de tous les etudiants :
	try {
		$query = $db->prepare('SELECT * FROM students');
		$query->execute();
	} catch(Exception $e) {
		exit('Error : '.$e->getMessage());
	}
	$students = $query->fetchAll(PDO::FETCH_ASSOC);
	$params = array($students[rand(0, count($students))]['mail'], $students[rand(0, count($students))]['mail'], $students[rand(0, count($students))]['mail'], $students[rand(0, count($students))]['mail'], $students[rand(0, count($students))]['mail'], $students[rand(0, count($students))]['mail']);
	
	// Selection de six etudiants :
	try {
		$fields = 'student_id, last_name, first_name, department, year, room, picture, gender, mail, groupe';
		$query = $db->prepare('SELECT '.$fields.' FROM students WHERE mail IN (?, ?, ?, ?, ?, ?) ORDER BY last_name, first_name;');
		$query->execute($params);
	} catch(Exception $e) {
		exit('Error : '.$e->getMessage());
	}
	
	$students = $query->fetchAll(PDO::FETCH_NUM);
	$query->closeCursor();
	
	// Return the results:
	return json_encode($students)."\n";
}
	
?>