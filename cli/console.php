<?php

if (!$loader = include __DIR__.'/../vendor/autoload.php') {
    die('You must set up the project dependencies.');
}
$app = new \Cilex\Application('Cilex');
$app->command(new Insannu\Fetcher\Command\LdapCommand());
$app->run();

/*        $app = $this->app;*/
//$app->get('/admin/create-database', function() use($app) {
//Student::initDb();
//return 'OK';
//});

//$app->get('/admin/populate-ldap', function() use($app) { 
//$ldap = LDAP::getInstance();
//$ldap->getStudents();
//return "OK";
//});

//$app->get('/admin/populate-ent', function() use($app) {
//$ent = ENT::getInstance();
//$ent->parseFile();
//return "OK"; 
//}); 

//$app->get('/admin/populate-facebook', function() use($app) {
//$ent = Facebook::getInstance();
//$ent->retrieveGroupMembers();
//return "OK"; 
/*}); */
