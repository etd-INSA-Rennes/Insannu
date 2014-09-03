<?php

/**
 * Retrieves information from the LDAP server.
 * This class follows a Singleton pattern.
 * @author Paul Chaignon <paul.chaignon@gmail.com>
 * @author Quentin Dufour <quentin@deuxfleurs.fr>
 */

require_once('../model/Student.php');
class LDAP {
  const SERVER = 'ldap.insa-rennes.fr';
  const PORT = 389;
  const DN = 'ou=people,dc=insa-rennes,dc=fr';
  private static $instance;
  private $connection = null;
  /**
   * Constructor
   * The constructor is private because the class follows a Singleton pattern.
   */
  private function LDAP() {
    $this->link = null;
    $this->connect();
  }
  /**
   * Destructor
   * Closes the connections to the LDAP server.
   */
  public function __destruct() {
    if($this->link) {
      ldap_close($this->link);
    }
  }
  /**
   * @return The instance of LDAP.
   */
  public static function getInstance() {
    if(self::$instance == null) {
      self::$instance = new LDAP();
    }
    return self::$instance;
  }
  /**
   * Connects to the LDAP server.
   * @throws RuntimeException If the connection fails.
   * @return False if the connection was already opened.
   */
  protected function connect() {
    if($this->connection) {
      return false;
    }
    $this->connection = ldap_connect(self::SERVER, self::PORT);
    if(!$this->connection) {
      throw new RuntimeException('Could not connect to LDAP server.');
    }
    ldap_bind($this->connection);
    return true;
  }
  /**
   * Retrieves the students from the LDAP server.
   * @return The students as Student object.
   */
  public function getStudents() {
    if(!$this->connection) {
      throw new RuntimeException('LDAP not available.');
    }
    $results = @ldap_search($this->connection, self::DN, 'insapopulation~=etudiant');
    $results = ldap_get_entries($this->connection, $results);
    if($results['count'] < 1) {
      return null;
    }
    $students = array();
    unset($results['count']);
    foreach($results as $result) {
      $studentID = $result['uidnumber'][0];
      $lastName = $result['sn'][0];
      $firstName = $result['givenname'][0];
      $mail = $result['mail'][0];
      $login = $result['uid'][0];
      $promotion = $result['insaclasseetu'][0];
      // Parses the department, year and class.
      $class = '';
      if($promotion == 'DOCT') {
        $department = 'Doctorant';
        $year = '';
      } else if($promotion=='MAST' || $promotion=='MS') {
        $department = 'Master';
        $year = '';
      } else if($promotion == 'SPIR') {
        $department = $promotion;
        $year = '';
      } else if($promotion == 'REPOR') {
        continue;
      } else if(strlen($promotion) == 2) {
        $department = 'STPI';
        $year = $promotion[0];
      } else if(strlen($promotion) > 3) {
        $department = substr($promotion, 1);
        $year = $promotion[0];
      }
      (new Student($studentID, $firstName, $lastName, $class, $mail, $department, $year, $login))->save();
    }
    return $students;
  }
}
