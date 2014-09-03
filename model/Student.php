<?php
/**
 * * The Student class to store information about a student.
 * * @author Paul Chaignon <paul.chaignon@gmail.com>
 * */
class Student implements JsonSerializable {
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
  private $tags;
  private $fbId;
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
  public function Student() {
  }
  
  public function loadFromNothing($studentID, $firstName, $lastName, $groupe, $mail, $department, $year, $login, $picture = false, $room = '', $gender = '', $photoChanged = false, $tags = '', $fbId = '') {
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
    $this->tags = $tags;
    $this->fbId = $fbId;
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
  public function setPicture($path) {
    $this->picture = $path;
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

  public function setTags($tags) {
    $this->tags = $tags;
  }

  public function jsonSerialize() {
    return [
      'first_name' => $this->firstName,
      'last_name' => $this->lastName,
      'groupe' => $this->groupe,
      'mail' => $this->mail,
      'department' => $this->department,
      'year' => $this->year,
      'picture' => $this->picture,
      'room' => $this->room,
      'gender' => $this->gender,
      'tags' => $this->tags,
      'fb_id' => $this->fbId
    ];
  }

  public function loadFromDB($userDB) {
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
    $this->tags = $userDB['tags'];
    $this->fbId = $userDB['fb_id'];
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
      gender TEXT,
      tags TEXT,
      fb_id TEXT UNIQUE 
    );");
  }


  public function loadByEmail($email) {
    $app = Main::getInstance()->getApp();
    $req = $app['db']->executeQuery('SELECT * FROM students WHERE mail=? COLLATE NOCASE', array($email));
    $userDB = $req->fetch();
    if (count($userDB)>0) {
      $this->loadFromDB($userDB);
      return true;
    } else {
      return false;
    }
  }

  public function loadByName($first_name, $last_name) {
    $app = Main::getInstance()->getApp();
    $req = $app['db']->executeQuery('SELECT * FROM students WHERE first_name=? AND last_name=? COLLATE NOCASE', array($first_name, $last_name));
    if (count($userDB)>0) {
      $this->loadFromDB($userDB);
      return true;
    } else {
      return false;
    }

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
      :gender,
      :tags,
      :fb_id
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
    $req->bindValue("tags",$this->tags);
    $req->bindValue("fb_id",$this->tags);

    error_log("Adding ".$this->login,0);

    $req->execute();
  }
}

