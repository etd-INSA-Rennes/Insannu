<?php

require('common/connect.php');
require('secret/mysql_register.php');
require('functions/mails.php');
require('PHPMailer/class.phpmailer.php');
require('functions/register.php');

$error = '';
if(isset($_FILES['photo']) && isset($_POST['id'])) {
	try {
		$query = $GLOBALS['bdd_mysql']->prepare("SELECT * FROM registrations WHERE ip_address = ? OR id = ?");
		$query->execute(array($_SERVER['REMOTE_ADDR'], $_POST['id']));
	} catch(Exception $e) {
		exit('Error: '.$e->getMessage());
	}
	$results = $query->fetchAll();
	if(!isset($results[1])) {
		// Errors handling :
		if($_FILES['photo']['error']) {
			switch($_FILES['photo']['error']) {
				case 1: // UPLOAD_ERR_INI_SIZE
				case 2: // UPLOAD_ERR_FORM_SIZE
					$error = 'La taille de la photo est supérieure à celle autorisée (50ko).';
				break;
				case 3: // UPLOAD_ERR_PARTIAL
					$error = 'Le transfert de la photo a été interrompu.';
				break;
				case 4: // UPLOAD_ERR_NO_FILE
					$error = 'Une photo est requise.';
				break;
				case 6: // UPLOAD_ERR_NO_TMP_DIR
					$error = 'Un dossier temporaire est requis pour l\'upload de la photo.';
				break;
				case 7: // UPLOAD_ERR_CANT_WRITE
					$error = 'Une erreur est survenue lors de la sauvegarde de la photo.';
				break;
				case 8: // UPLOAD_ERR_EXTENSION
					$error = 'Une extension PHP a stopp&eacute; le transfert de la photo.';
				break;
			}
		}
		
		if($_FILES['photo']['error']==0) {
			// Get an available name for the file.
			try {
				$query = $GLOBALS['bdd_mysql']->prepare("SELECT * FROM students WHERE ip_address = ?");
				$query->execute(array($_SERVER['REMOTE_ADDR']));
			} catch(Exception $e) {
				exit('Error: '.$e->getMessage());
			}
			if($student = $query->fetch()) {
				$ext = strrchr($_FILES['photo']['name'], '.');
				$authorized_files = array('.jpeg', '.jpg');
				if(in_array($ext, $authorized_files)) {
				// This type of image is allowed to be uploaded:
					$file_name = $student['id'].$ext;
					if(move_uploaded_file($_FILES['photo']['tmp_name'], '/var/www/photos_uploaded/'.$file_name)) {
					// The file is in the right directory:
						if($_POST['id']==0) {
						// The student hasn't submitted an id.
							// Ask for a confirmation by email:
							$id_confirm = generate_id_confirm();
							try {
								$query = $GLOBALS['bdd_mysql']->prepare("UPDATE students SET id_confirm = ?, file = ? WHERE mail = ?");
								$query->execute(array($id_confirm, $file_name, $student['mail']));
							} catch(Exception $e) {
								exit('Error: '.$e->getMessage());
							}
							$result_mail = mailConfirmPhoto($student['mail'], $student['first_name'].' '.$student['last_name'], $file_name, $student['id'], $id_confirm);
							$error = 'La photo a bien été uploadée.<br/>';
							if($result_mail===true) {
								$error .= 'Un email t\'a été envoyé pour confirmer ton identitée.';
							} else {
								$error .= 'Une erreur est survenue lors de l\'envoi de l\'email de confirmation.<br/>';
								$error .= 'Tu peux réessayer. Si le problème persiste n\'hésite pas à contacter un admin.';
							}
						} else {
						// The student used a link with an id.
							if($student['id'] == $_POST['id']) {
							// The id sent is correct.
								try {
									$query = $GLOBALS['bdd_mysql']->prepare("UPDATE students SET file = ? WHERE mail = ?");
									$query->execute(array($file_name, $student['mail']));
								} catch(Exception $e) {
									exit('Error: '.$e->getMessage());
								}
								// Just need a validation from an administrator:
								$result_mail = mailValidPhoto($student['mail'], $student['first_name'].' '.$student['last_name'], $file_name, $student['id'], $student['student_id']);
								$error = 'Le fichier a bien été uploadée.<br/>';
								if($result_mail===true) {
									$error .= 'Ta photo sera mise à jour dès qu\'elle aura été validée par un admin.';
								} else {
									$error .= 'Une erreur est survenue lors de l\'envoi de l\'email pour la validation.';
									$error .= 'Tu peux réessayer. Si le problème persiste n\'hésite pas à contacter un admin.';
								}
							} else {
							// The id sent is incorrect.
								$error = 'Une erreur est survenue.';
							}
						}
					} else {
						$error = 'Erreur lors du transfert.';
					}
				} else {
					$error = 'Ce format d\'image n\'est pas autorisé. Seul le format jpg l\'est.';
				}
			} else {
				$error = 'Une erreur est survenue.';
			}
		}
	} else {
		$error = 'Il semblerait que notre base de données contienne des informations conflictuelles à ton sujet.';
	}
} else {
	header('Location: change_photo.php');
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
