<?php

function customSearches($search) {
	$custom_searches = array('3INFO-LSR22', '3INFO-A', '3INFO-B', '3INFO-C', '3INFO-LSR', '3INFO-LSR1', '3INFO-LSR2', '3INFO-LSR3', '3INFO-LSR11', '3INFO-LSR12', '3INFO-LSR22', '3INFO-LSR22',
							'3INFO-TDMM', '3INFO1', '3INFO2', '3INFO11', '3INFO12', '3INFO21', '3INFO22');
	foreach($custom_searches as $custom_search) {
		if($search==$custom_search) {
			return file_get_contents('../custom/'.$search.'.json');
		}
	}
	return '';
}

?>
