<?php // Database connection
/* WARNING: FILE MUST NOT BE ACCESSIBLE BY USERS, ELSE THEY CAN GET DATABASE PASSWORD */
	
	function connect_mysql_db() {
		$db = null;
		try {
			$db = new PDO('mysql:dbname=insannu;host=127.0.0.1', 'root', '1n$annu');
		} catch (PDOException $e) {
			exit('Erreur : '.$e->getMessage());
		}
		return $db;
	}
	
?>
