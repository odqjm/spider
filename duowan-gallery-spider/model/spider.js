/**
 * spider.js
 * 做过一些小封装的spider, 可配置下载的间隔时间等.
 */
const superagent = require('superagent');

let spider = {};

// 请求

spider.crawl = (url) => {
    return new Promise(function (resolve, reject) {
        console.log('request '+ url + ' ...');
        superagent.get(url).end(function (error, content) {
            if (error) {
                reject(error);
            }
            else {
                console.log('request ok');
                resolve(content);
            }
        });
    });
};

module.exports = spider;
