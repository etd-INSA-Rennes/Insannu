<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8"/>
	<title>Insannu</title>
	<link rel="shortcut icon" href="img/star.gif"/>
	<link href="css/default.css" rel="stylesheet" type="text/css"/>
	<script src="js/ajax.js" type="text/javascript"></script>
	<script src="js/search.js" type="text/javascript"></script>
	<script src="js/maillist.js" type="text/javascript"></script>
	<script src="js/jquery-1.8.2.min.js" type="text/javascript"></script>
</head>
	
<body onload="infiniteScroll(); $('#search').focus(); askServeur();">
	<?php include('../include/header.php'); ?>
			
	L'Insannu est actuellement en maintenance. Veuillez réessayer dans un instant.
	
	<ul id="stack" style="display: none;"></ul>

	<?php include('../include/footer.php'); ?>
</body>
</html>
