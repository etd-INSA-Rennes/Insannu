<?php

require_once('Student.class.php');

/**
 * Accessor class to the old/previous database.
 * Used to retrieve the pictures and gender of some students.
 * @author Paul Chaignon <paul.chaignon@gmail.com>
 */
class OldStudentsAccessor {
	private $databaseFile;

	/**
	 * Constructor
	 * @param databaseFile The previous database file. 
	 */
	public function OldStudentsAccessor($databaseFile) {
		$this->databaseFile = $databaseFile;
	}

	/**
	 * Retrieves the default pictures from the old photos folder using the cp command.
	 */
	public static function retrieveDefaultPictures() {
		system('cp ../photos_old/default_*male.jpg ../photos/');
	}

	/**
	 * Updates students with the pictures from the old/previous database (and photo folder).
	 * @param students Reference to the students to update.
	 * @param The students actually updated.
	 */
	public function retrievePictures(&$students) {
		$studentsUpdated = array();
		$db = $this->connect();
		$query = $db->prepare('SELECT picture, photo_changed FROM students WHERE mail LIKE ?');
		foreach($students as $mail => $student) {
			if(!$student->hasPicture()) {
				$query->execute(array($mail));
				if($result = $query->fetch()) {
					if($result['picture'] == 1) {
						$student->setPicture($result['photo_changed']);
						self::copyPicture($student->getStudentID());
						$studentsUpdated[] = $student;
					}
				}
			}
		}
		return $studentsUpdated;
	}

	/**
	 * Updates students with their genders from the old/previous database.
	 * @param students Reference to the students to update.
	 * @param The students actually updated.
	 */
	public function retrieveGenders(&$students) {
		$studentsUpdated = array();
		$db = $this->connect();
		$query = $db->prepare('SELECT gender FROM students WHERE mail LIKE ?');
		foreach($students as $mail => $student) {
			if(!$student->hasGender()) {
				$query->execute(array($mail));
				if($result = $query->fetch()) {
					$student->setGender($result['gender']);
					$studentsUpdated[] = $student;
				}
			}
		}
		return $studentsUpdated;
	}

	/**
	 * Copy a picture to the new photo folder using the cp command.
	 */
	private static function copyPicture($pictureID) {
		system('cp ../photos_old/'.$pictureID.'.jpg ../photos/');
	}

	/**
	 * Connects to the old/previous database.
	 * @return The PDO connection.
	 */
	protected function connect() {
		$db = new PDO('sqlite:../../data/'.$this->databaseFile);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $db;
	}
}

?>