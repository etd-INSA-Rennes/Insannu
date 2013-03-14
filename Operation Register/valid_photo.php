<?php

require('secret/mysql_register.php');
require('secret/sqlite.php');
connect_db('secret/insannu.db');
require('functions/mails.php');

if(isset($_GET['id'])) {

	try {
		$query = $GLOBALS['bdd_mysql']->prepare("SELECT * FROM students WHERE file <> '' AND ip_address <> '' AND id = ?");
		$query->execute(array($_GET['id']));
	} catch(Exception $e) {
		exit('Error: '.$e->getMessage());
	}
	
	$student = $query->fetch();
	if($student) {

		if(isset($_POST['accepted'])) {
			$ext = strrchr($student['file'], '.');
			if($ext=='.jpg' || $ext=='.jpeg') {
				$file = 'photos_uploaded/'.$student['file'];
				$longueur = 144;
				$largeur = 192;
				$taille = getimagesize($file);
				if($taille) {
					$img_big = imagecreatefromjpeg($file);
					$img_petite = imagecreatetruecolor($longueur, $largeur) or $img_petite = imagecreate($longueur, $largeur);
					if(imagecopyresized($img_petite, $img_big, 0, 0, 0, 0, $longueur, $largeur, $taille[0], $taille[1])) {
						if($student['picture']==1) {
							$result_mv = rename('photos/'.$student['student_id'].'.jpg', 'photos/old/'.$student['student_id'].'.jpg');
						} else {
							$result_mv = true;
						}
						if($result_mv) {
							if(imagejpeg($img_petite, 'photos/'.$student['student_id'].'.jpg')) {
								try {
									$update = $GLOBALS['bdd']->prepare("UPDATE students SET photo_changed = 1, picture = 1 WHERE mail = ?");
									if($update->execute(array($student['mail']))) {
										if(unlink($file)) {
											$message = '';
											if(isset($_POST['message_accepted']) && $_POST['message_accepted']!='') {
												$message = $_POST['message_accepted'];
											} elseif(isset($_POST['message_accepted_perso'])) {
												$message = $_POST['message_accepted_perso'];
											}
											$name = $student['first_name'].' '.$student['last_name'];
											if(mailPhotoAccepted($student['mail'], $name, $message)) {
												exit('Photo acceptée !');
											} else {
												exit('Erreur lors de l\'envoi du mail.');
											}
										} else {
											exit('Erreur lors de la suppression de l\'ancien fichier.');
										}
									} else {
										exit('Erreur lors de la mise à jour de la bdd.');
									}
								} catch(Exception $e) {
									exit('Error: '.$e->getMessage());
								}
							} else {
								if(rename('photos/old/'.$student['student_id'].'.jpg', 'photos/'.$student['student_id'].'.jpg')) {
									exit('Erreur lors de la tentatives de retour de la photo.');
								}
							}
						}
					}
				}
			} else {
				exit('La photo n\'est pas au format jpg.');
			}
		} else if(isset($_POST['refused']) && isset($_POST['message_refused'])) {
			$file = 'photos_uploaded/'.$student['file'];
			if(!unlink($file)) {
				echo 'Le fichier n\'a pas pu être supprimé : '.$file;
			}
			try {
				$update = $GLOBALS['bdd_mysql']->prepare("UPDATE students SET file = '' WHERE mail = ?");
				if($update->execute(array($student['mail']))) {
					if($_POST['message_refused']!='') {
						$message = $_POST['message_refused'];
					} else {
						$message = $_POST['message_refused_perso'];
					}
					$name = $student['first_name'].' '.$student['last_name'];
					if(mailPhotoRefused($student['mail'], $name, $message)) {
						exit('Photo refusée !');
					} else {
						exit('Erreur lors de l\'envoi du mail.');
					}
				} else {
					exit('Erreur lors de la mise à jour de la bdd.');
				}
			} catch(Exception $e) {
				exit('Error: '.$e->getMessage());
			}
		}
		
	} else {
		exit('Erreur lors de la sélection de l\'étudiant(e).');
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
	
	<img src="photos/<?php echo $student['student_id']; ?>.jpg" height="192" width="144" alt="old" title="old"/>
	<img src="photos_uploaded/<?php echo $student['file']; ?>" height="192" width="144" alt="new" title="new"/>
	<img src="photos_uploaded/<?php echo $student['file']; ?>" alt="new" title="new"/><br/>
	
	<?php
		$name = $student['first_name'].' '.$student['last_name'];
		$name = strtr($name, 'ÁÀÂÄÃÅÇÉÈÊËÍÏÎÌÑÓÒÔÖÕÚÙÛÜÝ', 'AAAAAACEEEEEIIIINOOOOOUUUUY');
		$name = strtr($name, 'áàâäãåçéèêëíìîïñóòôöõúùûüýÿ', 'aaaaaaceeeeiiiinooooouuuuyy');
		echo '<a href="/'.urlencode($name).'">'.$name.'</a>';
	?>
	<br/><br/>
	
	<form method="post" action="valid_photo.php?id=<?php echo $_GET['id']; ?>">
		<legend><b>Photo refusée</b></legend>
		<fieldset>
			<select id="message_refused" name="message_refused">
				<option value=""></option>
				<option value="format">Format incorrect. Risque d'être déformée</option>
				<option value="reconnaissable">Ne permet pas de reconnaître l'étudiant</option>
			</select><br/>
			<textarea id="text" name="message_refused_perso" id="message_refused_perso" cols="70" rows="10"></textarea><br/>
		</fieldset>
		<input type="submit" name="refused" id="refused" value="Refuser"/>
	</form>
	<br/><br/>
	<form method="post" action="valid_photo.php?id=<?php echo $_GET['id']; ?>">
		<legend><b>Photo acceptée</b></legend>
		<fieldset>
			<select id="message_accepted" name="message_accepted">
				<option value=""></option>
				<option value="deformee">Photo légèrement déformée</option>
				<option value="floue">Photo légèrement floue</option>
			</select><br/>
			<textarea id="text" name="message_accepted_perso" id="message_accepted_perso" cols="70" rows="10"></textarea><br/>
		</fieldset>
		<input type="submit" name="accepted" id="accepted" value="Accepter"/>
	</form>
	
	<?php include("common/footer.php"); ?>

</body>
</html>
<?php

	} else {

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
	
	<p style="color:red;">
		Erreur
	</p>
	
	<?php include("common/footer.php"); ?>

</body>
</html>
<?php
	}
?>