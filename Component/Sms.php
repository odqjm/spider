<?php

declare(strict_types=1);

namespace Component;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class Sms
{
    /**
     * getSmsConfig
     *
     * @param string $smsProvider
     * @return array
     * @author hurong
     */
    private static function getSmsConfig($smsProvider = 'ali_sms'): array
    {
        $config = require '_config.php';
        return $config[$smsProvider];
    }

    /**
     * TODO saveSmsLog
     *
     * @param $info
     * @author hurong
     */
    private static function saveSmsLog($info)
    {

    }

    /**
     * sendSingle
     *
     * @param string $phoneNumber
     * @param string $templateCode
     * @param array $templateParam
     * @param string $signName
     * @return array
     * @throws ClientException
     * @throws ServerException
     * @throws SmsException
     * @author hurong
     */
    public static function sendSingle(string $phoneNumber, string $templateCode, array $templateParam, string $signName = '葫榕web'): array
    {
        $config = self::getSmsConfig();

        AlibabaCloud::accessKeyClient($config['app_key'], $config['app_secret'])
            ->regionId('cn-hangzhou')
            ->asDefaultClient();

        $result = AlibabaCloud::rpc()
            ->product('Dysmsapi')
            // ->scheme('https') // https | http
            ->version('2017-05-25')
            ->action('SendSms')
            ->method('POST')
            ->options([
                'query' => [
                    'PhoneNumbers'  => $phoneNumber,
                    'SignName'      => $signName,
                    'TemplateCode'  => $templateCode,
                    'TemplateParam' => json_encode($templateParam),
                ],
            ])
            ->request()
            ->toArray();

        self::saveSmsLog($result);

        if ($result['Code'] !== 'OK') {
            throw new SmsException($result['Message'], 1111);
        }

        return $result;
    }
}