<?php

if (!$loader = include __DIR__.'/../vendor/autoload.php') {
    die('You must set up the project dependencies.');
}
$app = new \Cilex\Application('Cilex');

$app['baseDir'] = __DIR__.'/..';

$app->register(new Cilex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/../app.db',
    ),
));


$app->register(new Cilex\Provider\ConfigServiceProvider(), array('config.path' => __DIR__.'/config.yml'));

$app->command(new Insannu\Fetcher\Command\LdapCommand());
$app->command(new Insannu\Fetcher\Command\DirectoryPictureCommand());
$app->command(new Insannu\Fetcher\Command\LegacyPictureCommand());
$app->command(new Insannu\Fetcher\Command\FacebookCommand());

$app->command(new Insannu\Api\Command\CreateDatabaseCommand());
$app->run();

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
