<?php

	exit();

	require('secret/sqlite.php');
	connect_db('secret/insannu.db');
	
	$doc = new DOMDocument();
	@$doc->loadHTMLFile("annuaire.html");
	$fieldsets = $doc->getElementsByTagName('fieldset');
	$query = $GLOBALS['bdd']->prepare("UPDATE students SET student_id = ? WHERE mail LIKE ?");
	for($i=0 ; $i<$fieldsets->length ; $i++) {
		$image = $fieldsets->item($i)->getElementsByTagName('img')->item(0);
		$src = $image->attributes->getNamedItem('src')->nodeValue;
		if(stripos($src, 'mail.png')===false) {
			$link = $fieldsets->item($i)->getElementsByTagName('a')->item(0);
			$mail = substr($link->attributes->getNamedItem('href')->nodeValue, 7);
			$id = substr($src, 27, -4);
			
			$query->execute(array($id, $mail));
			echo $mail.'<br/>';
			echo $id.'<br/><br/>';
		}
	}
	
?>