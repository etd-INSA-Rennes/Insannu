<?php

require('secret/mysql_register.php');

$results = file_get_contents('mails_results.txt');
$results = explode("\n", $results);
$query = $GLOBALS['bdd_mysql']->prepare("UPDATE students SET mail_sent = 1 WHERE id = ?");
foreach($results as $mail) {
	$infos = explode(' : ', $mail);
	echo $infos[0].'<br/>';
	echo $infos[1].'<br/>';
	echo $infos[2].'<br/>';
	echo '<br/>';
	if($infos[1]==1) {
		try {
			$result_query = $query->execute(array($infos[2]));
		} catch(Exception $e) {
			exit('Error: '.$e->getMessage());
		}
	}
	if($result_query===false) {
		echo 'Probleme !!!!';
	}
}

?>