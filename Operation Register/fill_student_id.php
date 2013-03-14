<?php

// exit();

require('secret/sqlite.php');
connect_db('secret/insannu.db');
require('functions/register.php');

try {
	$query = $GLOBALS['bdd']->prepare("SELECT * FROM students WHERE student_id IS NULL ORDER BY year");
	$query->execute();
} catch(Exception $e) {
	exit('Error: '.$e->getMessage());
}

$nb = 0;
$update = $GLOBALS['bdd']->prepare("UPDATE students SET student_id = ? WHERE mail = ?");
while($student = $query->fetch()) {
	$id = generate_student_id();
	echo $student['mail'].': '.$id.'<br/>';
	try {
		$update->execute(array($id, $student['mail']));
	} catch(Exception $e) {
		exit('Error: '.$e->getMessage());
	}
	$nb++;
}
echo $nb;

?>