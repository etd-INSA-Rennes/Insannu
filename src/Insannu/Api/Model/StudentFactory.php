<?php

namespace Insannu\Api\Model;

use Silex\Application;

class StudentFactory {
    protected $list;
    protected $app;

    public function __construct(Application $app) {
        $this->app = $app;
        $this->list = [];
    }

    public function search($keywords) {
        $keywordsArray = explode(' ', $keywords);

        $sql = "SELECT * FROM students WHERE 1=1 ";
        $params = [];

        foreach ($keywordsArray as $keyword) {
            //ROOM - like BNC205
            if (preg_match('/^[ABCD]{1}[NS]{1}[NCS]{0,1}[0-9]{3}$/i',$keyword)) {
                if($keyword[2] == 'N' || $keyword[2] == 'C' || $keyword[2] == 'S') {
                    $keyword = substr_replace($keyword,'',2,1);  
                }
                $sql .= "AND room LIKE ? ";
                $params[] = $keyword;
                error_log("Match a room : ".$keyword);

            } 

            //CLASS - like 2STPI
            else if (preg_match('/^([1-5]{0,1})(stpi|info|gcu|gma|sgm|eii|src|arom|doctorant)$/i', $keyword, $res)) {
                $year = $res[1];
                $depart = $res[2];
                if ($year != '') {
                    $sql .= "AND year LIKE ? AND department LIKE ? ";
                    $params[] = $year; $params[] = $depart;
                } else  {
                    $sql .= "AND department LIKE ?";
                    $params[] = $depart;
                }
                error_log("Match a class : ".$keyword); 
            } 

            //NAME
            else {
                $sql .= "AND (last_name LIKE ? OR first_name LIKE ? OR login LIKE ?) ";
                array_push($params, '%'.$keyword.'%', '%'.$keyword.'%', '%'.$keyword.'%');

                error_log("Match a name : ".$keyword);
            }
        }

        $sql .= "COLLATE NOCASE ORDER BY last_name ASC";

        $req = $this->app['db']->executeQuery($sql, $params);
        $this->loadFromDB($req->fetchAll());
    }

    private function loadFromDB($pl) {
        foreach ($pl as $u) {
            $s = new Student();
            $s->loadFromDB($u);
            $this->list[] = $s;
        }
    }

    public function getJSON() {
        return json_encode($this->list);
    }
}
