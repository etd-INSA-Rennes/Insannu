<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8"/>
	<title>Insannu</title>
	<link rel="shortcut icon" href="img/star.gif" />
	<link href="css/default.css" rel="stylesheet" type="text/css" />
</head>
	
<body>
	<?php include('../include/header.php'); ?>
		
	<div id="wrapper">
		<p>
			L'Insannu est un site web <a href="contact.php">développé par les étudiants</a>, pour les étudiants.
			Il est uniquement accessible depuis le campus de l'INSA et n'a pas pour vocation d'être accessible de l'extérieur.
		</p>
		<p>
			Les informations personnelles des étudiants sont récupérées régulièrement grâce à divers scripts PHP depuis l'annuaire officiel.</a><br />
			Une base de donnée SQLite est ainsi construite, et nous utilisons PHP pour présenter ces données via un serveur Apache.<br />
			Certaines informations (les photos mises à jour notamment) sont aussi reprises d'une année sur l'autre.<br />
			En somme, l'Insannu n'est que l'annuaire officiel avec plus de possibilités de recherche.<br />
		</p>
	</div>
		
	<?php include('../include/footer.php'); ?>
</body>
</html>
