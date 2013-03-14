<?php
	
	require("shared/connect.php");
	
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <title>Insannu</title>
    <link rel="shortcut icon" href="img/star.gif" />
    <link href="/css/default.css" rel="stylesheet" type="text/css" />
</head>
    
<body>
    <?php include("shared/header.php"); ?>
        
    <div id="wrapper">
        <p>
            L'Insannu est un site web <a href="contact.php">développé par les étudiants</a>, pour les
            étudiants. Il est uniquement accessible depuis le campus de l'INSA
            et n'a pas pour vocation d'être accessible de l'extérieur.
        </p>
        <p>
            Les informations personnelles des étudiants sont récupérées
            régulièrement grâce à un script Python depuis
            <a href="http://www.insa-rennes.fr/annuaire-insa-rennes">l'annuaire officiel.</a><br />
            Le script permet au passage d'ajouter différentes informations
            telles que les adresses IP ou les groupes (pour 1ère et 2ème années).<br />
            Une base de donnée SQLite est ainsi construite, et nous utilisons
            PHP pour présenter ces données via un serveur Apache.<br />
            En somme, l'Insannu n'est que l'annuaire officiel avec plus de
            possibilités de recherche.<br />
        </p>
    </div>
        
	<?php include("shared/footer.php"); ?>

</body>
</html>
