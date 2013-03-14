<?php

exit();

require('secret/mysql_register.php');

$rooms_file = file_get_contents('map.txt');
$rooms = explode("\n", $rooms_file);
$map = array();
foreach($rooms as $room) {
	$infos = explode(':', $room);
	$map[$infos[0]] = $infos[1];
}

try {
	$query = $GLOBALS['bdd_mysql']->prepare("SELECT * FROM students WHERE ip_address REGEXP '.+' AND ip_address <> 'No connection'");
	$query->execute();
} catch(Exception $e) {
	exit('Error: '.$e->getMessage());
}

$nb = 0;
$nb_externe = 0;
$nb_error = 0;
$update = $GLOBALS['bdd_mysql']->prepare("UPDATE students SET room = ? WHERE mail = ?");
$update_extern = $GLOBALS['bdd_mysql']->prepare("UPDATE students SET room = 'Externe' WHERE mail = ?");
while($student = $query->fetch()) {
	if($student['ip_address']=='externe') {
		echo $student['first_name'].' '.$student['last_name'].': Externe<br/>';
		$nb_externe++;
		try {
			$update_extern->execute(array($student['mail']));
		} catch(exception $e) {
			exit('Error: '.$e->getMessage());
		}
	} elseif(isset($map[$student['ip_address']])) {
		echo $student['first_name'].' '.$student['last_name'].': '.strtoupper($map[$student['ip_address']]).'<br/>';
		$nb++;
		try {
			$update->execute(array(strtoupper($map[$student['ip_address']]), $student['mail']));
		} catch(exception $e) {
			exit('Error: '.$e->getMessage());
		}
	} else {
		echo 'Error on '.$student['ip_address'].'<br/>';
		$nb_error++;
	}
}

echo '<br/>';
echo 'Erreurs: '.$nb_error.'<br/>';
echo 'Internes: '.$nb.'<br/>';
echo 'Externes: '.$nb_externe;

?>