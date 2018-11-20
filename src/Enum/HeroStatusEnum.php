<?php

namespace App\Enum;

class HeroStatusEnum
{
    const STATUS_ATTACKER = 'attacker';

    const STATUS_DEFENDER = 'defender';

    /**
     * user friendly named status.
     *
     * @var array
     */
    private static $typeName = [
        self::STATUS_ATTACKER => 'Attacker',
        self::STATUS_DEFENDER => 'Defender',
    ];

    /**
     * @param string $statusShortName
     *
     * @return string
     */
    public static function getStatusName(string $statusShortName)
    {
        if (!isset(self::$typeName[$statusShortName])) {
            throw new \UnexpectedValueException("Unknown status ($statusShortName)");
        }

        return self::$typeName[$statusShortName];
    }

    /**
     * @return array
     */
    public static function getAvailableStatuses()
    {
        return [
            self::STATUS_ATTACKER,
            self::STATUS_DEFENDER,
        ];
    }
}
