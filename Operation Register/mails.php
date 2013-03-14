<?php

$GLOBALS['url'] = 'insannu.fr.cr';
$GLOBALS['url_interne'] = '10.0.0.3';

function mailConfirmPhoto($address, $name, $file, $id, $id_confirm) {
	$url = "http://zeta.dnsdynamic.com/mails/index.php?action=1&address=".urlencode($address)."&name=".urlencode($name)."&file=".urlencode($file)."&id=".urlencode($id)."&id_confirm=".urlencode($id_confirm);
	$credentials = "pi:p0rsanprat";
	$ch = curl_init($url);
	/*if($method=="POST") {
		curl_setopt($ch, CURLOPT_POST, 1);
	} else {
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
	}*/
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	// curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
	curl_setopt($ch, CURLOPT_USERPWD, $credentials);
	$result = curl_exec($ch);
	curl_close($ch);
	if($result==1) {
		return true;
	}
	return false;
}

function mailValidPhoto($address, $name, $file, $id, $student_id) {
    $url = "http://zeta.dnsdynamic.com/mails/index.php?action=2&address=".urlencode($address)."&name=".urlencode($name)."&file=".urlencode($file)."&id=".urlencode($id)."&student_id=".urlencode($student_id);
	$credentials = "pi:p0rsanprat";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
	curl_setopt($ch, CURLOPT_USERPWD, $credentials);
	$result = curl_exec($ch);
	curl_close($ch);
	if($result==1) {
		return true;
	}
	return false;
}

function mailRegister($id_mail, $address, $name, $id) {
	$url = "http://zeta.dnsdynamic.com/mails/index.php?action=3&id_mail=".urlencode($id_mail)."&address=".urlencode($address)."&name=".urlencode($name)."&id=".urlencode($id);
	$credentials = "pi:p0rsanprat";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
	curl_setopt($ch, CURLOPT_USERPWD, $credentials);
	$result = curl_exec($ch);
	curl_close($ch);
	if($result==1) {
		return true;
	}
	return false;
}

function mailPhotoAccepted($address, $name, $message = '') {
	$url = "http://zeta.dnsdynamic.com/mails/index.php?action=4&address=".urlencode($address)."&name=".urlencode($name)."&message=".urlencode($message);
	$credentials = "pi:p0rsanprat";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
	curl_setopt($ch, CURLOPT_USERPWD, $credentials);
	$result = curl_exec($ch);
	curl_close($ch);
	if($result==1) {
		return true;
	}
	return false;
}

function mailPhotoRefused($address, $name, $message) {
	$url = "http://zeta.dnsdynamic.com/mails/index.php?action=5&address=".urlencode($address)."&name=".urlencode($name)."&message=".urlencode($message);
	$credentials = "pi:p0rsanprat";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
	curl_setopt($ch, CURLOPT_USERPWD, $credentials);
	$result = curl_exec($ch);
	curl_close($ch);
	if($result==1) {
		return true;
	}
	return false;
}

?>