<?php
/******************************************************************
Code pour la deconnexion : detruit le cookie d'authentification
******************************************************************/
setcookie('mdp', '', time(), null, null, false, true);
header('Location: login.php');

?>
