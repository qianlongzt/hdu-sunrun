<?php
	ini_set('display_errors','on');

	require_once 'curl.php';
	$stuno = '15153302';
	$password = '15153302';
	$curl = new Curl();
	$curl -> post('http://hdu.sunnysport.org.cn/login/', array(
								'username' => $stuno,
								'password' => $password
								));
	/*$respbody = $curl -> get('http://hdu.sunnysport.org.cn/runner/index.html');
	preg_match_all("#{$stuno}#", $respbody, $match);
	var_dump($match);
	if(!isset($match[0][0])) {
		echo "无法获取该学生 $stuno 信息\n";
	}

	preg_match_all("/<label>(.*?)<\/label>/", $respbody, $name);
	preg_match_all("/<td>(.*)<\/td>/", $respbody, $arr);
	
	var_dump($name);
	var_dump($arr);
	*/

	$detail = $curl->get('http://hdu.sunnysport.org.cn/runner/achievements.html');
	preg_match('#<tbody>(.*)</tbody>#s', $detail, $tbody);
	preg_match_all("#<tr[^>]*>(.*)</tr>#sU",$tbody[1], $trs);
	$infos = array();
	foreach($trs[1] as $i =>$tr) {
		preg_match_all("#<td>(.*)</td>#sU", $tr, $once);
		$once = $once[1];
		$infos[$i]['count'] = (int)$once[0];
		$infos[$i]['date'] = $once[1];
		$infos[$i]['time'] = $once[2];
		$infos[$i]['length'] = $once[3];
		$infos[$i]['speed'] = $once[4];
		$status = (preg_match('#ok#', $once[5]) == 1);
		$infos[$i]['status'] = $status;
		$infos[$i]['remark'] = $once[6];
	}
echo json_encode($infos, JSON_UNESCAPED_UNICODE);
