<?php
require_once __DIR__.'/../vendor/autoload.php'; 
require_once __DIR__.'/../model/Student.php'; 
require_once __DIR__.'/../model/StudentFactory.php'; 
require_once __DIR__.'/../connector/LDAP.php'; 

class Main {
  private static $instance;
  private $app;

  private function Main() {
    $this->initialConfiguration();
    $this->setupRoutes();
  }
  public static function getInstance() {
    if (self::$instance == null) {
      self::$instance = new Main();
    }
    return self::$instance;
  }

  public function getApp() {
    return $this->app;
  }

  private function initialConfiguration() {
    $this->app = new Silex\Application(); 
    $this->app->register(new Silex\Provider\DoctrineServiceProvider(), array(
      'db.options' => array(
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/../app.db',
      ),
    ));
    $this->app['debug'] = true;
  }

  private function setupRoutes() {
    $app = $this->app;
    $app->get('/admin/create-database', function() use($app) {
      Student::initDb();
      return 'OK';
    });

    $app->get('/admin/populate-ldap', function() use($app) { 
      $ldap = LDAP::getInstance();
      $ldap->getStudents();
      return "OK";
    });  
    
    $app->get('/random', function() use($app) { 
      return "OK";
    }); 

    $app->get('/search/{keywords}', function($keywords) use ($app) {
      $sf = new StudentFactory(); 
      $sf->search($keywords);
      return $sf->getJSON();
    });
  }

}

Main::getInstance()->getApp()->run();
