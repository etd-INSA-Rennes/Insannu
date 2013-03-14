<?php // Database connection
/* WARNING: FILE MUST NOT BE ACCESSIBLE BY USERS, ELSE THEY CAN GET DATABASE PASSWORD */
	
	try {
		$GLOBALS['bdd_mysql'] = new PDO('mysql:dbname=insannu_register;host=127.0.0.1', 'root', '1n$annu');
	} catch (PDOException $e) {
		exit('Erreur : '.$e->getMessage());
	}
	
?>
