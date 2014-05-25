<?php

require_once('StudentsManager.class.php');
require_once('LDAP.class.php');
require_once('OldStudentsAccessor.class.php');
require_once('OfficialDirectory.class.php');
require_once('GenderRules.class.php');

/**
 * Main class for the installation process.
 * Makes the operations and displays the messages.
 * The current state of the installation is stored in an INI file.
 * This class follows a Singleton pattern.
 * @author Paul Chaignon <paul.chaignon@gmail.com>
 */
class Installation {
	const INSTALLATION_FILE = '../../data/installation.ini';
	const MESSAGE_FIRST_HALF = 'Première partie finie, relancez pour la seconde partie.';
	private static $instance;
	private $oldDatabaseFile;
	private $step;
	private $studentsUpdated;
	private $subStep;

	/**
	 * Constructor
	 * The constructor is private because the class follows a Singleton pattern.
	 */
	private function Installation() {
		$this->studentsUpdated = array();
		if(file_exists(self::INSTALLATION_FILE)) {
			$installationInfo = parse_ini_file(self::INSTALLATION_FILE);
			$this->step = $installationInfo['step'];
			$this->oldDatabaseFile = $installationInfo['old_database_file'];
			$this->subStep = $installationInfo['sub_step'];
		} else {
			$this->step = InstallationStep::START;
			$this->oldDatabaseFile = '';
			$this->updateInstallationFile();
			$this->subStep = 0;
		}
	}

	/**
	 * @return The instance of Installation.
	 */
	public static function getInstance() {
		if(self::$instance == null) {
			self::$instance = new Installation();
		}
		return self::$instance;
	}

	/**
	 * @return The current step of the installation process.
	 */
	public function getCurrentStep() {
		return $this->step;
	}

	/**
	 * Goes to the next step of the installation process.
	 * Updates the INI file.
	 */
	public function goToNextStep() {
		$this->step++;
		$this->updateInstallationFile();
	}

	/**
	 * Goes to the other half of the current step of the installation process.
	 * Updates the INI file.
	 */
	public function goToNextHalf() {
		$this->subStep = ($this->subStep == 0)? 1 : 0;
		$this->updateInstallationFile();
	}

	/**
	 * Sets the previous database file.
	 * @param oldDatabaseFile The previous database file.
	 */
	public function setOldDatabaseFile($oldDatabaseFile) {
		$this->oldDatabaseFile = $oldDatabaseFile;
		$this->updateInstallationFile();
	}

	/**
	 * Displays the form to go to the next step.
	 * It comes with a message to details the next step process.
	 */
	public function display() {
		echo '<form action="index.php" method="post">';

		switch($this->step) {
			case InstallationStep::START:
				echo 'Veuillez saisir le nom du fichier copie de la base de données actuelle.<br/>'."\n";
				echo 'Cela doit correspondre au fichier de type insannu_XXXXXX avec la plus grande date dans le dossier data.<br/>'."\n";
				echo '<input type="text" name="old_database" value="insannu_XXXXXX.db"/><br/>'."\n";
				break;
			case InstallationStep::POPULATE:
				echo 'Les étudiants vont maintenant être récupérés depuis le serveur LDAP.<br/>'."\n";
				break;
			case InstallationStep::OFFICIAL_RESULTS:
				echo 'Veuillez charger le fichier contenant les résultats de l\'annuaire officiel sur le serveur.<br/>'."\n";
				echo 'Ce fichier doit contenir le code source de la page résultat avec tous les étudiants.<br/>'."\n";
				echo 'Il doit être nommé '.OfficialDirectory::HTML_RESULTS_FILE.'.<br/>'."\n";
				break;
			case InstallationStep::ROOMS:
				echo 'Les chambres vont maintenant être récupérées depuis les résultats de l\'annuaire officiel.<br/>'."\n";
				break;
			case InstallationStep::RETRIEVE_OLD_PICTURES:
				echo 'Les photos de l\'ancienne base de données vont maintenant être récupérées.<br/>'."\n";
				break;
			case InstallationStep::RETRIEVE_OFFICIAL_PICTURES:
				echo 'Des photos vont maintenant être récupérées depuis les résultats de l\'annuaire officiel.<br/>'."\n";
				break;
			case InstallationStep::RETRIEVE_OLD_GENDERS:
				echo 'Les sexes des étudiants vont maintenant être récupérées depuis l\'ancienne base de données.<br/>'."\n";
				break;
			case InstallationStep::RULES_FROM_NAME:
				echo 'Vous allez maintenant devoir déterminer des règles pour les nouveaux prénoms (si possible).<br/>'."\n";
				break;
			case InstallationStep::APPLY_GENDER_RULES:
				echo 'Toutes les règles (anciennes et nouvelles) sur les prénoms pour déterminer le sexe vont maintenant être appliquées.<br/>'."\n";
				break;
			case InstallationStep::GENDER_FROM_PICTURES:
				echo 'Vous allez maintenant devoir déterminer le sexe des étudiants restants à partir de leur photos (bonne chance :P).<br/>'."\n";
				break;
			default:
				return false;
		}

		echo "\t".'<input type="submit" value="Process" name="process"/>'."\n";
		echo "</form>\n";
	}

