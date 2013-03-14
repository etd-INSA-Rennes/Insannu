<?php
/**********************************************************************
Code pour la connexion : création d'un cookie avec le mdp.
Redirection vers les pages admin.php ou connexion.php selon le mdp.
**********************************************************************/
session_start();

$admins_ips = array('192.168.1.2', '10.133.22.218', '10.133.21.212');
/*if(!in_array($_SERVER['REMOTE_ADDR'], $admins_ips)) {
	$_GET['error'] = 'no_admin';
} else*/if(isset($_POST['password'])) {
	if($_POST['password']=='0peReg|ster') {
		setcookie('admin', 1, time()+60*15, null, null, false, true);
		$_SESSION['admin'] = 1;
		header('Location: index.php');
		exit();
	} else {
		$_GET['error'] = 'no_password';
	}
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
	<title>Insannu</title>
	<link rel="shortcut icon" href="/img/star.gif"/>
	<link href="/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/bootstrap-responsive.min.css" rel="stylesheet">
	<script src="/js/jquery-1.8.2.min.js" type="text/javascript"></script>
	
	<script type="text/javascript">
		$(function() {
			$("div.alert").ready(function(){
				$("div.alert").delay(4000).hide("slow");
				return false;
			});
		});
	</script>
</head>

<body onload="document.getElementById('password').focus();" style="margin: 0 auto; text-align: center;">
	
	<header id="header">
		<h1 id="logo"><a href="."><img src="/img/logo.gif" alt="Insannu" style="text-decoration:none;"/></a></h1>
		<h3 id="title">L'annuaire des &eacute;tudiants</h3><br/>
	</header>
	
	<br/>
	<form action="login.php" method="post" class="container" style="width: 370px;">
		<fieldset class="span4 well" style=" margin: 0 auto;">
			<input type="text" id="login" name="login" placeholder="Login"/><br/>
			<input type="password" id="password" name="password" placeholder="Mot de passe"/><br/>
			<?php 
				// Traitement des erreurs :
				if(isset($_GET['error'])) {
					echo '<div class="alert alert-error">';
					switch($_GET['error']) {
						case "no_password":
							echo '<p style="color:red;">Erreur : mauvais mot de passe</p><br/>';
						break;
						case "no_connect":
							echo '<p style="color:red;">Merci de vous identifier.</p><br/>';
						break;
						case "sess_expired":
							echo '<p style="color:red;">Votre session a expiré.</p><br/>';
						break;
						case "no_admin":
							echo '<p style="color:red;">Vous n\'êtes pas authorisé à accéder à cette partie du site.</p><br/>';
						break;
					}
					echo '</div>';
				}
			?>
			<button class="btn btn-primary" type="submit"><i class="icon-white icon-ok-sign"></i>&nbsp;&nbsp;Valider</button>
		</fieldset>
	</form>
	<br/>
	
	<footer id="footer">
		<p>Merci de signaler tout bug <a href="mailto:pchaigno@insa-rennes.fr">en nous envoyant un e-mail</a>.</p>
		<a href="/insannu">Contact</a>
		<img src="/img/star.gif" alt="Separation mark"/>
		<a href="/about.php">&Agrave; propos</a>
		<img src="/img/star.gif" alt="Separation mark"/>
		<a href="/versions.php">Versions</a>
		<img src="/img/star.gif" alt="Separation mark"/>
		<a href="/stats.php">Statistiques</a>
		<img src="/img/star.gif" alt="Separation mark"/>
		<a href="/advanced_search.php">Recherche Avancée</a>
	</footer>
</body>
</html>
