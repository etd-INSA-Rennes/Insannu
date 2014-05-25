<?php

require_once('Student.class.php');
require_once('../../include/tools.php');

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
	private function GenderRules() {
		if(!file_exists(self::RULES_FILE)) {
			system('touch '.self::RULES_FILE);
		}
		$this->rules = parse_ini_file(self::RULES_FILE);
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
	 * Adds a new rule for a name
	 * @param firstName The first name.
	 * @param gender The gender to apply for this name.
	 * @throws Exception if the gender value is incorrect or if this name already has a rule.
	 */
	public function addRule($firstName, $gender) {
		if(!in_array($gender, array('Male', 'Female', ''))) {
			throw new Exception('Illegal gender value: '.$gender);
		}
		if(array_key_exists(strtolower($firstName), $this->rules)) {
			throw new Exception('This name already has a rule: '.$firstName);
		}
		$this->rules[strtolower($firstName)] = $gender;
	}

	/**
	 * Saves the rules to the INI file.
	 */
	public function saveRules() {
		writeInstallationFile(self::RULES_FILE, $this->rules);
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
				$firstName = strtolower($student->getFirstName());
				if(array_key_exists($firstName, $this->rules) && $this->rules[$firstName]!='') {
					$student->setGender($this->rules[$firstName]);
					$studentsUpdated[] = $student;
				}
			}
		}
		return $studentsUpdated;
	}

	/**
	 * Displays all the first names which don't already have rules
	 * and let the user select a rule for each one of them.
	 * @param names The first names.
	 */
	public function displayNamesWithoutRule($names) {
		$namesWithoutRule = $this->getNamesWithoutRule($names);
		$nbNames = count($namesWithoutRule);

		echo '<form id="gender_rules" method="post">'."\n";
		echo '<input type="hidden" name="add_rules" value="1"/>'."\n";
		echo 'Leave it to unknown if you don\'t know.<br/><br/>'."\n\n";
		for($i=0; $i<$nbNames; $i++) {
			echo '<label>'.$namesWithoutRule[$i].':</label>';
			echo '<span><input type="hidden" name="name_'.$i.'" value="'.$namesWithoutRule[$i].'"/>'."\n";
			echo 'Male <input type="radio" name="gender_'.$i.'" value="Male"/>'."\n";
			echo 'Female <input type="radio" name="gender_'.$i.'" value="Female"/>'."\n";
			echo 'Mixed <input type="radio" name="gender_'.$i.'" value=""/>'."\n";
			echo 'Unknown <input type="radio" name="gender_'.$i.'" value="Unknown" checked="checked"/></span><br/>'."\n";
		}
		echo '<input type="hidden" name="nb_names" value="'.$nbNames.'"/><br/>'."\n";
		echo '<input type="submit" name="process" value="Process"/>'."\n";
		echo '</form>'."\n";
	}

	/**
	 * Filters the first names without any rule.
	 * @param names The first names to process.
	 * @return The first names without rule for it.
	 */
	private function getNamesWithoutRule($names) {
		$namesWithoutRule = array();
		foreach($names as $name) {
			if(!array_key_exists(strtolower($name), $this->rules)) {
				$namesWithoutRule[] = $name;
			}
		}
		return $namesWithoutRule;
	}

	/**
	 * Processes the new gender rules entered by the admin.
	 * @return The number of new rules.
	 */
	public function processNewGenderRules() {
		$nbNewRules = 0;

		for($i=0; $i<$_POST['nb_names']; $i++) {
			$gender = $_POST['gender_'.$i];
			if($gender != 'Unknown') {
			// The user knew the rule.
				$firstName = $_POST['name_'.$i];
				$this->addRule($firstName, $gender);
				$nbNewRules++;
			}
		}

		$this->saveRules();
		return $nbNewRules;
	}
}

?>