<?php
/**
 * Copyright (c) 2014 Paul Chaignon <paul.chaignon@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, distribute with modifications, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE ABOVE COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR
 * THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * Except as contained in this notice, the name(s) of the above copyright
 * holders shall not be used in advertising or otherwise to promote the
 * sale, use or other dealings in this Software without prior written
 * authorization.
 */

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