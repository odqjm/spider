<?php

/**
 * 获取url内容
 * @Author: eps
 * @DateTime 11/27/2018 9:05 AM
 * @return bool|string
 */
function getHtml()
{
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


/**
 * 从html中获取壁纸的url
 * @Author: eps
 * @DateTime 11/27/2018 9:08 AM
 * @param $html
 * @return bool
 */
function getUrlFromHtml(&$html)
{
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

/**
 * 读取文件
 * @Author: eps
 * @DateTime 11/27/2018 9:08 AM
 * @return mixed
 */
function readFromFile()
{
    $filename = 'picture_list.json';
    if (!file_exists("./{$filename}")) {
        writeIntoFile([]);
    }
    $content = file_get_contents("./{$filename}");
    return json_decode($content, true);
}

/**
 * 写入文件
 * @Author: eps
 * @DateTime 11/27/2018 9:08 AM
 * @param $data
 */
function writeIntoFile($data)
{
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
    // TODO 发送短信
    exit(2);
}

$url = 'https://cn.bing.com/' . $url;

echo $url . PHP_EOL;

$picture_list = readFromFile();
$picture_list[] = $url;
writeIntoFile($picture_list);