	/**
	 * Displays a message with the result of the last step.
	 * Also displays a form to go to the next step explanations.
	 */
	public function displayResult() {
		$nbUpdated = count($this->studentsUpdated);
		switch($this->step) {
			case InstallationStep::START:
				echo 'Base de données vidée !';
				break;
			case InstallationStep::POPULATE:
				echo $nbUpdated.' étudiants ont été récupérés depuis le serveur LDAP.<br/><br/>'."\n\n";
				foreach($this->studentsUpdated as $student) {
					$student->display();
				}
				break;
			case InstallationStep::OFFICIAL_RESULTS:
				echo 'Le fichier contenant les résultats de l\'annuaire officiel a bien été pris en compte.<br/>'."\n";
				break;
			case InstallationStep::ROOMS:
				echo 'Les chambres de '.$nbUpdated.' étudiants ont pu être récupérées depuis l\'annuaire officiel.<br/><br/>'."\n\n";
				foreach($this->studentsUpdated as $student) {
					$student->displayRoom();
				}
				break;
			case InstallationStep::RETRIEVE_OLD_PICTURES:
				echo 'Les photos de '.$nbUpdated.' étudiants ont pu être récupérées depuis l\'ancienne base de données.<br/><br/>'."\n\n";
				foreach($this->studentsUpdated as $student) {
					$student->displayPicture();
				}
				break;
			case InstallationStep::RETRIEVE_OFFICIAL_PICTURES:
				echo 'Les photos de '.$nbUpdated.' étudiants ont pu être récupérées depuis l\'annuaire officiel.<br/><br/>'."\n\n";
				foreach($this->studentsUpdated as $student) {
					$student->displayPicture();
				}
				break;
			case InstallationStep::RETRIEVE_OLD_GENDERS:
				echo 'Le sexe de '.$nbUpdated.' étudiants a pu être récupéré depuis l\'ancienne base de données.<br/><br/>'."\n\n";
				foreach($this->studentsUpdated as $student) {
					$student->displayGender();
				}
				break;
			case InstallationStep::APPLY_GENDER_RULES:
				echo 'Les règles pour la détermination du sexe à partir du prénom ont été appliqué avec succès sur '.$nbUpdated.' étudiants.<br/><br/>'."\n\n";
				foreach($this->studentsUpdated as $student) {
					$student->displayGender();
				}
				break;
		}

		echo '<form action="index.php">';
		echo "\t".'<input type="submit" value="Next" name="next"/>'."\n";
		echo "</form>\n";
	}

