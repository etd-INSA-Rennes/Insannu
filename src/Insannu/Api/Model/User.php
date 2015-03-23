<?php

namespace Insannu\Api\Model;

class User implements \JsonSerializable {
    private $student;

    protected $app;

    public function __construct($app) {
        $this->app = $app;
    }

    public function initDb() {
        $this->app['db']->executeUpdate("DROP TABLE IF EXISTS users;");
        $this->app['db']->executeUpdate("CREATE TABLE users (
            email TEXT UNIQUE,
            password TEXT,
            login TEXT
        );");

    }

    public function jsonSerialize() {
        return [
            'email' => $this->email,
            'password' => $this->password,
            'login' => $this->login,
        ];
    }
}
