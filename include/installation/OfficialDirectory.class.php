<?php

require_once('Student.class.php');

/**
 * Static class to retrieve information from the officiel directory.
 * The information is actually retrieved from the HTML result page
 * of a search for everything (*) on the official directory.
 * @author Paul Chaignon <paul.chaignon@gmail.com>
 */
class OfficialDirectory {
	const HTML_RESULTS_FILE = '../../data/official_results.html';

	/**
	 * Updates students with their room from the official results.
	 * @param students Reference to the students to update.
	 * @return The students actually updated.
	 */
	public static function retrieveRooms(&$students) {
		$rooms = OfficialDirectory::parseRooms();
		$studentsUpdated = array();
		foreach($rooms as $mail => $room) {
			// Skips room if the user is in the second half:
			if(!isset($students[$mail])) {
				continue;
			}

			if(!$students[$mail]->hasRoom()) {
				$students[$mail]->setRoom($room);
				$studentsUpdated[] = $students[$mail];
			}
		}
		return $studentsUpdated;
	}

	/**
	 * Updates students with their picture from the official results.
	 * @param students A reference to the students to update.
	 * @return The students actually updated.
	 */
	public static function retrievePictures(&$students) {
		$studentsUpdated = array();
		$pictures = OfficialDirectory::parsePictures();
		foreach($pictures as $mail => $pictureID) {
			// Skips picture if the user is in the second half:
			if(!isset($students[$mail])) {
				continue;
			}
			
			if(!$students[$mail]->hasPicture()) {
				$students[$mail]->setPicture(false);
				OfficialDirectory::downloadPicture($pictureID, $students[$mail]->getStudentID());
				$studentsUpdated[] = $students[$mail];
			}
		}
		return $studentsUpdated;
	}

	/**
	 * Parses the official results and retrieves the rooms.
	 * @return The rooms indexed by mail addresses in lower case.
	 */
	private static function parseRooms() {
		$rooms = array();
		$doc = new DOMDocument();
		@$doc->loadHTMLFile(self::HTML_RESULTS_FILE);
		$fieldsets = $doc->getElementsByTagName('fieldset');
		for($i=0; $i<$fieldsets->length; $i++) {
			$links = $fieldsets->item($i)->getElementsByTagName('a');
			$divs = $fieldsets->item($i)->getElementsByTagName('div');
			if($links->length == 1) {
				$link = $links->item(0);
				$div = $divs->item($divs->length-1);
				$mail = substr($link->attributes->getNamedItem('href')->nodeValue, 7);
				if(preg_match('/Résidence (Bréhat|Cézembre|Les Glénan|Arz) - ([A-Z]{2,3}\d{3})/', $div->nodeValue, $matches)) {
					$room = $matches[2];
					$rooms[strtolower($mail)] = $room;
				}
			}
		}
		return $rooms;
	}

	/**
	 * Parses the official results and retrieves the pictures' IDs.
	 * @return The pictures' IDs indexed by mail addresses in lower case.
	 */
	private static function parsePictures() {
		$pictures = array();
		$doc = new DOMDocument();
		@$doc->loadHTMLFile(self::HTML_RESULTS_FILE);
		$fieldsets = $doc->getElementsByTagName('fieldset');
		for($i=0; $i<$fieldsets->length; $i++) {
			$image = $fieldsets->item($i)->getElementsByTagName('img')->item(0);
			$src = $image->attributes->getNamedItem('src')->nodeValue;
			if(stripos($src, 'mail.png') === false) {
				$links = $fieldsets->item($i)->getElementsByTagName('a');
				if($links->length == 1) {
					$link = $links->item(0);
					$mail = substr($link->attributes->getNamedItem('href')->nodeValue, 7);
					$pictureID = substr($src, 27, -4);
					$pictures[strtolower($mail)] = $pictureID;
				}
			}
		}
		return $pictures;
	}

	/**
	 * Downloads the picture from the official directory using wget.
	 * If the file already exists on the server, skips the download.
	 * @param pictureID The ID of the picture on the official directory.
	 * @param studentID The student's ID to use for the name of the picture on the server.
	 */
	private static function downloadPicture($pictureID, $studentID) {
		if(!file_exists('../photos/'.$studentID.'.jpg')) {
			system('sudo wget -O ../photos/'.$studentID.'.jpg http://ent.insa-rennes.fr/AnnuaireENT/images/photos/'.$pictureID.'.jpg');
		}
	}
}

?>