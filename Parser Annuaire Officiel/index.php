<?php

	$html = file_get_contents('resultats_annuaire.html');
	
	$page = new DomDocument;
	@$page->loadHTML($html);
	
	$results = $page->getElementById('tabs-Annuaire')->getElementsByTagName('table')->item(0)->getElementsByTagName('fieldset');
	
	foreach($results as $result) {
		echo "<u>Nom :</u> ".utf8_decode(get_inner_html($result->getElementsByTagName('b')->item(0))).'<br/>';
		$div = explode('<br />', preg_replace('#<a(.)+</a>#', '', utf8_decode(get_inner_html($result->getElementsByTagName('div')->item(0)))));
		echo "<u>Mail :</u> ".$div[0].'<br/>';
		if($div[1]=="Master" || $div[1]=="Doctorant") {
			$department = $div[1];
			$year = 0;
		} else {
			$year = substr(trim($div[2]), 0, 1);
			if(preg_match("/STPI/", $div[2]) || preg_match("/Sciences et Techniques pour/", $div[2])) {
				$department = 'STPI';
			} elseif(preg_match("/GMA/", $div[2]) || preg_match("/canique et Automatique/", $div[2])) {
				$department = 'GMA';
			} elseif(preg_match("/GCU/", $div[2]) || preg_match("/Civil et Urbain/", $div[2])) {
				$department = 'GCU';
			} elseif(preg_match("/EII/", $div[2]) || preg_match("/Electronique et Informatique Industrielle/", $div[2])) {
				$department = 'EII';
			} elseif(preg_match("/INFO/", $div[2]) || preg_match("/Informatique/", $div[2])) {
				$department = 'INFO';
			} elseif(preg_match("/SRC/", $div[2]) || preg_match("/seaux de Communication/", $div[2])) {
				$department = 'SRC';
			} elseif(preg_match("/MNT/", $div[2]) || preg_match("/riaux et Nanotechnologies/", $div[2])) {
				$department = 'MNT';
			}
		}
		echo "<u>Année :</u> ".$year.'<br/>';
		echo "<u>Département :</u> ".$department.'<br/><br/>';
	}
	
	
function get_inner_html($node) {
	/********************************************************
	Cette fonction retourne le code HTML d'un element.
	********************************************************/
    $innerHTML= '';
    $children = $node->childNodes;
    foreach($children as $child) {
        $innerHTML .= $child->ownerDocument->saveXML($child);
    }
	
    return $innerHTML;
}
	
?>