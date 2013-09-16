<?php

// exit();

class InsaLDAP {
	private $host, $port, $link;
	protected $dn, $filter;

	public function InsaLDAP() {
		$this->host = 'ldap.insa-rennes.fr';
		$this->port = 389;
		$this->link = null;
		$this->dn = 'ou=people,dc=insa-rennes,dc=fr';
		$this->connect();
	}

	public function __destruct() {
		if($this->link) {
			ldap_close($this->link);
		}
	}

	/**
	* Connexion au serveur LDAP.
	* @throws RuntimeException si la connexion echoue.
	*/
	protected function connect() {
		if($this->link) {
			return;
		}

		$this->link = ldap_connect($this->host, $this->port);
		if(!$this->link) {
			throw new RuntimeException('Could not connect to LDAP server.');
		}

		ldap_bind($this->link);
	}

	/**
	* Recupere le groupe d'une personne via son email.
	* @param string $email Adresse mail
	* @throws RuntimeException si pas connecté au serveur LDAP.
	* @return array Tableau de la forme ([classe] => 4MNT, [groupe] => null) ou groupe
	*   est de la forme 2stpi-j pour les 1A et 2A. NULL si pas de résultat.
	*/
	public function getByEmail($email) {
		if(!$this->link) {
			throw new RuntimeException('LDAP not available.');
		}

		$results = @ldap_search($this->link, $this->dn, sprintf('mail=%s', $email)/*, array('insaclasseetu', 'insagroupeetu', 'uid'), 0, 1*/);
		
		$results = ldap_get_entries($this->link, $results);
		if($results['count']<1) {
			return null;
		}
		$result = $results[0];

		return array(
				'classe' => (isset($result['insaclasseetu'])? $result['insaclasseetu'][0] : null),
				'last_name' => ((isset($result['sn']))? $result['sn'][0] : null),
				'first_name' => ((isset($result['givenname']))? $result['givenname'][0] : null),
				'mail' => ((isset($result['mail']))? $result['mail'][0] : null),
				'login' => (isset($result['uid'])? $result['uid'][0] : null),
				'groupe' => ((!isset($result['insagroupeetu']) || $result['insagroupeetu'][0] == '--')? null : $result['insagroupeetu'][0]),
			);
	}
	
	public function getAll() {
		if(!$this->link) {
			throw new RuntimeException('LDAP not available.');
		}

		$results = @ldap_search($this->link, $this->dn, 'uid=*'/*, array('insaclasseetu', 'insagroupeetu', 'uid'), 0, 1*/);
		
		$results = ldap_get_entries($this->link, $results);
		if($results['count']<1) {
			return null;
		}
		
		$array = array();
		foreach($results as $result) {
			$array[] = array(
					'uidnumber' => (isset($result['uidnumber'])? $result['uidnumber'][0] : null),
					'gidnumber' => (isset($result['gidnumber'])? $result['gidnumber'][0] : null),
					'insapopulation' => (isset($result['insapopulation'])? $result['insapopulation'][0] : null),
					'classe' => (isset($result['insaclasseetu'])? $result['insaclasseetu'][0] : null),
					'last_name' => ((isset($result['sn']))? $result['sn'][0] : null),
					'first_name' => ((isset($result['givenname']))? $result['givenname'][0] : null),
					'mail' => ((isset($result['mail']))? $result['mail'][0] : null),
					'phone_number' => ((isset($result['telephonenumber']))? $result['telephonenumber'][0] : null),
					'login' => (isset($result['uid'])? $result['uid'][0] : null),
					'groupe' => ((!isset($result['insagroupeetu']) || $result['insagroupeetu'][0] == '--')? null : $result['insagroupeetu'][0]),
				);
		}
		unset($array[0]);
		
		return $array;
	}
	
