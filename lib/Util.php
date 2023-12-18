<?php

namespace JQuest;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date Jul 26, 2015
 */
class Util
{

    /**
     * Ref: https://secure.php.net/manual/en/intldateformatter.create.php#105460
     * @param \DateTime $datetime
     * @return string
     */
    public static function JapanDateTime(\DateTime $datetime)
    {
        // $formatter = new \IntlDateFormatter(/*'ja_JP@calendar=japanese'*/'en', \IntlDateFormatter::MEDIUM, \IntlDateFormatter::MEDIUM, 'Asia/Tokyo', \IntlDateFormatter::TRADITIONAL);
        // return $formatter->format($datetime);
        return $datetime->format('Y-m-d H:i:s');
    }
}
