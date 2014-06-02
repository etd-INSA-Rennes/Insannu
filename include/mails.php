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

$GLOBALS['url'] = 'insannu.fr.cr';
$GLOBALS['url_interne'] = '10.0.0.3';

function mailConfirmPhoto($address, $name, $file, $id, $id_confirm) {
	$url = 'http://zebil.dnsdynamic.com/mails/index.php?action=1&address='.urlencode($address).'&name='.urlencode($name).'&file='.urlencode($file).'&id='.urlencode($id).'&id_confirm='.urlencode($id_confirm);
	$credentials = 'pi:p0rsanprat';
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

function mailValidPhoto($address, $name, $file, $id, $student_id) {
    $url = 'http://zebil.dnsdynamic.com/mails/index.php?action=2&address='.urlencode($address).'&name='.urlencode($name).'&file='.urlencode($file).'&id='.urlencode($id).'&student_id='.urlencode($student_id);
	$credentials = 'pi:p0rsanprat';
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
	$url = 'http://zebil.dnsdynamic.com/mails/index.php?action=3&id_mail='.urlencode($id_mail).'&address='.urlencode($address).'&name='.urlencode($name).'&id='.urlencode($id);
	$credentials = 'pi:p0rsanprat';
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
	$url = 'http://zebil.dnsdynamic.com/mails/index.php?action=4&address='.urlencode($address).'&name='.urlencode($name).'&message='.urlencode($message);
	$credentials = 'pi:p0rsanprat';
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
	$url = 'http://zebil.dnsdynamic.com/mails/index.php?action=5&address='.urlencode($address).'&name='.urlencode($name).'&message='.urlencode($message);
	$credentials = 'pi:p0rsanprat';
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