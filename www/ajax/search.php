<?php

require_once('../../include/special_search.php');
require_once('../../include/search.php');
require_once('../../include/sqlite.php');
$db = connect_db('../../data/insannu.db');

if(isset($_GET['search'])) {
	// Replaces all special characters:
	$search = str_replace(array('é', 'è', 'à', 'ë', 'ê', 'ï', 'î'), array('e', 'e', 'a', 'e', 'e', 'i', 'i'), $_GET['search']);
	
	// Calls the right search function:
	$search = strtoupper($search);
	/*if($search=='VOISINS') { // Need to be put in JSON !!!
		require("../functions/voisins.php");
		echo voisins($_SERVER['REMOTE_ADDR']);
	} else*/if($search == 'INSANNU') {
		echo specialSearchInsannu($db);
	} elseif($search=='RANDOM' || $search=='ALEATOIRE') {
		echo specialSearchRandom($db);
	} else {
		echo search($db, $search);
	}
}



?>
