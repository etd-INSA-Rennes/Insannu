<?php

exit();

if(!isset($_GET['id']) || $_GET['id']!='458d45re8e4f5f8d7ee4d5cf58gfd4') {
	header('Location: index.php');
	exit();
}
// exit("Temporaire...");



/*******************************
	LECTURE DE LA BASE SQLITE
*******************************/
try {
	$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
	$dbb = new PDO('sqlite:secret/insannu.db');
} catch(PDOException $e) {
    exit('Erreur : '.$e->getMessage());
}

try {
	$query = $dbb->prepare("SELECT * FROM statistics");
	$query->execute();
} catch(PDOException $e) {
	exit("Erreur : ".$e->getMessage());
}

$statistics = $query->fetchAll(PDO::FETCH_ASSOC);
$query->closeCursor();



/**************************************
	CHANGEMENT DU MOT DE PASSE MYSQL
**************************************/
try {
    $dbb = new PDO('mysql:dbname=mysql;host=127.0.0.1', 'root', '');
} catch (PDOException $e) {
    exit('Erreur : '.$e->getMessage());
}

try {
	$query = $dbb->prepare("UPDATE user SET password = PASSWORD('1n\$annu') WHERE user = 'root'");
	$query->execute();
} catch(PDOException $e) {
	exit('Erreur : '.$e->getMessage());
}

try {
	$query = $dbb->prepare("FLUSH PRIVILEGES;");
	$query->execute();
} catch(PDOException $e) {
	exit('Erreur : '.$e->getMessage());
}

$query->closeCursor();



/*******************************
	CREATION DE LA BDD MYSQL
*******************************/
try {
    $dbb = new PDO('mysql:host=127.0.0.1', 'root', '1n$annu');
} catch (PDOException $e) {
    exit('Erreur : '.$e->getMessage());
}

try {
	$query = $dbb->prepare("CREATE DATABASE `insannu`");
	$query->execute();
} catch(PDOException $e) {
	exit("Erreur : ".$e->getMessage());
}

$query->closeCursor();

try {
    $dbb = new PDO('mysql:dbname=insannu;host=127.0.0.1', 'root', '1n$annu');
} catch (PDOException $e) {
    exit('Erreur : '.$e->getMessage());
}

try {
	$query = $dbb->prepare("CREATE TABLE `statistics`(`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, `date` INT NOT NULL, `ident` TEXT, `search` TEXT)");
	$query->execute();
} catch(PDOException $e) {
	exit("Erreur : ".$e->getMessage());
}

$query->closeCursor();



// Toutes les stats seront maintenant enregistrees dans la nouvelle BDD :
$handle = fopen("common/mysql.php", "w");
fwrite($handle, '<?php $mysql=true; ?>');
fclose($handle);



/*********************************************
	ECRITURE DES DONNEES DANS LA BDD MYSQL
*********************************************/
try {
    $dbb = new PDO('mysql:dbname=insannu;host=127.0.0.1', 'root', '1n$annu');
} catch (PDOException $e) {
    exit('Erreur : '.$e->getMessage());
}

$query_sql = '';
$params = array();
for($i=0 ; $i<count($statistics) ; $i++) {
	if($i==count($statistics)-1) {
		$query_sql .= "(?, ?, ?)";
	} else {
		$query_sql .= "(?, ?, ?),";
	}
	$params = array_merge($params, array($statistics[$i]['date'], $statistics[$i]['ip_address'], $statistics[$i]['search']));
}
try {
	$query = $dbb->prepare("INSERT INTO statistics(date, ident, search) VALUES".$query_sql);
	$query->execute($params);
} catch(PDOException $e) {
	exit("Erreur : ".$e->getMessage());
}

$query->closeCursor();

?>