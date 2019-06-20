<?php

define('URL', 'https://cn.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1&mkt=zh-CN');
define('FILE_PATH', __DIR__ . '/list.json');

copy(URL, FILE_PATH);