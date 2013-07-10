<?php

function search($db, $input) {

	// Initialises all variables:
	$search = str_replace(array('*', '?'), array('%', '_'), $input);
	$mots = explode(' ', $search);
	$sql_query = '';
	$room = false;
	$params = array();
	$order_by = false;
	
	foreach($mots as $mot) {
		
		// Generates the ORDER BY :
		if(!$room) {
			$part_word = substr($mot, 0, 2);
			if(strlen($mot)>2 && is_numeric($mot{2}) && ($part_word=='BN' || $part_word=='BS' || $part_word=='CN' || $part_word=='CS' || $part_word=='AN' || $part_word=='AS')) {
				$room = true;
			} elseif($mot=='ARZ' || $mot=='BREHAT' || $mot=='CEZEMBRE' || $mot=='GLENAN') {
				$room = true;
			}
		}
			
		// Generates the SQL request for gender:
		if($mot=='GARS' || $mot=='FILLE' || $mot=='FILLES') {
			switch($mot) {
				case 'FILLE':
					$sql_query .= ' AND gender LIKE \'Female\'';
				break;
				case 'GARS':
					$sql_query .= ' AND gender LIKE \'Male\'';
				break;
				case 'FILLES':
					$sql_query .= ' AND gender LIKE \'Female\'';
				break;
			} 
		
		// Generates the SQL request for the residences:
		} else if($mot=='ARZ') {
			$sql_query .= ' AND room LIKE ?';
			$params[] = 'a%';
		} elseif($mot=='BREHAT') {
			$sql_query .= ' AND room LIKE ?';
			$params[] = 'b%';
		} elseif($mot=='CEZEMBRE') {
			$sql_query .= ' AND room LIKE ?';
			$params[] = 'c%';
		} elseif($mot=='GLENAN') {
			$sql_query .= ' AND room LIKE ?';
			$params[] = 'd%';
		
		// Generates the SQL request for Anis:
		} else if($mot=='AZIZ') {
			$sql_query .= ' AND (first_name LIKE \'Anis\' AND last_name LIKE \'DOGHRI\')';
		
		// Generates the SQL request for the years:
		} else if(strlen($mot)==3 && substr($mot, 1)=='AN' && is_numeric($mot{0})) {
			$sql_query .= ' AND year = ?';
			$params[] = $mot{0};
		
		// Generates the SQL request for the groups:
		} else if(strlen($mot)==2 && ($mot{0}==1 || $mot{0}==2) && !is_numeric($mot{1})) {
			$sql_query .= ' AND (year = ? AND groupe LIKE ?)';
			array_push($params, $mot{0}, $mot{1});
		
		} else {
			
			// Generates the SQL request for the name, the login and the ip address:
			$sql_query .= ' AND (last_name LIKE ?';
			$sql_query .= ' OR first_name LIKE ?';
			$sql_query .= ' OR login LIKE ?';
			array_push($params, '%'.$mot.'%', '%'.$mot.'%', '%'.$mot.'%'/*, '%'.$mot.'%'*/);
			
			
			// Generates the SQL request for rooms:
			if(substr($mot, 0, 2)=='BN' && substr($mot, 0, 3)!='BNC' && substr($mot, 0, 3)!='BNN' && substr($mot, 0, 3)!='BNS') {
				$bnc = substr($mot, 0, 2).'c'.substr($mot, 2);
				$bns = substr($mot, 0, 2).'s'.substr($mot, 2);
				$bnn = substr($mot, 0, 2).'n'.substr($mot, 2);
				$sql_query .= ' OR (room LIKE ? OR room LIKE ? OR room LIKE ?)';
				array_push($params, '%'.$bnc.'%', '%'.$bnn.'%', '%'.$bns.'%');
			} else {
				$sql_query .= ' OR room LIKE ?';
				$params[] = $mot.'%';
			}
			
			// Generates the SQL request for the groups:
			$part_word = strtoupper(substr($mot, 0, 6));
			if($part_word=='1STPI-') {
				$sql_query .= ' OR department LIKE 'STPI' AND year = 1 AND groupe LIKE ?';
				$params[] = $mot{6};
			} elseif($part_word=='2STPI-') {
				$sql_query .= ' OR department LIKE 'STPI' AND year = 2 AND groupe LIKE ?';
				$params[] = $mot{6};
			} elseif(strtoupper(substr($mot, 0, 5))=='STPI-') {
				$sql_query .= ' OR department LIKE 'STPI' AND groupe LIKE ?';
				$params[] = $mot{5};
			}
			
			// Generates the SQL request for the department:
			if($mot!='' && is_numeric($mot{0}) && !is_numeric($mot)) {
				$part_word = strtoupper(substr($mot, 1));
				if($part_word=='STPI' || $part_word=='INFO' || $part_word=='EII' || $part_word=='SRC' || $part_word=='GCU' || $part_word=='GMA' || $part_word=='SGM') {
					$sql_query .= ' OR (year = ? AND department LIKE ?)';
					array_push($params, $mot{0}, $part_word);
				} elseif($part_word=='GC') {
				// Special case for GC abbreviation.
					$sql_query .= ' OR (year = ? AND department LIKE \'GCU\')';
					$params[] = $mot{0};
				} elseif($part_word=='MNT') {
				// Special case for old name of SGM.
					$sql_query .= ' OR (year = ? AND department LIKE \'SGM\')';
					$params[] = $mot{0};
				} else {
					$sql_query .= ' OR year = ? OR department LIKE ?';
					array_push($params, $mot, '%'.$mot.'%');
				}
			} else {
				$sql_query .= ' OR year = ? OR department LIKE ?';
				array_push($params, $mot, '%'.$mot.'%');
			}
			
			$sql_query .= ')';
		}
	}
	$sql_query = ltrim($sql_query, ' AND'); // Deletes the first AND.
	
	// Executes the request on the database:
	try {
		$order_by = $room? 'room, ' : '';
		$fields = 'student_id, last_name, first_name, department, year, room, picture, gender, mail, groupe';
		$query = $db->prepare('SELECT '.$fields.' FROM students WHERE '.$sql_query.' ORDER BY '.$order_by.'last_name, first_name;');
		$query->execute($params);
	} catch(Exception $e) {
		exit('Error : '.$e->getMessage());
	}
	
	$students = $query->fetchAll(PDO::FETCH_NUM);
	$query->closeCursor();
	
	// Return the results:
	return json_encode($students);
	
}
	
?>