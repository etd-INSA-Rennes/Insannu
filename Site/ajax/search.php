<?php

require('../shared/constants.php');

if(isset($_GET['search']) && isset($_GET['maillist']) && $_GET['maillist']==1) {
	if($GLOBALS['statistics']===true) {
		// Updates the statistics:
		require('../secret/mysql.php');
		require('../functions/statistics.php');
		$mysql_db = connect_mysql_db();
		$search = str_replace(array('é', 'è', 'à', 'ë', 'ê', 'ï', 'î'), array('e', 'e', 'a', 'e', 'e', 'i', 'i'), $_GET['search']);
		add_search($mysql_db, $search, '1');
	}
	
} elseif(isset($_GET['search'])) {
	require('../secret/sqlite.php');
	$db = connect_db('../secret/insannu.db');
	if($GLOBALS['statistics']===true) {
		require('../secret/mysql.php');
		require("../functions/statistics.php");
		$mysql_db = connect_mysql_db();
	}
	
	// Replaces all special characters:
	$search = str_replace(array('é', 'è', 'à', 'ë', 'ê', 'ï', 'î'), array('e', 'e', 'a', 'e', 'e', 'i', 'i'), $_GET['search']);
	
	if($GLOBALS['statistics']===true) {
		// Updates the statistics:
		add_search($mysql_db, $search, '0');
	}
	
	// Calls the right search function:
	$search = strtoupper($search);
	/*if($search=='VOISINS') { // Need to be put in JSON !!!
		require("../functions/voisins.php");
		echo voisins($_SERVER['REMOTE_ADDR']);
	} else*/if($search=='INSANNU') {
		require("../functions/special_search.php");
		echo specialSearchInsannu($db);
	} elseif($search=='RANDOM' || $search=='ALEATOIRE') {
		require("../functions/special_search.php");
		echo specialSearchRandom($db);
	} elseif($search=='BIIP') {
		require("../functions/special_search.php");
		echo specialSearchBIIP($db);
	/*} elseif($search=='LOUALICHE') { // Need to be fix: add header.
		require("../functions/special_search.php");
		echo specialSearchLoualiche();*/
	} else {
		require("../functions/custom_searches.php");
		$custom = customSearches($search);
		if($custom=='') {
			header("Expires: Sat, 14 Sep 2013 05:00:00 GMT");
			require("../functions/search.php");
			echo search($db, $search);
		} else {
			echo $custom;
		}
	}
}



?>
