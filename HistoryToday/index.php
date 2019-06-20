<?php

define('URL', 'http://www.ipip5.com/today/api.php?type=json');

define('FILE_PATH',  __DIR__ . date('/Y-m-d') . '.json');

copy(URL, FILE_PATH);