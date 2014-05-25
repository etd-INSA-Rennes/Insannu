<?php

require_once('Student.class.php');

/**
 * Applies a set of rules defined by the admin to the students.
 * The rules are stored in an INI file.
 * The class follows a Singleton pattern.
 * @author Paul Chaignon <paul.chaignon@gmail.com>
 */
class GenderRules {
	const RULES_FILE = '../../data/gender_rules.ini';
	private static $instance;
	private $rules;

	/**
	 * Constructor
	 * The constructor is private because the class follows a Singleton pattern.
	 */
	private function Installation() {
		$rules = parse_ini_file(self::RULES_FILE);
	}

	/**
	 * @return The instance of GenderRules.
	 */
	public static function getInstance() {
		if(self::$instance == null) {
			self::$instance = new GenderRules();
		}
		return self::$instance;
	}

	/**
	 * Applies the rules to students.
	 * @param students The students to apply the rules to as a reference.
	 * @return The students updated in the process.
	 */
	public function applyRules(&$students) {
		$studentsUpdated = array();
		foreach($students as $student) {
			if(!$student->hasGender()) {
				$student->setGender($this->rules[$student->getFirstName()]);
				$studentsUpdated[] = $student;
			}
		}
		return $studentsUpdated;
	}
}

?>