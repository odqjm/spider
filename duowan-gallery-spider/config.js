let config = {
    rootPath: './', // 根目录
    dataPath: './data/', //
    rootUrl: 'http://tu.duowan.com/tu', // 根url, 最初请求的url
    sms: {
        message: '{place} 发生了变化! 请及时修改代码以适应新变化!' // 短信未实现
    }
};

config.todayDir = config.dataPath + new Date().toLocaleDateString() + '/'; // ./data/2018/5/07/

module.exports = config;
