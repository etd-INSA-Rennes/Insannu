<?php

function inMaintenance() {
	return file_exists('../data/in_maintenance');
}

?>