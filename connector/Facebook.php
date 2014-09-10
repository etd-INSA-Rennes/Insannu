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
    $groupMembers = http_get($url);
    var_dump($groupMembers);
  }

  private static function http_get($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //One of the worst idea I agree
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
  }
}
