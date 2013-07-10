<?php
/****************************************



****************************************/

require('constants.php');

if($GLOBALS['statistics']===true) {

	session_start();
	require_once('secret/mysql.php');
	require('functions/statistics.php');
	$mysql_db = connect_mysql_db();

	if(!isset($_SESSION['connect']) || !isset($_COOKIE['connect']) || $_SESSION['connect']!=1 || $_COOKIE['connect']!=1) {
		// Connects the user to the site:
		$_SESSION['connect'] = 1;
		try {
			$pages_visited = $mysql_db->prepare('SELECT page FROM pages_visited WHERE student_id = (SELECT id FROM students WHERE ip_address = ? LIMIT 1)');
			$pages_visited->execute(array($_SERVER['REMOTE_ADDR']));
		} catch(Exception $e) {
			exit('Error: '.$e->getMessage());
		}

		$_SESSION['pages_visited'] = array();
		while($page_visited = $pages_visited->fetch()) {
			$_SESSION['pages_visited'][] = $page_visited['page'];
		}
		
		add_connection($mysql_db);
		update_student($mysql_db);
	}

	setcookie('connect', '1', time()+1800, null, null, false, true);

	$page = substr($_SERVER['PHP_SELF'], 1);
	if(!in_array($page, $_SESSION['pages_visited'])) {
		add_page_visited($mysql_db, $page);
	}
	
} else {
	
	$_SESSION['connect'] = 1;
	
}

?>