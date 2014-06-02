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

require_once('../../include/special_search.php');
require_once('../../include/search.php');
require_once('../../include/sqlite.php');
$db = connect_db('../../data/insannu.db');

if(isset($_GET['search'])) {
	// Replaces all special characters:
	$search = str_replace(array('é', 'è', 'à', 'ë', 'ê', 'ï', 'î'), array('e', 'e', 'a', 'e', 'e', 'i', 'i'), $_GET['search']);
	
	// Calls the right search function:
	$search = strtoupper($search);
	/*if($search=='VOISINS') { // Need to be put in JSON !!!
		require("../functions/voisins.php");
		echo voisins($_SERVER['REMOTE_ADDR']);
	} else*/if($search == 'INSANNU') {
		echo specialSearchInsannu($db);
	} elseif($search=='RANDOM' || $search=='ALEATOIRE') {
		echo specialSearchRandom($db);
	} else {
		echo search($db, $search);
	}
}



?>
