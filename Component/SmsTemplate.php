<?php

declare(strict_types=1);

namespace Component;

class SmsTemplate
{
    public const TYPE_CAPTCHA   = '验证码';
    public const TYPE_MORNING   = '女友清晨';
    public const TYPE_WORK_OK   = '工作完成';
    public const TYPE_WORK_FAIL = '工作失败';

    public static function getTemplateCodeByType($type): string
    {
        switch ($type) {
            case self::TYPE_CAPTCHA:
                return 'SMS_95795005';
            case self::TYPE_MORNING:
                return 'SMS_149097809';
            case self::TYPE_WORK_OK:
                return 'SMS_159626807';
            case self::TYPE_WORK_FAIL:
                return 'SMS_159781214';
        }
    }
}