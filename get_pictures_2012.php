<?php

	exit();
	
	require("secret/sqlite.php");
	
	connect_db("secret/insannu_2012.db");
	try {
		$query2 = $GLOBALS['bdd']->prepare("SELECT mail, student_id FROM students WHERE picture = 1");
		$query2->execute();
	} catch(PDOException $e) {
		exit("Erreur : ".$e->getMessage());
	}
	$students2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	
	connect_db("secret/insannu.db");
	try {
		$query1 = $GLOBALS['bdd']->prepare("SELECT mail, student_id, year, department, picture FROM students WHERE picture <> 1");
		$query1->execute();
	} catch(PDOException $e) {
		exit("Erreur : ".$e->getMessage());
	}
	$students1 = $query1->fetchAll(PDO::FETCH_ASSOC);
	
	$nb = 0;
	$query = $GLOBALS['bdd']->prepare("UPDATE students SET picture = 1, student_id = ? WHERE mail LIKE ?");
	foreach($students1 as $student1) {
		$mail = strtolower($student1['mail']);
		$found = false;
		foreach($students2 as $student2) {
			if($mail==strtolower($student2['mail'])) {
				$found = true;
				$nb++;
				echo '<u>'.$mail.':</u><br/>';
				echo 'Picture\'s name: '.$student2['student_id'].'<br/>';
				echo 'Promotion: '.$student1['year'].$student1['department'].'<br/>';
				try {
					$query->execute(array($student2['student_id'], $student1['mail']));
				} catch(PDOException $e) {
					exit("Erreur : ".$e->getMessage());
				}
				/*if(copy('photos_2012/'.$student2['student_id'].'.jpeg', 'photos/'.$student2['student_id'].'.jpg')) {
					echo 'Copied successfully !<br/>';
				} else {
					echo 'An error occurred while copying<br/>';
				}*/
				echo '<br/>';
				break;
			}
		}
		/*if(!$found) {
			$nb++;
			echo '<u>'.$mail.':</u><br/>';
			echo 'Promotion: '.$student1['year'].$student1['department'].'<br/>';
			echo '<br/>';
		}*/
	}
	
	echo $nb;
	
?>