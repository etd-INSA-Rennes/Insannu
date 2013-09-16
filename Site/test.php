<?php

	/*

	echo file_get_contents("custom/3INFO-A.json");

	exit();

	require('secret/sqlite.php');
	connect_db('secret/insannu.db');
	
	try {
		$query = $GLOBALS['bdd']->prepare("SELECT first_name, last_name, mail FROM students WHERE room LIKE 'b%'");
		$query->execute();
	} catch(Exception $e) {
		exit('Error: '.$e->getMessage());
	}
	
	$olds = $query->fetchAll();
	
	connect_db('secret/insannu_2012.db');
	
	try {
		$query = $GLOBALS['bdd']->prepare("SELECT first_name, last_name, mail FROM students WHERE room LIKE 'd%'");
		$query->execute();
	} catch(Exception $e) {
		exit('Error: '.$e->getMessage());
	}
	
	while($new = $query->fetch()) {
		foreach($olds as $old) {
			if($new['mail']==$old['mail']) {
				echo $new['first_name'].' '.$new['last_name'].'<br/>';
			}
		}
	}
	*/
	
?>
