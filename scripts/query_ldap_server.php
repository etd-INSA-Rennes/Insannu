#!/usr/bin/php
<?php
include 'conf.php';

echo "Populate Database\n===============\n\n";
echo "Connecting to ".Conf::$ip."...\n";
$ds=ldap_connect(Conf::$ip);  
echo "Result code is ". $ds ."\n\n";

if ($ds) {
  ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
   
  echo "Linking with ".Conf::$user."...\n"; 
  $r=ldap_bind($ds, Conf::$user, Conf::$pass);
  echo "Result code is " . $r . "\n\n";
  
  $pageSize = 100;
  $cookie = '';
  $found = 0;

  do {
    echo "Found " . $found . " entries\n";
    
    ldap_control_paged_result($ds, $pageSize, true, $cookie);
    echo "Searching users on ".Conf::$base."...\n";
    $sr=ldap_search($ds,Conf::$base, "objectClass=organizationalPerson");  
    echo "Result is " . $sr . "\n\n";

    $found += ldap_count_entries($ds,$sr);

    echo "Reading entries...\n";
    $info = ldap_get_entries($ds, $sr);

    for ($i=0; $i<$info["count"]; $i++) {
      $login = utf8_encode($info[$i]["name"][0]);
      $displayName = utf8_encode($info[$i]["displayname"][0]);
      $description = (isset($info[$i]["description"])) ? utf8_encode($info[$i]["description"][0]) : '';
      $memberOf = (isset($info[$i]["memberof"])) ? $info[$i]["memberof"] : [];
      $tags = get_tags($memberOf);

      echo "---------------\n";
      echo "Login : " . $login . "\n";
      echo "Display name : " . $displayName . "\n";
      echo "Description : " . $description . "\n";
      echo "Tags : " . $tags . "\n";
      echo "---------------\n\n";

    }
    ldap_control_paged_result_response($ds, $sr, $cookie);

  } while($cookie !== null && $cookie != '');

  echo "Closing session. Good bye !";
  ldap_close($ds);

} else {
    echo "Unable to connect to the LDAP server\n\n";
}

function get_tags($members) {
  $tags = '';
  for ($j=0; $j<$members["count"]; $j++) {
   $tags .= substr(explode(',',$members[$j])[0], 3) . ','; 
  }
  return substr($tags,0,-1);
}
?>

