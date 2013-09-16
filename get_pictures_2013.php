<?php

	exit();

	set_time_limit(0);

	require('secret/sqlite.php');
	$bdd = connect_db('secret/insannu_2013.db');
	
	try {
		$query = $bdd->prepare("SELECT * FROM students WHERE picture = 1");
		$query->execute();
	} catch(Exception $e) {
		exit('Error: '.$e->getMessage());
	}
	$students = $query->fetchAll(PDO::FETCH_ASSOC);
	
	unset($bdd);
	$bdd = connect_db('secret/insannu.db');
	$select_query = $bdd->prepare("SELECT * FROM students WHERE login = ?");
	$update_query = $bdd->prepare("UPDATE students SET picture = 1, photo_changed = ? WHERE login = ?");
	foreach($students as $student) {
		try {
			$select_query->execute(array($student['login']));
		} catch(Exception $e) {
			exit('Error: '.$e->getMessage());
		}
		$results = $select_query->fetchAll(PDO::FETCH_ASSOC);
		if(count($results) == 0) {
			continue;
		} else if(count($results) == 1) {
			$student_id = $results[0]['student_id'];
			$photo_changed = $student['photo_changed'];
		} else {
			var_dump($results);
			echo 'More than one result for student '.$student['first_name'].' '.$student['last_name'].' in current database.';
			exit();
		}
		echo $student['login'].' ';
		if($photo_changed) {
			echo '1<br/>';
		} else {
			echo '0<br/>';
		}
		echo 'cp photos_2013/'.$student['student_id'].'.jpg photos/'.$student_id.'.jpg<br/>';
		try {
			$result = $update_query->execute(array($photo_changed, $student['login']));
		} catch(Exception $e) {
			exit('Error: '.$e->getMessage());
		}
		if($result === false) {
			echo 'Error on update for '.$student['login'].'.<br/>';
		}
		$result = system('cp photos_2013/'.$student['student_id'].'.jpg photos/'.$student_id.'.jpg');
		if($result === false) {
			echo 'Error on copy for '.$student['login'].'.<br/>';
		}
		echo '<br/>';
	}
	
?>