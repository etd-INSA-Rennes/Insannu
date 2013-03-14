<?php

require('secret/mysql_register.php');
require('secret/sqlite.php');
connect_db('secret/insannu.db');

try {
	$query = $GLOBALS['bdd']->prepare("SELECT * FROM students");
	$query->execute();
} catch(Exception $e) {
	exit('Error: '.$e->getMessage());
}
$students = $query->fetchAll();

$nb = 0;
$insert = $GLOBALS['bdd_mysql']->prepare("INSERT INTO students(login, groupe, first_name, last_name, room, ip_address, mail, student_id, department, year, gender, picture, id, file, id_confirm) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, '', 0)");
foreach($students as $student) {
	try {
		$query0 = $GLOBALS['bdd_mysql']->prepare("SELECT * FROM students WHERE mail LIKE ?");
		$query0->execute(array($student['mail']));
	} catch(Exception $e) {
		exit('Error: '.$e->getMessage());
	}
	if(!($result = $query0->fetch())) {
		if($student['ip_address']==NULL) {
			$student['ip_address'] = '';
		}
		if($student['gender']==NULL) {
			$student['gender'] = '';
		}
		if($student['room']==NULL) {
			$student['room'] = '';
		}
		$student['student_id'] = (int)$student['student_id'];
		$student['year'] = (int)$student['year'];
		var_dump(array($student['login'], $student['groupe'], $student['first_name'], $student['last_name'], $student['room'], $student['ip_address'], $student['mail'], $student['student_id'], $student['department'], $student['year'], $student['gender'], $student['picture']));
		echo '<br/>';
		try {
			$insert->execute(array($student['login'], $student['groupe'], $student['first_name'], $student['last_name'], $student['room'], $student['ip_address'], $student['mail'], $student['student_id'], $student['department'], $student['year'], $student['gender'], $student['picture']));
		} catch(Exception $e) {
			exit('Error: '.$e->getMessage());
		}
		// var_dump($GLOBALS['bdd_mysql']->errorInfo());
		$nb++;
	}
}
$insert->closeCursor();
$query->closeCursor();
echo $nb;

?>