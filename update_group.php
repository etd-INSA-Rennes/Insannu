<?php

function update_group() {
	/***************************************************
	Met a jour les groupes pour les STPIs avec ldap.
	***************************************************/
	$ldap = new Insa_Ldap();
	
	// Selection de tous les STPIs :
	try {
		$bdd = $GLOBALS['bdd'];
		$query = $bdd->prepare("SELECT * FROM students WHERE department = 'STPI'");    
		$query->execute();
	} catch (Exception $e) {
		exit('Error : '.$e->getMessage());
	}

	// MaJ du groupe de chaque etudiant :
	while($data = $query->fetch()) {
		$groupe = $ldap->getGroup($data['mail']);
		try {
			$bdd = $GLOBALS['bdd'];
			$query = $bdd->prepare("UPDATE students SET group = ? WHERE mail = ?");    
			$query->execute(array($groupe['groupe'], $data['mail']));
		} catch (Exception $e) {
			exit('Error : '.$e->getMessage());
		}
	}
}

/********************************************************
	Leger wrapper autour du module ldap de php.
	
	@author Nicolas Hurman <nicolas.hurman@gmail.com>
	
********************************************************/
class Insa_LDAP {
	/**
	 * Connexion au serveur LDAP et requêtes.
	 */
	private $host, $port, $link;
	protected $dn, $filter;

	public function __construct() {
		$this->host   = 'ldap.insa-rennes.fr';
		$this->port   = 389;
		$this->link   = null;
		$this->dn     = 'ou=people,dc=insa-rennes,dc=fr';
		$this->filter = 'mail=%s';
	
		$this->connect();
	}

	public function __destruct() {
		if ($this->link)
		ldap_close($this->link);
	}

	/**
	* Connexion au serveur LDAP.
	*
	* @throws RuntimeException si la connexion échoue.
	*/
	protected function connect() {
		if($this->link) 
			return;

		$this->link = ldap_connect($this->host, $this->port);
		if(!$this->link)
			throw new RuntimeException('Could not connect to LDAP server.');

		ldap_bind($this->link);
	}

	/**
	 * Récupère le groupe d'une personne via son email.
	 *
	 * @param string $email Adresse mail
	 * @throws RuntimeException si pas connecté au serveur LDAP.
	 * @return array Tableau de la forme ([classe] => 4MNT, [groupe] => null) où groupe
	 *   est de la forme 2stpi-j pour les 1A et 2A. NULL si pas de résultat.
	 */
	public function getGroup($email) {
		if (!$this->link)
		throw new RuntimeException('LDAP not available.');

		$result = @ldap_search(
			$this->link, $this->dn,
			sprintf($this->filter, $email),
			array('insaclasseetu', 'insagroupeetu'),
			0, 1);

		$result = ldap_get_entries($this->link, $result);
		if ($result['count'] < 1)
		return null;
		$result = $result[0];

		return array(
			'classe' => (isset($result['insaclasseetu']) ? $result['insaclasseetu'][0] : null),
			'groupe' => ((!isset($result['insagroupeetu']) || $result['insagroupeetu'][0] == '--')
			? null : $result['insagroupeetu'][0]),
		);
	}
}

?>