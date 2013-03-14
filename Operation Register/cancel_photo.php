<?php

require('common/connect.php');
require('secret/mysql_register.php');

$error = '';
if(isset($_GET['id_confirm']) && isset($_GET['id'])) {
	try {
		$query = $GLOBALS['bdd_mysql']->prepare("SELECT * FROM registrations WHERE id = ? OR ip_address = ?");
		$query->execute(array($_GET['id'], $_SERVER['REMOTE_ADDR']));
	} catch(Exception $e) {
		exit('Error: '.$e->getMessage());
	}
	$results = $query->fetchAll();
	if(!isset($results[1])) {
		try {
			$query = $GLOBALS['bdd_mysql']->prepare("SELECT * FROM students WHERE id_confirm = ? AND id = ?");
			$query->execute(array($_GET['id_confirm'], $_GET['id']));
		} catch(Exception $e) {
			exit('Error: '.$e->getMessage());
		}
		if($result = $query->fetch()) {
			try {
				$query = $GLOBALS['bdd_mysql']->prepare("UPDATE students SET id_confirm = 0 WHERE id_confirm = ?");
				$query->execute(array($_GET['id_confirm']));
			} catch(Exception $e) {
				exit('Error: '.$e->getMessage());
			}
			$error = 'Le changement de photo a bien été annulé.';
		} else {
			$error = 'Votre changement de photo semble avoir déjà été annulé.';
		}
	} else {
		$error = 'Il semblerait que notre base de données contienne des informations conflictuelles à ton sujet.';
	}
} else {
	$error = 'Vous devez utiliser le lien qui vous a été envoyé par mail pour confirmer le changement de photo.';
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
	
	<p style="color: red;">
	<?php
		echo $error;
	?>
	</p>
	
	<?php include("common/footer.php"); ?>

</body>
</html>