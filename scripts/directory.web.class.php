#!/usr/bin/php
<?php
include '../include/sqlite.php';

$db = connect_db('../data/insannu.db');

$nameEdit = $db->prepare('UPDATE students SET last_name=?, first_name=? WHERE login=?');
$nameEdit->bindParam(1,$last_name);
$nameEdit->bindParam(2,$fist_name);
$nameEdit->bindParam(3,$login);

$dump = file_get_contents('./dump.txt', true);
preg_match_all('/<fieldset>((?!tr).)*<\/fieldset>/s',$dump, $extracted);

$rawusers = $extracted[0];

foreach ($rawusers as $rawuser) {
  $last_name = $first_name = $login = $email = '';
  
  preg_match('/<b> ([A-ZÉÈÊÎÏË\- ]*)&nbsp;([A-ZÉÈÊÎÏË]{1}[a-zéèêëïî \-]*)&nbsp;&nbsp;<\/b>/',$rawuser,$name);
  if (count($name) == 3) {
    $last_name = $name[1];
    $first_name = $name[2];
    $login = strtolower( substr($first_name, 0, 1) . substr($last_name, 0, 7) );
    echo "Update ".$login." name\n";
    $nameEdit->execute();
  
  
    preg_match('/\/AnnuaireENT\/images\/photos\/\d+\.jpg/',$rawuser,$image);
    if (isset($image[0])) {
      $profile = file_get_contents('http://ent.insa-rennes.fr'.$image[0]);
      $fp = fopen('../www/photos/'.$login.'.jpg', 'w');
      fwrite($fp,$profile);
    }
  }
}
?>
