<?php

require('common/connect.php');
require('secret/mysql_register.php');
require('functions/mails.php');
require('PHPMailer/class.phpmailer.php');

$error = '';
if(isset($_GET['id_confirm']) && isset($_GET['id'])) {
	try {
		$query = $GLOBALS['bdd_mysql']->prepare("SELECT * FROM students WHERE id = ?");
		$query->execute(array($_GET['id']));
	} catch(Exception $e) {
		exit('Error: '.$e->getMessage());
	}
	if($result = $query->fetch()) {
		try {
			$query = $GLOBALS['bdd_mysql']->prepare("SELECT * FROM registrations WHERE id = ? && ip_address = ?");
			$query->execute(array($_GET['id'], $_SERVER['REMOTE_ADDR']));
		} catch(Exception $e) {
			exit('Error: '.$e->getMessage());
		}
		$results = $query->fetchAll();
		if(!isset($results[1])) {
			try {
				$query = $GLOBALS['bdd_mysql']->prepare("SELECT * FROM students WHERE ip_address = ?");
				$query->execute(array($_SERVER['REMOTE_ADDR']));
			} catch(Exception $e) {
				exit('Error: '.$e->getMessage());
			}
			if($student = $query->fetch()) {
				if($student['id_confirm']==$_GET['id_confirm']) {
					try {
						$query = $GLOBALS['bdd_mysql']->prepare("UPDATE students SET id_confirm = 0 WHERE mail = ?");
						$query->execute(array($student['mail']));
					} catch(Exception $e) {
						exit('Error: '.$e->getMessage());
					}
					$result_mail = mailValidPhoto($student['mail'], $student['first_name'].' '.$student['last_name'], $student['file'], $student['id'], $student['student_id']);
					if($result_mail===true) {
						$error .= 'Votre photo sera mise à jour dès qu\'elle aura été validée par un admin.';
					} else {
						$error .= 'Une erreur est survenue lors de l\'envoi de l\'email pour la validation par un admin.';
						$error .= 'Tu peux réessayer. Si le problème persiste n\'hésite pas à contacter un admin.';
					}
				} else {
					$error = 'Votre changement de photo semble avoir été annulé.';
				}
			} else {
				$error = 'Vous devez vous connecter depuis votre chambre pour confirmer le changement de photo.';
			}
		} else {
			$error = 'Il semblerait que notre base de données contienne des informations conflictuelles à ton sujet.';
		}
	} else {
		$error = 'Vous devez utiliser le lien qui vous a été envoyé par mail pour confirmer le changement de photo.';
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