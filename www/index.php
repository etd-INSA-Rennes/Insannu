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
