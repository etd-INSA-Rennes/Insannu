<?php
class ENT {

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
            $filename = md5($email);
            $fp = fopen(__DIR__.'/../public/bucket/'.$filename.'.jpg', 'w');
            fwrite($fp,$profile);
            $s = new Student();
            $s->loadByEmail($email);
            error_log("Adding a picture for ".$s->getLastName(),0);
            $s->setPicture($filename);
            $s->save();
          }
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
