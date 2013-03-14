<?php

	exit();

	require("secret/sqlite.php");
	connect_db("secret/insannu.db");
	
	if(isset($_POST['getGender01'])) {
		
		$query = $GLOBALS['bdd']->prepare("UPDATE students SET gender = ? WHERE mail LIKE ?");
		foreach($_POST as $mail => $gender) {
			if($gender!='') {
				echo $mail.' '.$gender.'<br/>';
				try {
					$query->execute(array($gender, $mail));
				} catch(PDOException $e) {
					exit("Erreur : ".$e->getMessage());
				}
			}
		}
		
	} else {
	
		$boys_names = array();
		$girls_names = array();
	
		try {
			$query = $GLOBALS['bdd']->prepare("SELECT mail, gender, year, department, first_name FROM students WHERE gender IS NULL");
			$query->execute();
		} catch(PDOException $e) {
			exit("Erreur : ".$e->getMessage());
		}
		
		echo '<form method="post" action="get_no_gender.php">';
		$nb = 0;
		while($student = $query->fetch(PDO::FETCH_ASSOC)) {
			$nb++;
			echo '<u>'.strtolower($student['mail']).':</u><br/>';
			// echo '<img src="photos/'.$student['student_id'].'.jpg" width="144px" heigth"192px"/><br/>';
			echo $student['first_name'].'<br/>';
			echo 'Promotion: '.$student['year'].$student['department'].'<br/>';
			echo '<label for="'.$student['mail'].'">Gender: </label>';
			if(in_array($student['first_name'], $boys_names)) {
				echo 'Male<input type="radio" name="'.$student['mail'].'" id="'.$student['mail'].'" value="Male" checked="checked"/>';
			} else {
				echo 'Male<input type="radio" name="'.$student['mail'].'" id="'.$student['mail'].'" value="Male"/>';
			}
			if(in_array($student['first_name'], $girls_names)) {
				echo 'Female<input type="radio" name="'.$student['mail'].'" id="'.$student['mail'].'" value="Female" checked="checked"/>';
			} else {
				echo 'Female<input type="radio" name="'.$student['mail'].'" id="'.$student['mail'].'" value="Female"/>';
			}
			echo 'Unknown<input type="radio" name="'.$student['mail'].'" id="'.$student['mail'].'" value=""/><br/>';
			echo '<br/>';
		}
		echo '<input type="submit" name="getGender01" value="Send"/></form>';
		
		echo $nb;
		
	}
	
?>