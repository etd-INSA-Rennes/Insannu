<?php

require_once('../../include/installation/GenderRules.class.php');
set_time_limit(0);

$letters = array(/*'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', */'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
$endPages = 'Nous n\'avons pas de prénoms correspondant à votre recherche';

foreach($letters as $letter) {
	$page = 1;
	while(true) {
		$url = 'http://www.aufeminin.com/world/maternite/prenoms/prenomresult__letter='.$letter.'&courant=0&page='.$page.'.html';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);

		// Checks if there is still results for this letter:
		if(strpos($result, $endPages) !== false) {
			break;
		}

		preg_match_all('/<font size=2 face="Arial,sans-serif">([A-Z-\' ]+)<\/font><\/a>\s*<font size=1>\(\s*(\w+|\w+\s-\s\w+.)\.\s*\)<\/font>/i', $result, $matches, PREG_SET_ORDER);
		
		// Prints the number of match.
		// Should be 60 except for the last page of each letter.
		$nbMatches = count($matches);
		if($nbMatches != 60) {
			echo '<u>'.$letter.'</u>: '.$page.' - '.$nbMatches."<br/>\n";
		}

		for($i=0; $i<$nbMatches; $i++) {
			$firstName = strtolower($matches[$i][1]);
			switch($matches[$i][2]) {
				case 'Fem':
					$gender = 'Female';
					break;
				case 'Masc':
					$gender = 'Male';
					break;
				case 'Mixte - Masc':
				case 'Mixte - Fem':
					$gender = '';
					break;
				default:
					throw new Exception('Gender unknown: '.$matches[$i][2]);
			}

			GenderRules::getInstance()->addRule($firstName, $gender);

			if($gender == '') {
			// If gender is mixed, then the next one is the same name.
				$i++;
			}
		}

		curl_close($ch);
		$page++;
	}
}

GenderRules::getInstance()->saveRules();

?>