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
	
function voisins($db, $ip_address) {
	try {
		$query = $db->prepare("SELECT * FROM students WHERE ip_address LIKE ? LIMIT 1");
		$query->execute(array($ip_address));
	} catch(Exception $e) {
		exit('Error : '.$e->getMessage());
	}

	header("Content-Type: text/xml; charset=utf-8");
	echo '<?xml version="1.0" encoding="utf-8"?>';
	echo '<students>';
	
	if($visiteur = $query->fetch()) {
		if(substr($visiteur['room'], 0, 2)=='BN') {
			$couloir = substr($visiteur['room'], 0, 3);
			$chambre = substr($visiteur['room'], 3);
		} else {
			$couloir = substr($visiteur['room'], 0, 2); 
			$chambre = substr($visiteur['room'], 2);
		}
		if($chambre%2==1) {
			$chambres = array(check_number($chambre+102), check_number($chambre+100), check_number($chambre+98), check_number($chambre+2), 0, check_number($chambre-2), check_number($chambre-98), check_number($chambre-100), check_number($chambre-102));
		} else {
			$chambres = array(check_number($chambre+98), check_number($chambre+100), check_number($chambre+102), check_number($chambre-2), 0, check_number($chambre+2), check_number($chambre-102), check_number($chambre-100), check_number($chambre-98));
		}
		for($i=0 ; $i<9 ; $i++) {
			if($i==4) {
				echo '<student groupe="'.$visiteur['groupe'].'" mail="'.$visiteur['mail'].'" student_id="'.$visiteur['student_id'].'" picture="'.$visiteur['picture'].'" last_name="'.ucwords(strtolower($visiteur['last_name'])).'" first_name="'.$visiteur['first_name'].'" gender="'.$visiteur['gender'].'" room="'.$visiteur['room'].'" ip_address="'.$visiteur['ip_address'].'" year="'.$visiteur['year'].'" department="'.$visiteur['department'].'"/>';
			} else {
				try {
					$query = $db->prepare("SELECT * FROM students WHERE room LIKE ? LIMIT 1");
					$query->execute(array($couloir.$chambres[$i]));
				} catch(Exception $e) {
					exit('Error : '.$e->getMessage());
				}
				if($voisin = $query->fetch()) {
					echo '<student groupe="'.$voisin['groupe'].'" mail="'.$voisin['mail'].'" student_id="'.$voisin['student_id'].'" picture="'.$voisin['picture'].'" last_name="'.ucwords(strtolower($voisin['last_name'])).'" first_name="'.$voisin['first_name'].'" gender="'.$voisin['gender'].'" room="'.$voisin['room'].'" ip_address="'.$voisin['ip_address'].'" year="'.$voisin['year'].'" department="'.$voisin['department'].'"/>';
				} else {
					echo '<student first_name=""/>';
				}
			}
		}
	}
	
	echo '</students>';
	
	$query->closeCursor();
}

function check_number($number) {
	if($number<10) {
		$result = '00'.$number;
	} elseif($number<100) {
		$result = '0'.$number;
	} else {
		$result = $number;
	}
	return $result;
}
	
?>