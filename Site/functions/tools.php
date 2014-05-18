<?php

function inMaintenance() {
	return file_exists('in_maintenance');
}

?>