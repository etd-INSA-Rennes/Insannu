<?php
/****************************

****************************/
function update_student($db) {
	try {
		$user = $db->prepare('SELECT * FROM students WHERE ip_address = ?');
		$user->execute(array($_SERVER['REMOTE_ADDR']));
	} catch(Exception $e) {
		exit('Error: '.$e->getMessage());
	}
	
	if($user->fetch()) {
		try {
			$query = $db->prepare('UPDATE students SET last_connection = ?, navigator = ?, nb_connections = nb_connections + 1 WHERE ip_address = ?');
			$query->execute(array(time(), $_SERVER["HTTP_USER_AGENT"], $_SERVER['REMOTE_ADDR']));
		} catch(Exception $e) {
			exit('Error: '.$e->getMessage());
		}
	} else {
		try {
			$query = $db->prepare('INSERT INTO students(ip_address, last_connection, navigator, nb_connections) VALUES(?, ?, ?, 1)');
			$query->execute(array($_SERVER['REMOTE_ADDR'], time(), $_SERVER['HTTP_USER_AGENT']));
		} catch(Exception $e) {
			exit('Error: '.$e->getMessage());
		}
	}
	
	$user->closeCursor();
	$query->closeCursor();
	
}

/********************************************


Une session dure maximum une demi-heure.

********************************************/
function add_connection($db) {
	if(isset($_SERVER['HTTP_REFERER'])) {
		$origin_page = $_SERVER['HTTP_REFERER'];
	} else {
		$origin_page = '';
	}
	
	try {
		$query = $db->prepare('INSERT INTO connections(date, origin_page, landing_page) VALUES(?, ?, ?)');
		$query->execute(array(time(), $origin_page, substr($_SERVER['REQUEST_URI'], 1)));
	} catch(Exception $e) {
		exit('Error: '.$e->getMessage());
	}
	$query->closeCursor();
}

/********************************




********************************/
function add_search($db, $search, $maillist) {
	try {
		$query = $db->prepare('INSERT INTO searches(date, search, maillist) VALUES(?, ?, ?)');
		$query->execute(array(time(), $search, $maillist));
	} catch(Exception $e) {
		exit('Error : '.$e->getMessage());
	}
	if(!$query) echo 'ok';
	$query->closeCursor();
}

/*********************************



*********************************/
function add_page_visited($db, $page) {
	try {
		$query = $db->prepare('INSERT INTO pages_visited(page, student_id) VALUES(?, (SELECT id FROM students WHERE ip_address = ? LIMIT 1))');
		$query->execute(array($page, $_SERVER['REMOTE_ADDR']));
	} catch(Exception $e) {
		exit('Error: '.$e->getMessage());
	}
	$_SESSION['pages_visited'][] = $page;
}

?>