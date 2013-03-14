<?php

require('secret/mysql_register.php');
require('functions/register.php');

$cheat = 0;
$error = '';
if(isset($_POST['externe']) && isset($_POST['id'])) {
	try {
		$query = $GLOBALS['bdd_mysql']->prepare("SELECT * FROM students WHERE id = ?");
		$query->execute(array($_POST['id']));
	} catch(Exception $e) {
		exit('Error: '.$e->getMessage());
	}
	if($results = $query->fetch()) {
	// That's a correct id.
		if(!isFromResidence($_SERVER['REMOTE_ADDR'])) {
		// He's connected from outside the residences.
			// Add this ip address to the table of registrations:
			try {
				$query = $GLOBALS['bdd_mysql']->prepare("SELECT * FROM registrations WHERE id = ? AND ip_address = ?");
				$query->execute(array($_POST['id'], 'externe'));
			} catch(Exception $e) {
				exit('Error: '.$e->getMessage());
			}
			if(!($results = $query->fetch())) {
				try {
					$query = $GLOBALS['bdd_mysql']->prepare("INSERT INTO registrations(ip_address, id, date, user_agent) VALUES(?, ?, ?, ?)");
					$query->execute(array('externe', $_POST['id'], time(), $_SERVER['HTTP_USER_AGENT']));
				} catch(Exception $e) {
					exit('Error: '.$e->getMessage());
				}
			}
			
			try {
				$query = $GLOBALS['bdd_mysql']->prepare("SELECT COUNT(DISTINCT(ip_address)) FROM registrations WHERE id = ?");
				$query->execute(array($_POST['id']));
			} catch(Exception $e) {
				exit('Error: '.$e->getMessage());
			}
			$results = $query->fetchAll();
			if($results[0][0]==1) {
			// There is only one ip address linked to this id.
				try {
					$query = $GLOBALS['bdd_mysql']->prepare("UPDATE students SET ip_address = ? WHERE id = ?");
					$query->execute(array('externe', $_POST['id']));
				} catch(Exception $e) {
					exit('Error: '.$e->getMessage());
				}
			} else {
			// There are more than one ip address linked to this ip address, so we don't know the real value.
				try {
					$query = $GLOBALS['bdd_mysql']->prepare("UPDATE students SET ip_address = '' WHERE id = ?");
					$query->execute(array($_POST['id']));
				} catch(Exception $e) {
					exit('Error: '.$e->getMessage());
				}
				$cheat = 3;
			}
		}
	} else {
	// That's just a tentative to cheat.
		$ch = fopen('cheaters.txt', 'w');
		fwrite($ch, $_SERVER['REMOTE_ADDR'].':'.$_POST['id']."\n");
		fclose($ch);
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link rel="shortcut icon" href="images/star.gif" />
    <link href="style.css" rel="stylesheet" type="text/css" />
    <title>Insannu</title>
</head>
    
<body>

	<?php include("common/header.php"); ?>
	
	<p>
		Si tu veux changer ta photo, tu peux nous <a href="mailto: pchaigno@insa-rennes.fr" style="color:blue; text-decoration:underline; font-weight:normal;">l'envoyer par mail</a>.<br/><br/>
	</p>
	
	<?php include("common/footer.php"); ?>

</body>
</html>