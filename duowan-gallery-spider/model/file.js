const fs = require('fs');
let file = {};

// 写入指定文件
file.write = (path, data) => {
    return new Promise(function (resolve, reject) {
        console.log('write '+ path + ' ...');
        fs.writeFile(path, data, function (error) {
            if (error) {
                reject(error);
            }
            else {
                console.log('write ok');
                resolve(true)
            }
        });
    });
};

// 读取指定文件的内容
file.read = (path) => {
    return new Promise(function (resolve, reject) {
        console.log('read '+ path + ' ...');
        fs.readFile(path, function (error, data) {
            if (error) {
                reject(error);
            }
            else {
                console.log('read ok');
                resolve(data)
            }
        });
    });
};

// 创建目录
file.mkdir = (path) => {
    return new Promise(function (resolve, reject) {
        console.log('mkdir '+ path + ' ...');
        fs.mkdir(path, function (error) {
            if (error) {
                reject(error);
            }
            else {
                console.log('mkdir ok');
                resolve(true)
            }
        });
    });
};

module.exports = file;
