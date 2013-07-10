<?php

function generate_id_confirm() {
	do {
		$id_confirm = rand(10000000, 99999999);
		try {
			$query = $GLOBALS['bdd_mysql']->prepare('SELECT * FROM students WHERE id_confirm = ?');
			$query->execute(array($id_confirm));
		} catch(Exception $e) {
			exit('Error: '.$e->getMessage());
		}
	} while($result = $query->fetch());
	return $id_confirm;
}

function generate_id() {
	do {
		$id = rand(10000000, 99999999);
		try {
			$query = $GLOBALS['bdd_mysql']->prepare('SELECT * FROM students WHERE id = ?');
			$query->execute(array($id));
		} catch(Exception $e) {
			exit('Error: '.$e->getMessage());
		}
	} while($result = $query->fetch());
	return $id;
}

function generate_student_id() {
	do {
		$id = rand(1000, 4999);
		try {
			$query = $GLOBALS['bdd']->prepare('SELECT * FROM students WHERE student_id = ?');
			$query->execute(array($id));
		} catch(Exception $e) {
			exit('Error: '.$e->getMessage());
		}
	} while($result = $query->fetch());
	return $id;
}

function isFromResidence($ip_address) {
	$ip = explode('.', $ip_address);
	if($ip[0]!=10 || $ip[1]!=133) {
		return false;
	}
	if($ip[2]<8 || $ip[2]>14) {
		return false;
	}
	if($ip[3]==0 || $ip[3]==254) {
		return false;
	}
	return true;
}

?>