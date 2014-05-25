<?php

function inMaintenance() {
	return file_exists('../data/in_maintenance');
}

/**
 * Writes an array of values to an INI file.
 * @param file The INI file.
 * @param array The array of values to write.
 * @param hasSections True if the INi file has sections. Default to false.
 * @return True if the file was successfully written.
 */
function writeInstallationFile($file, $array, $hasSections = false) {
	$content = '';
	if($hasSections) {
	// Has sections.
		foreach($array as $key => $elem) {
		// For each section.
			$content .= '['.$key."]\n";
			foreach($elem as $key2 => $elem2) {
			// For each item.
				if(is_array($elem2)) {
					for($i=0; $i<count($elem2); $i++) {
						$content .= $key2.'[] = "'.$elem2[$i]."\"\n";
					}
				} else if($elem2 == '') {
					$content .= $key2." = \n";
				} else {
					$content .= $key2.' = "'.$elem2."\"\n";
				}
			}
		}
	} else {
		foreach($array as $key => $elem) {
		// For each item.
			if(is_array($elem)) {
				for($i=0; $i<count($elem); $i++) {
					$content .= $key.'[] = "'.$elem[$i]."\"\n";
				}
			} else if($elem == '') {
				$content .= $key." = \n";
			} else {
				$content .= $key.' = "'.$elem."\"\n";
			}
		}
	}
	if(!$handle = fopen($file, 'w')) {
		return false;
	}
	if(!fwrite($handle, $content)) {
		return false;
	}
	fclose($handle);
	return true;
}

?>