<?php
/**
 * Copyright (c) 2014 Paul Chaignon <paul.chaignon@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, distribute with modifications, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE ABOVE COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR
 * THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * Except as contained in this notice, the name(s) of the above copyright
 * holders shall not be used in advertising or otherwise to promote the
 * sale, use or other dealings in this Software without prior written
 * authorization.
 */

function search($db, $input) {

	// Initialises all variables:
	$search = str_replace(array('*', '?'), array('%', '_'), $input);
	$mots = explode(' ', $search);
	$sql_query = '';
	$room = false;
	$params = array();
	$order_by = false;
	
	foreach($mots as $mot) {

		$like = 'LIKE';
		$or = 'OR';
		$and = 'AND';
		$eq = '=';
		// Invert the meaning if ! or - is detected:
		if($mot{0}=='!' || $mot{0}=='-') {
			$like = 'NOT LIKE';
			$or = 'AND';
			$and = 'OR';
			$eq = '<>';
			$mot = substr($mot, 1);
		}
		
		// Generates the ORDER BY:
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
					$sql_query .= " AND gender $like 'Female'";
				break;
				case 'GARS':
					$sql_query .= " AND gender $like 'Male'";
				break;
				case 'FILLES':
					$sql_query .= " AND gender $like 'Female'";
				break;
			} 
		
		// Generates the SQL request for the residences:
		} else if($mot=='ARZ') {
			$sql_query .= " AND room $like ?";
			$params[] = 'a%';
		} elseif($mot=='BREHAT') {
			$sql_query .= " AND room $like ?";
			$params[] = 'b%';
		} elseif($mot=='CEZEMBRE') {
			$sql_query .= " AND room $like ?";
			$params[] = 'c%';
		} elseif($mot=='GLENAN') {
			$sql_query .= " AND room $like ?";
			$params[] = 'd%';
		
		// Generates the SQL request for Anis:
		} else if($mot=='AZIZ') {
			$sql_query .= " AND (first_name $like 'Anis' $and last_name $like 'DOGHRI')";
		
		// Generates the SQL request for the years:
		} else if(strlen($mot)==3 && substr($mot, 1)=='AN' && is_numeric($mot{0})) {
			$sql_query .= " AND year $eq ?";
			$params[] = $mot{0};
		
		// Generates the SQL request for the groups:
		} else if(strlen($mot)==2 && ($mot{0}==1 || $mot{0}==2) && !is_numeric($mot{1})) {
			$sql_query .= " AND (year $eq ? $and groupe $like ?)";
			array_push($params, $mot{0}, $mot{1});
		
		} else {
			
			// Generates the SQL request for the name, the login and the ip address:
			$sql_query .= " AND (last_name $like ?";
			$sql_query .= " $or first_name $like ?";
			$sql_query .= " $or login $like ?";
			array_push($params, '%'.$mot.'%', '%'.$mot.'%', '%'.$mot.'%'/*, '%'.$mot.'%'*/);
			
			
			// Generates the SQL request for rooms:
			if(substr($mot, 0, 2)=='BN' && substr($mot, 0, 3)!='BNC' && substr($mot, 0, 3)!='BNN' && substr($mot, 0, 3)!='BNS') {
				$bnc = substr($mot, 0, 2).'c'.substr($mot, 2);
				$bns = substr($mot, 0, 2).'s'.substr($mot, 2);
				$bnn = substr($mot, 0, 2).'n'.substr($mot, 2);
				$sql_query .= " $or (room $like ? $or room $like ? $or room $like ?)";
				array_push($params, '%'.$bnc.'%', '%'.$bnn.'%', '%'.$bns.'%');
			} else {
				$sql_query .= " $or room $like ?";
				$params[] = '%'.$mot.'%';
			}
			
			// Generates the SQL request for the groups:
			$part_word = strtoupper(substr($mot, 0, 6));
			if($part_word=='1STPI-') {
				$sql_query .= " $or department $like 'STPI' $and year $eq 1 $and groupe $like ?";
				$params[] = $mot{6};
			} elseif($part_word=='2STPI-') {
				$sql_query .= " $or department $like 'STPI' $and year $eq 2 $and groupe $like ?";
				$params[] = $mot{6};
			} elseif(strtoupper(substr($mot, 0, 5))=='STPI-') {
				$sql_query .= " $or department $like 'STPI' $and groupe $like ?";
				$params[] = $mot{5};
			}
			
			// Generates the SQL request for the department:
			if($mot!='' && is_numeric($mot{0}) && !is_numeric($mot)) {
				$part_word = strtoupper(substr($mot, 1));
				if($part_word=='STPI' || $part_word=='INFO' || $part_word=='EII' || $part_word=='SRC' || $part_word=='GCU' || $part_word=='GMA' || $part_word=='SGM') {
					$sql_query .= " $or (year $eq ? $and department $like ?)";
					array_push($params, $mot{0}, $part_word);
				} elseif($part_word=='GC') {
				// Special case for GC abbreviation.
					$sql_query .= " $or (year $eq ? $and department $like 'GCU')";
					$params[] = $mot{0};
				} elseif($part_word=='MNT') {
				// Special case for old name of SGM.
					$sql_query .= " $or (year $eq ? $and department $like 'SGM')";
					$params[] = $mot{0};
				} else {
					$sql_query .= " $or year $eq ? $or department $like ?";
					array_push($params, $mot, '%'.$mot.'%');
				}
			} else if(is_numeric($mot)) {
				$sql_query .= " $or year $eq ?";
				array_push($params, $mot);
			} else {
				$sql_query .= " $or department $like ?";
				array_push($params, '%'.$mot.'%');
			}
			
			$sql_query .= ')';
		}
	}
	$sql_query = ltrim($sql_query, ' AND'); // Deletes the first AND.
	
	// Executes the request on the database:
	try {
		$order_by = $room? 'room, ' : '';
		$fields = 'student_id, last_name, first_name, department, year, room, picture, gender, mail, tags, description';
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
