<?php

	require_once('../include/tools.php');
	if(inMaintenance()) {
		header('Location: maintenance.php');
		exit();
	}

	$search = isset($_GET['search'])? $_GET['search'] : '';

?>
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
	
	<noscript>
		Tu dois activer le javascript pour que la recherche instantannée fonctionne !
	</noscript>
			
	<form id="searchform" method="get" action="index.php">
		<input type="hidden" id="previous_search" name="previous_search" value=""/>
		<input type="text" id="search" name="search" onkeyup="recherche();" value="<?php echo $search; ?>"/>
		<a class="button">Recherche Insannu</a>
	</form>
	
	<div id="maillist"></div>
	<ul id="results"></ul>
	
	<ul id="stack" style="display: none;"></ul>
		
	<?php include('../include/footer.php'); ?>
	
</body>
</html>
