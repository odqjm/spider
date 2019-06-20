<?php

declare(strict_types=1);

namespace DuowanGallery;

use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use Component\Sms;
use Component\SmsException;
use Component\SmsTemplate;

class IndexController
{
    public const URL         = 'http://tu.duowan.com/tag/5037.html?offset=0&order=created';
    public const GALLERY_URL = 'http://tu.duowan.com/gallery/${gid}.html';
    public const PHOTO_URL   = 'http://tu.duowan.com/index.php?r=show/getByGallery/&gid=${gid}&_=${time}';

    public static function main(): void
    {
        $galleryIds = self::getGalleryIdsByRequest();

        $galleryIds = self::filterGalleryIdsByDb($galleryIds);

        if (empty($galleryIds)) {
            return;
        }

        foreach ($galleryIds as $galleryId) {

            $url = str_replace(['${gid}', '${time}'], [$galleryId, time() . '000'], self::PHOTO_URL);

            $response = json_decode(file_get_contents($url), true);

            self::handleSaveGallery($response['picInfo']);
        }

        self::sendTaskAchievement();
    }

    private static function getGalleryIdsByRequest(): array
    {
        $html = file_get_contents(self::URL);

        $startPos = mb_strpos($html, '<li class="box"');

        $html = mb_substr($html, $startPos);

        $endPos = mb_strpos($html, '</ul>');

        $html = mb_substr($html, 0, $endPos);

        foreach (["\t", "\n", "\r"] as $value) {
            $html = str_replace($value, '', $html);
        }

        preg_match_all(
            '/<a href=\"http:\/\/tu.duowan.com\/gallery\/(\d+)\.html\" target=\"_blank\">/',
            $html,
            $matches
        );

        return array_values(array_unique($matches[1]));
    }

    private static function filterGalleryIdsByDb($galleryIds): array
    {
        // TODO
        return $galleryIds;
    }

    private static function handleSaveGallery($picInfo): void
    {

    }

    private static function sendTaskAchievement(): void
    {
        try {
            Sms::sendSingle(
                '13640222578',
                SmsTemplate::getTemplateCodeByType(SmsTemplate::TYPE_WORK_OK),
                [
                    'worker' => 'EPS',
                    'work'   => '多玩图库爬取',
                ]
            );
        } catch (ClientException $e) {
            echo $e->getMessage() . PHP_EOL;
        } catch (ServerException $e) {
            echo $e->getMessage() . PHP_EOL;
        } catch (SmsException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }
}