/**
 * sms.js
 * 短信model
 * 待定
 */
const { sms } = require('../config.js');

const notice = function (mobile, place) {
    let message = sms.message.replace('{place}', place);

};

module.export = {
    notice
};