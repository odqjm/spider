const cheerio = require('cheerio');

// 分析请求到的html, 解析出数据
let analyze = function (content) {
    let $ = cheerio.load(content.text);
    let menuList = [];
    // menuList
    $('#subnav_pk ul li a').each((index, item) => {
        menuList.push({
            href: $(item).attr('href'),
            text: $(item).text()
        });
    });
    // 获取每个图集块的封面, 图集链接等
    let blockList = [];
    $('li.box').each((index, item) => {
        if (index == 0) {
            return;
        }
        let obj = {};
        obj.cover = $(item).find('a img').attr('src');
        obj.link = $(item).children('a').attr('href');
        obj.title = $(item).find('em a').text();
        obj.crawlTime = Date.now();
        obj.galleryId = obj.link.match(/[0-9]+/)[0];
        blockList.push(obj);
    });
    return {
        menus: menuList,
        blocks: blockList
    };
};

module.exports = {
    analyze
};
