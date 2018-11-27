const method = require('./method');
const config = require('./config.js');
const spider = require('./model/spider.js');
const file = require('./model/file.js');

let data = {
    menus: [],
    wantsMenuIndex: 1,
    wantsMenu: {},
    blocks: [],
    gallerys: {},
};
let todayDir = './data/' + new Date().toLocaleDateString() + '-';

var start = async function () {
    "use strict";
    // 获取菜单
    let htmlData = null;
    let cacheData = null;

    // 先查缓存的菜单
    try {
        cacheData = await file.read('./data/menus.json');
        cacheData = cacheData.toString();
        cacheData = JSON.parse(cacheData);
        data.menus = cacheData;
    }
    catch (error) {
        console.log('!!! no menus.json !!!');
    }

    // 如果没有缓存
    if (!cacheData) {
        // 请求
        try {
            htmlData = await spider.crawl(config.rootUrl);
            htmlData = method.analyze(htmlData);
            data.menus = htmlData.menus;
        }
        catch (error) {
            console.log('!!! request menus fail!!!')
            console.log(error);
            return;
        }

        // 缓存菜单
        try {
            await file.write('./data/menus.json', JSON.stringify(data.menus));
        }
        catch (error) {
            console.log('!!! write menus.json fail!!!');
            console.log(error);
            return;
        }
    }

    cacheData = null;
    // 查缓存的blocks
    try {
        cacheData = await file.read(todayDir + data.wantsMenuIndex + '-blocks.json');
        cacheData = cacheData.toString();
        cacheData = JSON.parse(cacheData);
    }
    catch (error) {
        console.log('!!! no blocks.json !!!');
    }

    // 请求blocks
    data.wantsMenu = data.menus[data.wantsMenuIndex];
    try {
        htmlData = await spider.crawl(data.wantsMenu.href);
        htmlData = method.analyze(htmlData);
        data.blocks = htmlData.blocks;
    }
    catch (error) {
        console.log('!!! request blocks fail!!!');
        console.log(error);
        return;
    }

    // 如果没有缓存
    if (!cacheData) {
        // 缓存blocks
        try {
            await file.write(todayDir + data.wantsMenuIndex + '-blocks.json', JSON.stringify(data.blocks));
        }
        catch (error) {
            console.log('!!! write blocks.json fail!!!')
            console.log(error);
            return;
        }
    }
    else {
        // 如果有缓存, 判断是否请求的数据与缓存数据相同
        // 如果相同, 跳出
        // 如果不同, 过滤掉相同的部分
        for (let i = 0; i < data.blocks.length; i++) {
            for (let j = 0; j < cacheData.length; j++) {
                data.blocks[i].isDeleted = (cacheData[j].galleryId == data.blocks[i].galleryId);
                if (data.blocks[i].isDeleted) {
                    break;
                }
            }
        }
        // 过滤
        let temp = data.blocks.filter(function (block) {
            return !block.isDeleted; // 保留不被删除的
        });

        // 如果数据没有更新, return
        if (temp.length == 0) {
            console.log('=== no new data ===');
            return;
        }
        else {
            // 如果数据有更新了, 覆盖重新写入缓存
            try {
                await file.write(todayDir + data.wantsMenuIndex + '-blocks.json', JSON.stringify(data.blocks));
            }
            catch (error) {
                console.log('!!! write blocks.json fail!!!')
                console.log(error);
                return;
            }
            data.blocks = temp;
        }
    }

    for (let item of data.blocks) {
        let gid = item.galleryId;
        let url = `http://tu.duowan.com/index.php?r=show/getByGallery/&gid=${gid}&_=${Date.now()}`;
        try {
            htmlData = await spider.crawl(url);
            let resp = JSON.parse(htmlData.text)
            data.gallerys[gid] = {
                galleryId: gid,
                infos: resp.picInfo
            };
        }
        catch (error) {
            console.log('!!! gid: ' + gid + ' crawl fail!!!');
            console.log(error)
        }
    }

    for (let item of data.blocks) {
        let gid = item.galleryId;
        try {
            await file.write(todayDir + data.wantsMenuIndex + '-gallery-' + gid + '.json', JSON.stringify(data.gallerys[gid]));
        }
        catch (error) {
            console.log('!!! write ' + gid + '.json fail!!!');
            console.log(error);
            return;
        }
    }

};

start();