	/**
	 * Makes the operations associated to the current step.
	 * @return True if the operations were successfull.
	 */
	public function process() {
		switch($this->step) {
			case InstallationStep::START:
				if(!isset($_POST['old_database'])) {
					return false;
				}
				system('sudo touch ../../data/in_maintenance');
				system('sudo mv ../photos ../photos_old');
				system('sudo mkdir ../photos');
				system('sudo chmod a+wr ../photos');
				OldStudentsAccessor::retrieveDefaultPictures();
				$this->setOldDatabaseFile($_POST['old_database']);
				StudentsManager::clear();
				break;
			case InstallationStep::POPULATE:
				$students = LDAP::getInstance()->getStudents();
				StudentsManager::saveStudents($students);
				$this->studentsUpdated = $students;
				break;
			case InstallationStep::OFFICIAL_RESULTS:
				if(!file_exists(OfficialDirectory::HTML_RESULTS_FILE)) {
					return false;
				}
				break;
			case InstallationStep::ROOMS:
				$students = StudentsManager::getStudents($this->subStep);
				$this->studentsUpdated = OfficialDirectory::retrieveRooms($students);
				StudentsManager::updateRooms($this->studentsUpdated);
				if($this->subStep == 0) {
					$this->endFirstHalf();
				} else {
					$this->goToNextHalf();
				}
				break;
			case InstallationStep::RETRIEVE_OLD_PICTURES:
				$accessor = new OldStudentsAccessor($this->oldDatabaseFile);
				$students = StudentsManager::getStudents($this->subStep);
				$this->studentsUpdated = $accessor->retrievePictures($students);
				StudentsManager::updatePictures($this->studentsUpdated);
				if($this->subStep == 0) {
					$this->endFirstHalf();
				} else {
					$this->goToNextHalf();
				}
				break;
			case InstallationStep::RETRIEVE_OFFICIAL_PICTURES:
				$students = StudentsManager::getStudents($this->subStep);
				$this->studentsUpdated = OfficialDirectory::retrievePictures($students);
				StudentsManager::updatePictures($this->studentsUpdated);
				if($this->subStep == 0) {
					$this->endFirstHalf();
				} else {
					$this->goToNextHalf();
				}
				break;
			case InstallationStep::RETRIEVE_OLD_GENDERS:
				$accessor = new OldStudentsAccessor($this->oldDatabaseFile);
				$students = StudentsManager::getStudents($this->subStep);
				$this->studentsUpdated = $accessor->retrieveGenders($students);
				StudentsManager::updateGenders($this->studentsUpdated);
				if($this->subStep == 0) {
					$this->endFirstHalf();
				} else {
					$this->goToNextHalf();
				}
				break;
			case InstallationStep::RULES_FROM_NAME:
				exit('This part of the installation process has not been written yet.');
				$this->displayNames();
				break;
			case InstallationStep::APPLY_GENDER_RULES:
				$students = StudentsManager::getStudents($this->subStep);
				$this->studentsUpdated = GenderRules::getInstance()->applyRules($students);
				StudentsManager::updateGenders($this->studentsUpdated);
				if($this->subStep == 0) {
					$this->endFirstHalf();
				} else {
					$this->goToNextHalf();
				}
				break;
			case InstallationStep::GENDER_FROM_PICTURES:
				$this->displayPictures();
				break;
			default:
				return false;
		}
		return true;
	}

	/**
	 * Ends the first half of the current step and exits.
	 */
	private function endFirstHalf() {
		$this->displayResult();
		$this->goToNextHalf();
		exit(self::MESSAGE_FIRST_HALF);
	}

	/**
	 * Updates the installation INI file.
	 */
	private function updateInstallationFile() {
		$array = array();
		$array['step'] = $this->step;
		$array['sub_step'] = $this->subStep;
		$array['old_database_file'] = $this->oldDatabaseFile;
		$this->writeInstallationFile($array);
	}

	/**
	 * Writes an array of values to the installation INI file.
	 * @param array The array of values to write.
	 * @param hasSections True if the INi file has sections. Default to false.
	 * @return True if the file was successfully written.
	 */
	private function writeInstallationFile($array, $hasSections = false) {
		$content = '';
		if($hasSections) {
		// Has sections.
			foreach($array as $key => $elem) {
			// For each section.
				$content .= '['.$key."]\n";
				foreach($elem as $key2 => $elem2) {
				// For each item.
					if(is_array($elem2)) {
						for($i=0; $i<count($elem2); $i++) {
							$content .= $key2.'[] = "'.$elem2[$i]."\"\n";
						}
					} else if($elem2 == '') {
						$content .= $key2." = \n";
					} else {
						$content .= $key2.' = "'.$elem2."\"\n";
					}
				}
			}
		} else {
			foreach($array as $key => $elem) {
			// For each item.
				if(is_array($elem)) {
					for($i=0; $i<count($elem); $i++) {
						$content .= $key.'[] = "'.$elem[$i]."\"\n";
					}
				} else if($elem == '') {
					$content .= $key." = \n";
				} else {
					$content .= $key.' = "'.$elem."\"\n";
				}
			}
		}
		if(!$handle = fopen(self::INSTALLATION_FILE, 'w')) {
			return false;
		}
		if(!fwrite($handle, $content)) {
			return false;
		}
		fclose($handle);
		return true;
	}
}

/**
 * Enumeration of the installation steps.
 */
class InstallationStep {
	const START = 0;
	const POPULATE = 1;
	const OFFICIAL_RESULTS = 2;
	const ROOMS = 3;
	const RETRIEVE_OLD_PICTURES = 4;
	const RETRIEVE_OFFICIAL_PICTURES = 5;
	const RETRIEVE_OLD_GENDERS = 6;
	const RULES_FROM_NAME = 7;
	const APPLY_GENDER_RULES = 8;
	const GENDER_FROM_PICTURES = 9;
}

?>