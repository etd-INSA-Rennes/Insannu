<?php

namespace Insannu\Fetcher\Connector;

use Insannu\Api\Model\Student;

class Ent {

    protected $app;

    public function __construct($app) {
        $this->app = $app;
    }

    public function parseFile() {
        $dump = file_get_contents($this->app['config']['dump']['path'], true);
        preg_match_all('/<fieldset>((?!tr).)*<\/fieldset>/s',$dump, $extracted);

        foreach ($extracted[0] as $rawuser) {
            $email = '';

            if(preg_match('/[-0-9a-zA-Z.]+@insa-rennes.fr/',$rawuser,$email)) {
                $email = $email[0];
                if (preg_match('/\/AnnuaireENT\/images\/photos\/\d+\.jpg/',$rawuser,$image)){
                    $profile = $this->download_file('http://ent.insa-rennes.fr'.$image[0]);
                    if ($profile !== null) {
                        $s = new Student($this->app);
                        if ($s->loadByEmail($email)) {
                            $filename = $s->getStudentID().'.jpg';
                            $webpath = "/bucket/".$filename;
                            $fp = fopen($this->app['baseDir'].'/public/bucket/'.$filename, 'w');
                            fwrite($fp,$profile);
                            error_log("Adding a picture for ".$s->getLastName(),0);
                            $s->setPicture($webpath);
                            $s->save();
                        } else {
                            error_log("No user found for ".$email,0);
                        }
                    }
                } else {
                    error_log("Skip ".$email,0);
                }
            }
        }
    }

    public function parseFolder() {
        $files = scandir($this->app['baseDir'].'/raw/legacy-pictures');
        foreach ($files as $file) {
            $infos = explode('.', $file);
            if (count($infos) === 2 && $infos[1] === 'jpg') {
                $s = new Student($this->app);
                if ($s->loadById($infos[0])) {
                    $filename = $s->getStudentID().'.jpg';
                    $webpath = "/bucket/".$filename;
                    if (copy($this->app['baseDir'].'/raw/legacy-pictures/'.$file, $this->app['baseDir'].'/public/bucket/'.$filename)) {
                        $s->setPicture($webpath);
                        $s->save();
                    } else {
                        error_log("Copy failed for ".$infos[0],0);
                    }
                } else {
                    error_log("No user found for ".$infos[0],0);
                }
            } else {
                error_log("Invalid file ".$file,0);
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
