<?php
/**
 * * The Student class to store information about a student.
 * * @author Paul Chaignon <paul.chaignon@gmail.com>
 * */
class Student {
  private $firstName;
  private $lastName;
  private $studentID;
  private $mail;
  private $department;
  private $year;
  private $room;
  private $picture;
  private $gender;
  private $groupe;
  private $login;
  private $photoChanged;
  /**
   * * Constructor
   * * @param studentID The student's ID as defined in the LDAP server.
   * * @param firstName The first name of the student.
   * * @param lastName The last name of the student.
   * * @param groupe The class of the student if he's in STPI.
   * * @param mail The address mail.
   * * @param department The department.
   * * @param year The year.
   * * @param login The login.
   * * @param picture True if the student has a picture. Default value is false.
   * * @param room The room or 'Externe'. Default value is ''.
   * * @param gender The gender. Default value is ''.
   * * @param photoChanged True if the student changed his photo. Default value is false.
   * */
  public function Student($studentID, $firstName, $lastName, $groupe, $mail, $department, $year, $login, $picture = false, $room = '', $gender = '', $photoChanged = false) {
    $this->studentID = $studentID;
    $this->firstName = $firstName;
    $this->lastName = $lastName;
    $this->groupe = $groupe;
    $this->mail = $mail;
    $this->department = $department;
    $this->year = $year;
    $this->login = $login;
    $this->picture = $picture;
    $this->room = $room;
    $this->gender = $gender;
    $this->photoChanged = $photoChanged;
  }

  /**
   * * @return The email address.
   * */
  public function getEmailAddress() {
    return $this->mail;
  }
  /**
   * * @return The student's ID.
   * */
  public function getStudentID() {
    return $this->studentID;
  }
  /**
   * * @return True if the student changed his photo.
   * */
  public function getPhotoChanged() {
    return $this->photoChanged;
  }
  /**
   * * @return The first name of the student.
   * */
  public function getFirstName() {
    return $this->firstName;
  }
  /**
   * * @return The last name of the student.
   * */
  public function getLastName() {
    return $this->lastName;
  }
  /**
   * * @return The room.
   * */
  public function getRoom() {
    return $this->room;
  }
  /**
   * * @return The gender.
   * */
  public function getGender() {
    return $this->gender;
  }
  /**
   * * @return True if the student's gender is known.
   * */
  public function hasGender() {
    return $this->gender != '';
  }
  /**
   * * @return True if the student has a photo.
   * */
  public function hasPicture() {
    return $this->picture != '';
  }
  /**
   * * @return True if the student's room is known.
   * */
  public function hasRoom() {
    return $this->room != '';
  }
  /**
   * * Sets the picture attribute to true (the student has a picture)
   * * and updates the photoChanged parameter.
   * * @param photoChanged True if the student changed his photo.
   * */
  public function setPicture($photoChanged) {
    $this->picture = 1;
    $this->photoChanged = $photoChanged;
  }
  /**
   * * Sets the gender of the student.
   * * @param gender The gender.
   * */
  public function setGender($gender) {
    $this->gender = $gender;
  }
  /**
   * * Sets the room of the student.
   * * @param room The room.
   * */
  public function setRoom($room) {
    if($room == null) {
      $this->room = '';
    } else {
      $this->room = $room;
    }
  }

  private function loadUser($userDB) {
    $this->studentID = $userDB['student_id'];
    $this->firstName = $userDB['first_name'];
    $this->lastName = $userDB['last_name'];
    $this->groupe = $userDB['groupe'];
    $this->mail = $userDB['mail'];
    $this->department = $userDB['department'];
    $this->year = $userDB['year'];
    $this->login = $userDB['login'];
    $this->picture = $userDB['picture'];
    $this->room = $userDB['room'];
    $this->gender = $userDB['gender'];
  }

  public static function initDb() {
    $app = Main::getInstance()->getApp();
    $app['db']->executeUpdate("DROP TABLE IF EXISTS students;");
    $app['db']->executeUpdate("CREATE TABLE students (
      student_id INT PRIMARY KEY,
      login TEXT UNIQUE,
      first_name TEXT,
      last_name TEXT,
      groupe TEXT,
      year TEXT,
      mail TEXT UNIQUE,
      department TEXT,
      room TEXT,
      picture TEXT,
      gender TEXT 
    );");
  }


  public function getByEmail($email) {
    $req = $app['db']->executeQuery('SELECT * FROM students WHERE email=?', array($email));
    $userDB = $req->fetch();
    $this->loadUser($userDB);
  }

  public function save() {
    $app = Main::getInstance()->getApp();
    $req = $app['db']->prepare('INSERT OR REPLACE INTO students VALUES (
      :student_id,
      :login,
      :first_name,
      :last_name,
      :groupe,
      :year,
      :mail,
      :department,
      :room,
      :picture,
      :gender
    )');

    $req->bindValue("student_id",$this->studentID);
    $req->bindValue("login",$this->login);
    $req->bindValue("first_name",$this->firstName);
    $req->bindValue("last_name",$this->lastName);
    $req->bindValue("groupe",$this->groupe);
    $req->bindValue("year",$this->year);
    $req->bindValue("mail",$this->mail);
    $req->bindValue("department",$this->department);
    $req->bindValue("room",$this->room);
    $req->bindValue("picture",$this->picture);
    $req->bindValue("gender",$this->gender);

    error_log("Adding ".$this->login,0);

    $req->execute();
  }
}

