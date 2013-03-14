<?php
	
	exit();
	
	require("secret/sqlite.php");
	connect_db("secret/insannu.db");
	
	try {
		$query = $GLOBALS['bdd']->prepare("SELECT student_id, picture FROM students");
		$query->execute();
	} catch(PDOException $e) {
		exit("Erreur : ".$e->getMessage());
	}
	
	$nb = 0;
	while($student = $query->fetch(PDO::FETCH_ASSOC)) {
		if(file_exists("photos/".$student['student_id'].".jpeg")) {
			$picture = 1;
		} else {
			$picture = 0;
		}
		if($picture!=$student['picture']) {
			try {
				$query = $GLOBALS['bdd']->prepare("UPDATE students SET picture = ? WHERE student_id = ?");
				$query->execute(array($picture, $student['student_id']));
			} catch(PDOException $e) {
				exit("Erreur : ".$e->getMessage);
			}
		}
		echo $nb." : ".$student['student_id']."<br/>";
		$nb++;
	}
	
?>