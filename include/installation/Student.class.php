<?php

/**
 * The Student class to store information about a student.
 * @author Paul Chaignon <paul.chaignon@gmail.com>
 */
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
	 * Constructor
	 * @param studentID The student's ID as defined in the LDAP server.
	 * @param firstName The first name of the student.
	 * @param lastName The last name of the student.
	 * @param groupe The class of the student if he's in STPI.
	 * @param mail The address mail.
	 * @param department The department.
	 * @param year The year.
	 * @param login The login.
	 * @param picture True if the student has a picture. Default value is false.
	 * @param room The room or 'Externe'. Default value is ''.
	 * @param gender The gender. Default value is ''.
	 * @param photoChanged True if the student changed his photo. Default value is false.
	 */
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
	 * @return The email address.
	 */
	public function getEmailAddress() {
		return $this->mail;
	}

	/**
	 * @return The student's ID.
	 */
	public function getStudentID() {
		return $this->studentID;
	}

	/**
	 * @return True if the student changed his photo.
	 */
	public function getPhotoChanged() {
		return $this->photoChanged;
	}

	/**
	 * @return The first name of the student.
	 */
	public function getFirstName() {
		return $this->firstName;
	}

	/**
	 * @return The last name of the student.
	 */
	public function getLastName() {
		return $this->lastName;
	}

	/**
	 * @return The room.
	 */
	public function getRoom() {
		return $this->room;
	}

	/**
	 * @return The gender.
	 */
	public function getGender() {
		return $this->gender;
	}

	/**
	 * @return True if the student's gender is known.
	 */
	public function hasGender() {
		return $this->gender != '';
	}

	/**
	 * @return True if the student has a photo.
	 */
	public function hasPicture() {
		return $this->picture != '';
	}

	/**
	 * @return True if the student's room is known.
	 */
	public function hasRoom() {
		return $this->room != '';
	}

	/**
	 * Sets the picture attribute to true (the student has a picture)
	 * and updates the photoChanged parameter.
	 * @param photoChanged True if the student changed his photo.
	 */
	public function setPicture($photoChanged) {
		$this->picture = 1;
		$this->photoChanged = $photoChanged;
	}

	/**
	 * Sets the gender of the student.
	 * @param gender The gender.
	 */
	public function setGender($gender) {
		$this->gender = $gender;
	}

	/**
	 * Sets the room of the student.
	 * @param room The room.
	 */
	public function setRoom($room) {
		if($room == null) {
			$this->room = '';
		} else {
			$this->room = $room;
		}
	}

	/**
	 * @return The SQL parameters for the first insertion in the database.
	 */
	public function getSQLInsertParameters() {
		return array(
				$this->studentID,
				$this->firstName,
				$this->lastName,
				$this->groupe,
				$this->mail,
				$this->department,
				$this->year,
				$this->login
			);
	}

	/**
	 * Displays the initial information about the student (those from the LDAP server).
	 * This is used to verify the update.
	 */
	public function display() {
		echo $this->firstName.' '.$this->lastName.' ('.$this->login.")<br/>\n";
		echo $this->mail."<br/>\n";
		if($this->groupe == '') {
			echo $this->year.$this->department."<br/>\n";
		} else {
			echo $this->year.$this->department.'-'.$this->groupe."<br/>\n";
		}
		echo $this->studentID."<br/><br/>\n\n";
	}

	/**
	 * Displays the gender of the student along with some other information to identify him.
	 * This is used to verify the update.
	 */
	public function displayGender() {
		echo $this->firstName.' '.$this->lastName."<br/>\n";
		if($this->groupe == '') {
			echo $this->year.$this->department."<br/>\n";
		} else {
			echo $this->year.$this->department.'-'.$this->groupe."<br/>\n";
		}
		echo $this->gender."<br/><br/>\n\n";
	}

	/**
	 * Displays the picture of the student along with some other information to identify him.
	 * This is used to verify the update.
	 */
	public function displayPicture() {
		echo $this->firstName.' '.$this->lastName."<br/>\n";
		if($this->groupe == '') {
			echo $this->year.$this->department."<br/>\n";
		} else {
			echo $this->year.$this->department.'-'.$this->groupe."<br/>\n";
		}
		echo '<img src="../photos/'.$this->studentID.'.jpg"/>'."<br/><br/>\n\n";
	}

	/**
	 * Displays the room of the student along with some other information to identify him.
	 * This is used to verify the update.
	 */
	public function displayRoom() {
		echo $this->firstName.' '.$this->lastName."<br/>\n";
		echo $this->mail."<br/>\n";
		if($this->groupe == '') {
			echo $this->year.$this->department."<br/>\n";
		} else {
			echo $this->year.$this->department.'-'.$this->groupe."<br/>\n";
		}
		echo $this->room."<br/><br/>\n\n";
	}
}

?>