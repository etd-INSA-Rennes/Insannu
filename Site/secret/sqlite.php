<?php // Database connection
/* WARNING: FILE MUST NOT BE ACCESSIBLE BY USERS, ELSE THEY CAN GET DATABASE PASSWORD */
    
	function connect_db($db_file) {
		$bdd = null;
		try {
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('sqlite:'.$db_file);
		} catch (Exception $e) {
			exit('Error : '.$e->getMessage());
		}
		return $bdd;
	}
	
?>
