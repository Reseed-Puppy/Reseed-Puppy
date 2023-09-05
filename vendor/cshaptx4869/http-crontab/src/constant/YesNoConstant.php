<?php

namespace Fairy\constant;

class YesNoConstant
{
    const NO = 0;
    const YES = 1;

    /**
     * @return string[]
     */
    public static function yesNoOptions()
    {
        return [
            self::NO => '否',
            self::YES => '是'
        ];
    }
}