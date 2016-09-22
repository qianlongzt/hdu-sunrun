<?php
	ini_set('display_errors','on');

	require_once 'curl.php';
	$stuno = '15153302';
	$password = '15153302';
	$curl = new Curl();
	#$curl -> post('http://hdu.sunnysport.org.cn/login/', array('username' => $stuno,'password' => $password	));
	#echo $cookie = $curl -> cookie;
	$cookie = 'sessionid=xik9duzqejesgkouqlbk8py73az3n512';
	echo $curl -> get('http://hdu.sunnysport.org.cn/runner/index.html', $cookie);
	echo "\n\n".($curl->respHeader);
