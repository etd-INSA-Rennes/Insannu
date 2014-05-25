<?php

require_once('Student.class.php');

/**
 * Static class to manage the students for the new database.
 * Saves, updates and retrieves students.
 * @author Paul Chaignon <paul.chaignon@gmail.com>
 */
class StudentsManager {
	const DATABASE_FILE = '../../data/insannu.db';

	/**
	 * Clears the database.
	 */
	public static function clear() {
		$db = self::connect();
		$query = $db->prepare('DELETE FROM students;');
		$query->execute();
	}

	/**
	 * Retrieves students from the database.
	 * @param half 0 to only retrieve the first half (1000 students).
	 * @return The students from the database.
	 */
	public static function getStudents($half) {
		$db = self::connect();
		$students = array();
		if($half == 0) {
			$sql = 'SELECT * FROM students ORDER BY student_id LIMIT 1000;';
		} else {
			$sql = 'SELECT * FROM students ORDER BY student_id LIMIT 1000 OFFSET 1000;';
		}
		$query = $db->prepare($sql);
		$query->execute();
		while($result = $query->fetch()) {
			$student = new Student($result['student_id'], $result['first_name'], $result['last_name'], $result['groupe'], $result['mail'], $result['department'], $result['year'], $result['login'], $result['picture'], $result['room'], $result['gender'], $result['photo_changed']);
			$students[strtolower($result['mail'])] = $student;
		}
		return $students;
	}

	/**
	 * Retrieves all the first names from the database.
	 * @return A set of all the first names from the database.
	 */
	public static function getFirstNames() {
		$db = self::connect();
		$names = array();
		$query = $db->prepare('SELECT first_name FROM students ORDER BY first_name;');
		$query->execute();
		while($result = $query->fetch()) {
			if(!in_array($result['first_name'], $names)) {
				$names[] = $result['first_name'];
			}
		}
		return $names;
	}

	/**
	 * Saves students to the database with the initial information (those from the LDAP server).
	 * @param students The students to save in the database.
	 */
	public static function saveStudents($students) {
		$db = self::connect();
		$query = $db->prepare('INSERT INTO students(student_id, first_name, last_name, groupe, mail, department, year, login, gender, room, picture, photo_changed) VALUES(?, ?, ?, ?, ?, ?, ?, ?, \'\', \'\', 0, 0);');
		foreach($students as $student) {
			$query->execute($student->getSQLInsertParameters());
		}
	}

	/**
	 * Updates the room of the students in the database.
	 * @param students The students to update.
	 */
	public static function updateRooms($students) {
		$db = self::connect();
		$query = $db->prepare('UPDATE students SET room = ? WHERE mail LIKE ?;');
		foreach($students as $student) {
			if($student->hasRoom()) {
				$query->execute(array($student->getRoom(), $student->getEmailAddress()));
			}
		}
	}

	/**
	 * Updates the picture information (picture and photoChanged) of the students in the database.
	 * @param students The students to update.
	 */
	public static function updatePictures($students) {
		$db = self::connect();
		$query = $db->prepare('UPDATE students SET picture = 1, photo_changed = ? WHERE mail LIKE ?;');
		foreach($students as $student) {
			if($student->hasPicture()) {
				$query->execute(array($student->getPhotoChanged(), $student->getEmailAddress()));
			}
		}
	}

	/**
	 * Updates the gender of the students in the database.
	 * @param students The students to update.
	 */
	public static function updateGenders($students) {
		$db = self::connect();
		$query = $db->prepare('UPDATE students SET gender = ? WHERE mail LIKE ?;');
		foreach($students as $student) {
			if($student->hasGender()) {
				$query->execute(array($student->getGender(), $student->getEmailAddress()));
			}
		}
	}

	/**
	 * Connects to the database.
	 * @return The PDO connection.
	 */
	protected static function connect() {
		$db = new PDO('sqlite:'.static::DATABASE_FILE);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $db;
	}
}

?>