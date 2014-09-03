<?php
class Facebook {  

  private static $instance;

  private function Facebook() {
  }

  /**
   * @return The instance of Facebook.
   */
  public static function getInstance() {
    if(self::$instance == null) {
      self::$instance = new Facebook();
    }
    return self::$instance;
  }

  public static function retrieveGroupMembers() {
    $access_token = file_get_contents(__DIR__.'/access_token.txt', true);
    $url = "https://graph.facebook.com/444451508952924/members?access_token=".$access_token;
    $groupMembers = file_get_contents($url);
  }
}
