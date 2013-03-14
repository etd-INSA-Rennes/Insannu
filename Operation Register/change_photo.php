<?php

require('common/connect.php');
require('secret/mysql_register.php');
require('functions/register.php');

/**********************
BDD:
students
	- room_known
	- room
	- ip_address
	- mail
	- id
	- first_name
	- last_name
	- groupe
	- picture
	- student_id
	- year
	- department
	- gender
registrations
	- ip_address
	- id
	- date
**********************/

$cheat = 0;
if(isset($_GET['id'])) {
	try {
		$query = $GLOBALS['bdd_mysql']->prepare("SELECT * FROM students WHERE id = ?");
		$query->execute(array($_GET['id']));
	} catch(Exception $e) {
		exit('Error: '.$e->getMessage());
	}
	if($results = $query->fetch()) {
	// That's a correct id.
		if(isFromResidence($_SERVER['REMOTE_ADDR'])) {
		// He's connected from the residences.
			// Add this address ip to the table of registrations:
			try {
				$query = $GLOBALS['bdd_mysql']->prepare("SELECT * FROM registrations WHERE id = ? AND ip_address = ?");
				$query->execute(array($_GET['id'], $_SERVER['REMOTE_ADDR']));
			} catch(Exception $e) {
				exit('Error: '.$e->getMessage());
			}
			if(!($results = $query->fetch())) {
				try {
					$query = $GLOBALS['bdd_mysql']->prepare("INSERT INTO registrations(ip_address, id, date, user_agent) VALUES(?, ?, ?, ?)");
					$query->execute(array($_SERVER['REMOTE_ADDR'], $_GET['id'], time(), $_SERVER['HTTP_USER_AGENT']));
				} catch(Exception $e) {
					exit('Error: '.$e->getMessage());
				}
			}
			
			try {
				$query = $GLOBALS['bdd_mysql']->prepare("SELECT COUNT(DISTINCT(ip_address)) FROM registrations WHERE id = ?");
				$query->execute(array($_GET['id']));
			} catch(Exception $e) {
				exit('Error: '.$e->getMessage());
			}
			$results = $query->fetchAll();
			if($results[0][0]==1) {
			// There is only one ip address linked to this id.
				try {
					$query = $GLOBALS['bdd_mysql']->prepare("UPDATE students SET ip_address = ? WHERE id = ?");
					$query->execute(array($_SERVER['REMOTE_ADDR'], $_GET['id']));
				} catch(Exception $e) {
					exit('Error: '.$e->getMessage());
				}
			} else {
			// There are more than one ip address linked to this ip address, so we don't know the real value.
				try {
					$query = $GLOBALS['bdd_mysql']->prepare("UPDATE students SET ip_address = '' WHERE id = ?");
					$query->execute(array($_GET['id']));
				} catch(Exception $e) {
					exit('Error: '.$e->getMessage());
				}
				$cheat = 3;
			}
			
			try {
				$query = $GLOBALS['bdd_mysql']->prepare("SELECT * FROM students WHERE ip_address LIKE ?");
				$query->execute(array($_SERVER['REMOTE_ADDR']));
			} catch(Exception $e) {
				exit('Error: '.$e->getMessage());
			}
			$students = $query->fetchAll();
		} else {
		// Someone try to connect from outside the residences.
			$ch = fopen('cheaters.txt', 'a+');
			fwrite($ch, $_SERVER['REMOTE_ADDR'].':'.$_GET['id'].':ip'."\n");
			fclose($ch);
			$cheat = 1;
		}
	} else {
	// That's just a tentative to cheat.
		$ch = fopen('cheaters.txt', 'a+');
		fwrite($ch, $_SERVER['REMOTE_ADDR'].':'.$_GET['id']."\n");
		fclose($ch);
		$cheat = 2;
	}
} else {
// Someone try to connect without an id.
	$ch = fopen('cheaters.txt', 'a+');
	fwrite($ch, $_SERVER['REMOTE_ADDR']."\n");
	fclose($ch);
	try {
		$query = $GLOBALS['bdd_mysql']->prepare("SELECT * FROM students WHERE ip_address LIKE ?");
		$query->execute(array($_SERVER['REMOTE_ADDR']));
	} catch(Exception $e) {
		exit('Error: '.$e->getMessage());
	}
	$students = $query->fetchAll();
}

if($cheat!=0 || !isset($students[0]) || isset($students[1])) {
	
	if($cheat==1) {
		$error = 'Pour modifier ta photo tu dois te connecter depuis ta chambre.<br/>';
		$error .= '<form method="post" action="externe.php" onsubmit="confirm(\'Aucun retour en arrière possible\')"><input type="hidden" name="id" id="id" value="'.$_GET['id'].'"/>';
		$error .= '<input type="submit" value="Je suis externe" name="externe" id="externe"/></form>';
	} elseif($cheat==2) {
		$error = 'Nous n\'avons pas réussi à t\'identifier.<br/>';
		$error .= 'Pour modifier ta photo tu dois utiliser le lien qui t\'a été envoyé par mail.';
	} elseif($cheat==3 || isset($students[1])) {
		$error = 'Il semblerait que notre base de données contienne des informations conflictuelles à ton sujet.';
	} else {
		$error = 'Nous n\'avons pas réussi à t\'identifier.<br/>';
		$error .= 'Pour changer ta photo tu dois utiliser le lien qui t\'a été envoyé par mail.';
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
<?php
	
} else {

	if(isset($_GET['id'])) {
		$id = $_GET['id'];
	} else {
		$id = 0;
	}
	
	$student = $students[0];

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
	
	<ul id="results">
	<?php
		echo '<li id="n'.$student['student_id'].'">';
		$name = $student['first_name'].' '.$student['last_name'];
		if($student['picture']==1) {
			echo '<img height="192" width="144" src="photos/'.$student['student_id'].'.jpg" alt="'.$name.'" title="'.$name.'"/>';
		} else if($student['gender']=='Female') {
			echo '<img height="192" width="144" src="photos/default_female.jpg" alt="Photo par défaut" title="Photo par défaut"/>';
		} else {
			echo '<img height="192" width="144" src="photos/default_male.jpg" alt="Photo par défaut" title="Photo par défaut"/>';
		}
		echo $name.'<br/>';
		if($student['groupe']!='') {
			echo '<a href="index.php?search='.$student['year'].$student['groupe'].'">'.$student['year'].$student['department'].'-'.$student['groupe'].'</a>';
		} else {
			if($student['department']!='Doctorant' && $student['department']!='Master') echo $student['year'];
			echo $student['department'];
		}
		echo '<br/><input type="hidden" value="'.$student['mail'].'"/><br/>';
	?>
	</li>
	</ul>
	
	<form action="upload_photo.php" method="post" enctype="multipart/form-data">
		<legend>
			<br/><b>Changer ta photo</b><br/>
			<span style="color:red;">Attention ! Ta photo doit avoir les mêmes proportions<br/> (144x192px) que l'actuelle sinon elle sera déformée.</span>
		</legend>
		<fieldset style="height: 100;">
			<input type="hidden" name="id" id="id" value="<?php echo $id; ?>"/>
			<input type="hidden" name="MAX_FILE_SIZE" id="MAX_FILE_SIZE" value="50000">
			<input type="file" name="photo" id="photo"/><br/>
			<input type="submit" value="Envoyer"/>
		</fieldset>
	</form>
	
	<?php include("common/footer.php"); ?>

</body>
</html>
<?php

}

?>
