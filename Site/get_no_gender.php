<?php

	exit();

	require("secret/sqlite.php");
	$bdd = connect_db("secret/insannu.db");
	
	if(isset($_POST['getGender01'])) {
		
		$query = $bdd->prepare("UPDATE students SET gender = ? WHERE login LIKE ?");
		foreach($_POST as $login=>$gender) {
			if($gender!='') {
				echo $login.' '.$gender.'<br/>';
				try {
					$query->execute(array($gender, $login));
				} catch(PDOException $e) {
					exit("Erreur : ".$e->getMessage());
				}
			}
		}
		
	} else {
	
		try {
			$query = $bdd->prepare("SELECT * FROM students WHERE gender IS NULL");
			$query->execute();
		} catch(PDOException $e) {
			exit("Erreur : ".$e->getMessage());
		}
		
		echo '<form method="post" action="get_no_gender.php">';
		$nb = 0;
		while($student = $query->fetch(PDO::FETCH_ASSOC)) {
			$nb++;
			echo '<u>'.strtolower($student['login']).':</u><br/>';
			if($student['picture']) {
				echo '<img src="photos/'.$student['student_id'].'.jpg" width="144px" heigth"192px"/><br/>';
			}
			echo $student['first_name'].'<br/>';
			echo 'Promotion: '.$student['year'].$student['department'].'<br/>';
			echo 'Gender: ';
			echo '<label for="'.$student['login'].'_male">Male</label><input type="radio" name="'.$student['login'].'" id="'.$student['login'].'_male" value="Male"/>';
			echo '<label for="'.$student['login'].'_female">Female</label><input type="radio" name="'.$student['login'].'" id="'.$student['login'].'_female" value="Female"/>';
			echo '<label for="'.$student['login'].'_unknown">Unknown</label><input type="radio" name="'.$student['login'].'" id="'.$student['login'].'_unknown" value=""/><br/>';
			echo '<br/>';
		}
		echo '<input type="submit" name="getGender01" value="Send"/></form>';
		
		echo $nb;
		
	}
	
?>