	public function getAllStudents() {
		if(!$this->link) {
			throw new RuntimeException('LDAP not available.');
		}

		$results = @ldap_search($this->link, $this->dn, 'insapopulation~=etudiant'/*, array('insaclasseetu', 'insagroupeetu', 'uid'), 0, 1*/);
		
		$results = ldap_get_entries($this->link, $results);
		if($results['count']<1) {
			return null;
		}
		
		$array = array();
		foreach($results as $result) {
			$array[] = array(
					'uidnumber' => (isset($result['uidnumber'])? $result['uidnumber'][0] : null),
					'gidnumber' => (isset($result['gidnumber'])? $result['gidnumber'][0] : null),
					'insapopulation' => (isset($result['insapopulation'])? $result['insapopulation'][0] : null),
					'classe' => (isset($result['insaclasseetu'])? $result['insaclasseetu'][0] : null),
					'last_name' => ((isset($result['sn']))? $result['sn'][0] : null),
					'first_name' => ((isset($result['givenname']))? $result['givenname'][0] : null),
					'mail' => ((isset($result['mail']))? $result['mail'][0] : null),
					'phone_number' => ((isset($result['telephonenumber']))? $result['telephonenumber'][0] : null),
					'login' => (isset($result['uid'])? $result['uid'][0] : null),
					'groupe' => ((!isset($result['insagroupeetu']) || $result['insagroupeetu'][0] == '--')? null : $result['insagroupeetu'][0]),
				);
		}
		unset($array[0]);
		
		return $array;
	}
}

/*
$ldap = new InsaLDAP();
var_dump($ldap->getByEmail('Sebastien.Fournier@insa-rennes.fr'));
*/

require('secret/sqlite.php');
$bdd = connect_db('secret/insannu.db');

$query = $bdd->exec("DELETE FROM students");

$ldap = new InsaLDAP();

$students = $ldap->getAllStudents();
echo count($students).'<br/>';

$query = $bdd->prepare("INSERT INTO students('student_id', 'login', 'first_name', 'last_name', 'mail', 'year', 'department', 'groupe') VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
foreach($students as $student) {
	$year = '';
	switch($student['classe']) {
		case 'DOCT':
			$department = 'Doctorant';
		break;
		case 'MAST':
			$department = 'Master';
		break;
		default:
			$year = substr($student['classe'], 0, 1);
			$department = substr($student['classe'], 1);
			if($department=='A') {
				$department = 'STPI';
			}
	}
	if($student['groupe']=='') {
		$groupe = '';
	} else {
		$groupe = strtoupper(substr($student['groupe'], 6));
	}
	echo $student['login'].' '.$student['first_name'].' '.$student['last_name'].' '.$student['mail'].' '.$year.' '.$department.' '.$groupe.'<br/>';
	$success = $query->execute(array($student['uidnumber'], $student['login'], $student['first_name'], $student['last_name'], $student['mail'], $year, $department, $groupe));
	if(!$success) {
		echo 'ARG!<br/>';
	}
}


/*$ldap = new InsaLDAP();
var_dump($ldap->getByEmail('Julia.Saussereau@insa-rennes.fr'));*/

/*require('secret/sqlite.php');
connect_db('secret/insannu.db');

try {
	$query0 = $GLOBALS['bdd']->prepare("SELECT mail FROM students");
	$query0->execute();
} catch (Exception $e) {
	exit('Error : '.$e->getMessage());
}

$ldap = new InsaLDAP();
$query1 = $GLOBALS['bdd']->prepare("UPDATE students SET login = ? WHERE mail LIKE ?");
while($data = $query0->fetch()) {
	$ldap_infos = $ldap->getByEmail($data['mail']);
	// $groupe = strtoupper(substr($ldap_infos['groupe'], -1));
	// echo $groupe.'<br/>';
	$params = array($ldap_infos['login'], $data['mail']);
	try {
		$query1->execute($params);
	} catch (Exception $e) {
		exit('Error : '.$e->getMessage());
	}
}*/

	
?>