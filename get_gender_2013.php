<?php
	
	exit();
	
	require("secret/sqlite.php");
	
	$bdd = connect_db("secret/insannu_2013.db");
	try {
		$query2 = $bdd->prepare("SELECT * FROM students");
		$query2->execute();
	} catch(PDOException $e) {
		exit("Erreur : ".$e->getMessage());
	}
	$students = $query2->fetchAll(PDO::FETCH_ASSOC);
	
	unset($bdd);
	$bdd = connect_db("secret/insannu.db");
	$query1 = $bdd->prepare("UPDATE students SET gender = ? WHERE login = ? AND gender IS NULL");
	foreach($students as $student) {
		try {
			$result = $query1->execute(array($student['gender'], $student['login']));
		} catch(PDOException $e) {
			exit("Erreur : ".$e->getMessage());
		}
		if(!$result) {
			echo $student['first_name'].' '.$student['last_name'].' - '.$student['year'].$student['department'];
		}
	}
	
?>