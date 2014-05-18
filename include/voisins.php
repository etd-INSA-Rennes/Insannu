<?php
	
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