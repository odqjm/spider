<?php

function getHtml() {
	$tryTimes = 0;
	while ($tryTimes < 20) {
		$html = file_get_contents('https://cn.bing.com/');
		if ($html) {
			return $html;
		}
		$tryTimes++;
	}
	return false;
}

function getUrlFromHtml(&$html) {
	$html = mb_substr($html, -500);
	$preg = '/<img.*?src=[\"|\']?(.*?)[\"|\']?\s.*?>/i';
	if (!preg_match($preg, $html, $src)) {
		return false;
	}
	if (!$src) {
		return false;
	}
	if (!isset($src[1])) {
		return false;
	}
	return $src[1];
}

function sendSms() {

}

function readFromFile() {
	$filename = 'picture_list.json';
	if (!file_exists("./{$filename}")) {
		writeIntoFile([]);
	}
	$content = file_get_contents("./{$filename}");
	return json_decode($content, true);
}

function writeIntoFile($data) {
	$filename = 'picture_list.json';
	$data = json_encode($data);
	$content = file_put_contents("./{$filename}", $data);
}

$html = getHtml();
if (!$html) {
	exit(1);
}

$url = getUrlFromHtml($html);
if (!$url) {
	sendSms();
	exit(2);
}

$url = 'https://cn.bing.com/' . $url;

echo $url . PHP_EOL;

$picture_list = readFromFile();
$picture_list[] = $url;
writeIntoFile($picture_list);