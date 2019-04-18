<?php


define('URL', 'http://image.bee-ji.com/%');
define('FILE_PATH', __DIR__ . '/%.jpg');

$go = 5;

while ($go--) {
    copy(
        str_replace('%', $go, URL),
        str_replace('%', $go, FILE_PATH)
    );
    echo $go . PHP_EOL;
    sleep(1);
}

// TODO 需要获取下载文件的信息, 报错我猜是后缀对不上, 格式不一致导致的.

# 额外的接口
# https://www.doutula.com/api/search?keyword=金馆长&mime=0&page=2