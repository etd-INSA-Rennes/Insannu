<?php
	
	require('shared/connect.php');
	require('secret/sqlite.php');
	$db = connect_db('secret/insannu.db');
	
	$sql_querys = array(/*'room <> \'Externe\'', */'year = 1', 'year = 2', 'year = 3', 'year = 4', 'year = 5', 'department = \'Doctorant\'', 'department = \'Master\'');
	$lignes = array(/*'&Eacute;tudiants en résidence', */'Premières année', 'Deuxièmes année', 'Troisièmes année', 'Quatrièmes année', 'Cinquièmes année', 'Doctorants', 'Masters');
	$stats = array();
	
	for($i=0 ; $i<count($sql_querys) ; $i++) {
		try {
			$query = $db->prepare('SELECT COUNT(*) FROM students WHERE '.$sql_querys[$i].' GROUP BY gender ORDER BY gender DESC');
			$query->execute();
		} catch(Exception $e) {
			exit('Error : '.$e->getMessage());
		}
		$data = $query->fetchAll(PDO::FETCH_NUM);
		$stats[$i] = array($data[0][0]+$data[1][0], $data[0][0], $data[1][0]);
	}
	
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <title>Insannu</title>
    <link rel="shortcut icon" href="/img/star.gif"/>
    <link href="/css/default.css" rel="stylesheet" type="text/css"/>
</head>
    
<body>

    <?php include('shared/header.php'); ?>
        
    <table name="stats" id="stats" align="center">
	<tr><th width="200px"></th><th width="70px">Total</th><th width="70px">Gars</th><th width="70px">Filles</th></tr>
	<?php
		for($i=0 ; $i<count($stats) ; $i++) {
			echo '<tr>';
			echo '<td>'.$lignes[$i].'</td>';
			echo '<td>'.$stats[$i][0].'</td>';
			echo '<td>'.$stats[$i][1].'</td>';
			echo '<td>'.$stats[$i][2].'</td>';
			echo '<tr>';
		}
	?>
	</table>
	
	<?php include('shared/footer.php'); ?>

</body>
</html>