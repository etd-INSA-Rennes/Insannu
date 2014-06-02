<?php
/**
 * Copyright (c) 2014 Paul Chaignon <paul.chaignon@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, distribute with modifications, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE ABOVE COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR
 * THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * Except as contained in this notice, the name(s) of the above copyright
 * holders shall not be used in advertising or otherwise to promote the
 * sale, use or other dealings in this Software without prior written
 * authorization.
 */
	
require_once('../include/sqlite.php');
$db = connect_db('../data/insannu.db');

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
    <link rel="shortcut icon" href="img/star.gif"/>
    <link href="css/default.css" rel="stylesheet" type="text/css"/>
</head>
<body>
    <?php include('../include/header.php'); ?>
        
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
	
	<?php include('../include/footer.php'); ?>
</body>
</html>
