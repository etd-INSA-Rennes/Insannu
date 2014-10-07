<?php

namespace Insannu\Fetcher\Connector;

use Insanny\Api\Model\Student;

class Ent {

  private static $instance;

  private function ENT() {
  }

  /**
   * @return The instance of ENT.
   */
  public static function getInstance() {
    if(self::$instance == null) {
      self::$instance = new ENT();
    }
    return self::$instance;
  }

  public function parseFile() {
    $dump = file_get_contents(__DIR__.'/dump.txt', true);
    preg_match_all('/<fieldset>((?!tr).)*<\/fieldset>/s',$dump, $extracted);

    foreach ($extracted[0] as $rawuser) {
      $email = '';

      if(preg_match('/[-0-9a-zA-Z.]+@insa-rennes.fr/',$rawuser,$email)) {
        $email = $email[0];
        if (preg_match('/\/AnnuaireENT\/images\/photos\/\d+\.jpg/',$rawuser,$image)){
          $profile = $this->download_file('http://ent.insa-rennes.fr'.$image[0]);
          if ($profile !== null) {
            $s = new Student();
            if ($s->loadByEmail($email)) {
              $filename = $s->getStudentID().'.jpg';
              $webpath = "http://insannu.fr.cr/bucket/".$filename;
              $fp = fopen(__DIR__.'/../public/bucket/'.$filename, 'w');
              fwrite($fp,$profile);
              error_log("Adding a picture for ".$s->getLastName(),0);
              $s->setPicture($webpath);
              $s->save();
            } else {
              error_log("No user found for ".$s->getLastName(),0);
            }
          }
        } else {
          error_log("Skip ".$s->getLastName(),0);
        }
      }
    }
  }

  private function download_file($url) {
    $headers = get_headers($url);
    if (substr($headers[0], 9, 3) != "200") {
      return null;
    } else {
      return file_get_contents($url);
    }
  }
}
?>
