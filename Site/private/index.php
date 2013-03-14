<?php
	
	session_start();
	
	if(!isset($_SESSION['admin']) || $_SESSION['admin']!=1) {
		header('Location: login.php?error=no_connect');
		exit();
	} elseif(!isset($_COOKIE['admin'])) {
		header('Location: login.php?error=sess_expired');
		exit();
	} else {
		setcookie('admin', 1, time()+60*15, null, null, false, true);
	}
	
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
	<link rel="shortcut icon" href="/img/star.gif"/>
	<link href="/css/default.css" rel="stylesheet" type="text/css"/>
	<title>Insannu</title>
	<style>
		td {
			padding: 1px 5px 1px 5px;
		}
	</style>
</head>

<body>
	<div id="header"><h1 id="logo"><a href="."><img src="/img/logo.gif" alt="Insannu"/></a></h1></div>
	
	<span style="position:fixed; top: 1em;right: 1em;"><a href="logout.php">Deconnexion</a></span>
	
	<h2 id="title">Page d'administration</h2>
	
	<form action="index.php" method="get">
		<select name="item" id="item" onchange="document.forms[0].submit();">
			<option value=""></option>
			<option value="LRE">Liste des recherches effectuées</option>
			<option value="LCE">Liste des connexions établies</option>
			<option value="LEI">Liste des étudiants ayant déjà utilisé l'Insannu</option>
		</select>
	</form>

	<?php
		if(isset($_GET['item']) && ($_GET['item']=='LRE' || $_GET['item']=='LCE' || $_GET['item']=='LEI')) {
			require('../secret/mysql.php');
			
			// Choix de la requete a effectuer :
			switch($_GET['item']) {
				case 'LRE':
					$sql = "SELECT date, search, maillist FROM searches ORDER BY date DESC";
					$colonnes = "<th>Date</th><th>Mots clés</th><th>Maillist</th>";
					echo '<h3>Liste des recherches effectuées</h3>';
				break;
				case "LCE":
					$sql = "SELECT date, origin_page, landing_page FROM connections WHERE origin_page != '' OR landing_page != '' ORDER BY date DESC";
					$colonnes = "<th>Date</th><th>Page d'origine</th><th>Landing page</th>";
					echo '<h3>Liste des connexions établies</h3>';
				break;
				case "LEI":
					$sql = "SELECT DISTINCT ip_address, last_connection, navigator, nb_connections FROM pages_visited, students WHERE student_id = students.id ORDER BY last_connection DESC";
					$colonnes = "<th>Adresse IP</th><th>Dernière connexion</th><th>Navigateur</th><th>Nombre de connexions</th><th>page</th>";
					echo '<h3>Liste des étudiants ayant déjà utilisé l\' Insannu</h3>';
				break;
			}
			
			// Exectution de la requete :
			try {
				$query = $GLOBALS['bdd_mysql']->prepare($sql);
				$query->execute();
			} catch(Exception $e) {
				exit("Error: ".$e->getMessage());
			}
				
			// Affichage des requetes :	
			echo '<table align="center" border="0">'; 
			// Affichage des requetes : Liste des etudiants ayant deja utilise l'Insannu (cas particulier) :
			if($_GET['item']=="LEI") {
				echo '<tr>';
				echo $colonnes;
				echo '</tr>';	
				
				while($donnees = $query->fetch(PDO::FETCH_ASSOC)) {
					echo '<tr>'; 
					foreach($donnees as $key=>$value) {
						if($key=="last_connection" || $key=="date") { // Traitement de la date timestamp.
							echo '<td>'.date('d\/m\/y G\:i', $value+7200).'</td>';
						} elseif($key=="ip_address") {
							echo '<td><a href="index.php?search='.$value.'">'.$value.'</a></td>';
						} else {
							echo '<td>'.$value.'</td>';
						}
					}
					// Code permettant de concatener les pages visitees par un meme individu :
					try {
						$query1 = $GLOBALS['bdd_mysql']->prepare('SELECT page FROM pages_visited, students WHERE student_id = students.id AND ip_address = ?');
						$query1->execute(array($donnees['ip_address']));
					} catch(Exception $e) {
						exit("Error: ".$e->getMessage());
					}
					
					echo '<td><a class="info" href="#">pages visitées<span>'; // Affichage d'une infobulle.
					while($donnees1 = $query1->fetch(PDO::FETCH_ASSOC)) {
						foreach ($donnees1 as $key=>$value) {
							echo $value.' ';
						}
					}
					echo '</span></a></td></tr>';
				}
					
			// Affichage des requetes : autres cas :
			} else {
				echo '<tr>';
				echo $colonnes;
				echo '</tr>';	
			
				while($donnees = $query->fetch(PDO::FETCH_ASSOC)) {
					echo '<tr>'; 
					foreach($donnees as $key=>$value) {
						if($key=="last_connection" || $key=="date") { // Traitement de la date timestamp.
							echo '<td>'.date('d\/m\/y G\:i', $value+7200).'</td>';
						} else {
							echo '<td>'.$value.'</td>';
						}
					}
					echo '</tr>'; 
				}
			}
			echo '</table>';
			$query->closeCursor();
		}
		echo '<br/><br/><br/>';
	?>
</body>
</html>
