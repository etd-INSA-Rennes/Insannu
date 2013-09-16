<?php

	exit();

	set_time_limit(0);

	require('secret/sqlite.php');
	$bdd = connect_db('secret/insannu.db');
	
	$doc = new DOMDocument();
	@$doc->loadHTMLFile('annuaire.html');
	$fieldsets = $doc->getElementsByTagName('fieldset');
	$query_update = $bdd->prepare("UPDATE students SET picture = 1 WHERE mail LIKE ?");
	$query_select = $bdd->prepare("SELECT * FROM students WHERE mail LIKE ?");
	$nb = 0;
	for($i=0 ; $i<$fieldsets->length ; $i++) {
		$image = $fieldsets->item($i)->getElementsByTagName('img')->item(0);
		$src = $image->attributes->getNamedItem('src')->nodeValue;
		if(stripos($src, 'mail.png') === false) {
			$links = $fieldsets->item($i)->getElementsByTagName('a');
			if($links->length == 1) {
				$link = $links->item(0);
				$mail = substr($link->attributes->getNamedItem('href')->nodeValue, 7);
				$id = substr($src, 27, -4);
				
				$query_select->execute(array($mail));
				if($student = $query_select->fetch()) {
					if(!$student['picture']) {
						echo $mail.'<br/>';
						if(file_exists('photos/'.$student['student_id'].'.jpg')) {
							echo 'Error: file already exists.<br/>';
						}
						if(!file_exists('photos_annuaire/'.$id.'.jpg')) {
							echo 'Error: file doesn\'t exist.<br/>';
							$nb++;
						}
						echo 'cp photos_annuaire/'.$id.'.jpg photos/'.$student['student_id'].'.jpg<br/><br/>';
						$result = $query_update->execute(array($mail));
						if($result === false) {
							echo 'Error on update for '.$mail.'<br/>';
						}
						$result = system('cp photos_annuaire/'.$id.'.jpg photos/'.$student['student_id'].'.jpg');
						if($result === false) {
							echo 'Error on update for '.$mail.'<br/>';
						}
					}
				} else {
					echo 'Error: nobody with that email address: '.$mail.'<br/><br/>';
				}
			}
		}
	}

	echo $nb.' files missing.';
	
?>