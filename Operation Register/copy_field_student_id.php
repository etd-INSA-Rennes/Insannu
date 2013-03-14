<?php

exit();

require('secret/mysql_register.php');
require('secret/sqlite.php');
connect_db('secret/insannu.db');

try {
	$query = $GLOBALS['bdd']->prepare("SELECT * FROM students WHERE student_id < 5000");
	$query->execute();
} catch(Exception $e) {
	exit('Error: '.$e->getMessage());
}

$update = $GLOBALS['bdd_mysql']->prepare("UPDATE students SET student_id = ? WHERE mail = ? AND last_name = ? AND first_name = ?");
while($student = $query->fetch()) {
	try {
		$update->execute(array($student['student_id'], $student['mail'], $student['last_name'], $student['first_name']));
	} catch(Exception $e) {
		exit('Error: '.$e->getMessage());
	}
}

?>