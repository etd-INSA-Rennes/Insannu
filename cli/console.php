<?php

if (!$loader = include __DIR__.'/../vendor/autoload.php') {
    die('You must set up the project dependencies.');
}
$app = new \Cilex\Application('Cilex');
$app->register(new Cilex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/../app.db',
    ),
));
$app->command(new Insannu\Fetcher\Command\LdapCommand());
$app->command(new Insannu\Fetcher\Command\EntCommand());
$app->command(new Insannu\Fetcher\Command\FacebookCommand());

$app->command(new Insannu\Api\Command\CreateDatabaseCommand());
$app->run();


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
