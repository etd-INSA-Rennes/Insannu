<?php

exit();

if($handle = opendir('custom/')) {
	$GLOBALS['statistics'] = false;
	require('secret/sqlite.php');
	connect_db('secret/insannu.db');
	require('functions/search.php');
	set_time_limit(120);
	
	$entry = '3INFO-LSR22.lst';
	
    /*while(($entry = readdir($handle))!==false) {
        if($entry != '.' && $entry != '..') {*/
            echo $entry.'<br/>';
			$file = file('custom/'.$entry);
			$result = '[';
			foreach($file as $line) {
				$line = rtrim($line);
				echo $line.'<br/>';
				$result .= substr(search($line), 1, -1).',';
			}
			$result = rtrim($result, ',');
			$result .= ']';
			echo $result.'<br/>';
			echo '<br/><br/>';
			
			$ext = strrchr($entry, '.');
			$name = basename($entry, $ext);
			$fp = fopen('custom/'.$name.'.json', 'w');
			fwrite($fp, $result);
			fclose($fp);
       /* }
    }
    closedir($handle);*/
}

?>