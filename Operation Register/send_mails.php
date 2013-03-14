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
<?php

require('secret/mysql_register.php');
require('functions/mails.php');
require('PHPMailer/class.phpmailer.php');
require('functions/register.php');

try {
	$query = $GLOBALS['bdd_mysql']->prepare("SELECT * FROM students WHERE room = '' AND mail_sent = 0");
	$query->execute();
} catch(Exception $e) {
	exit('Error: '.$e->getMessage());
}

$nb = 0;
$testeurs_origin = array('pchaigno@insa-rennes.fr', 'Paul.Chaignon@insa-rennes.fr', 'paul.chaignon@insa-rennes.fr', 'Paul.chaignon@insa-rennes.fr', 'paul.Chaignon@insa-rennes.fr');
$testeurs = array();
// $update = $GLOBALS['bdd_mysql']->prepare("UPDATE students SET id = ? WHERE mail = ?");
$update = $GLOBALS['bdd_mysql']->prepare("UPDATE students SET mail_sent = 1 WHERE mail = ?");
$id_mail = '';
while($student = $query->fetch()) {
	/*$id = generate_id();
	try {
		$update->execute(array($id, $student['mail']));
	} catch(Exception $e) {
		exit('Error: '.$e->getMessage());
	}*/
	$name = $student['first_name'].' '.$student['last_name'];
	$result_mail = mailRegister($id_mail, $student['mail'], $name, $student['id']);
	if($result_mail===false) {
		echo $name.': Une erreur a eu lieu lors de l\'envoi de l\'email ('.$student['id'].').<br/>';
	} else {
		echo $name.': Mail envoyé ('.$student['id'].').<br/>';
		try {
			$result_update = $update->execute(array($student['mail']));
		} catch(Exception $e) {
			exit('Error: '.$e->getMessage());
		}
		if($result_update===false) {
			echo 'Probleme !!!<br/>';
		}
		$nb++;
	}
	if($nb%100==0) {
		$id_mail = floor($nb/100);
		if(empty($testeurs)) {
			$testeurs = $testeurs_origin;
		}
		$result_mail = mailRegister($id_mail, array_shift($testeurs), 'Testeur', 12345678);
	}
	if($nb%170==0) {
		sleep(3090);
	}
}

?>
</p>
	
<?php include("common/footer.php"); ?>

</body>
</html>