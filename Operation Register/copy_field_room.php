<?php

exit();

require('secret/mysql_register.php');
require('secret/sqlite.php');
connect_db('secret/insannu.db');

try {
	$query = $GLOBALS['bdd_mysql']->prepare("SELECT * FROM students WHERE room <> ''");
	$query->execute();
} catch(Exception $e) {
	exit('Error: '.$e->getMessage());
}

$nb = 0;
$update = $GLOBALS['bdd']->prepare("UPDATE students SET room = ? WHERE mail LIKE ?");
while($student = $query->fetch()) {
	echo $student['room'].': '.$student['mail'].'<br/>';
	try {
		$update->execute(array($student['room'], $student['mail']));
	} catch(Exception $e) {
		exit('Error: '.$e->getMessage());
	}
	$nb++;
}
echo $nb;

?>