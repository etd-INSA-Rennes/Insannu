<?php

require('secret/mysql_register.php');

$sql = "(select distinct(r.id) as id, r.ip_address as ip_address, s.first_name as first_name, s.last_name as last_name from registrations as r, students as s where s.id = r.id and r.id not in (select r.id from registrations as r, load_logo as l where r.id = l.id and r.ip_address <> l.ip_address) and (r.ip_address = 'externe' or r.ip_address regexp '10\.133\.(8|9|10|11|12|13|14)\..+'))";
$sql .= " union ";
$sql .= "(select distinct(r.id) as id, r.ip_address as ip_address, s.first_name as first_name, s.last_name as last_name from load_logo as r, students as s where s.id = r.id and r.id not in (select r.id from registrations as r, load_logo as l where r.id = l.id and r.ip_address <> l.ip_address) and (r.ip_address = 'externe' or r.ip_address regexp '10\.133\.(8|9|10|11|12|13|14)\..+'))";

try {
	$ids = $GLOBALS['bdd_mysql']->prepare($sql);
	$ids->execute();
} catch(Exception $e) {
	exit('Error: '.$e->getMessage());
}

$nb_extern = 0;
$nb = 0;
$nb_update = 0;
$update = $GLOBALS['bdd_mysql']->prepare("UPDATE students SET ip_address = ? WHERE id = ? AND ip_address = ''");
while($id = $ids->fetch()) {
	echo $id['id'].': '.$id['first_name'].' '.$id['last_name'].' => '.$id['ip_address'].'<br/>';
	try {
		if($update->execute(array($id['ip_address'], $id['id']))) {
			$nb_update++;
		}
	} catch(Exception $e) {
		exit('Error: '.$e->getMessage());
	}
	if($id['ip_address']=='externe') {
		$nb_extern++;
	} else {
		$nb++;
	}
}
echo $nb_extern.'<br/>';
echo $nb.'<br/>';
echo $nb_update.'<br/>';

?>