<?php
/**
 * Created by PhpStorm.
 * User: Eve ODIN
 * Date: 13/10/17
 * Time: 17:48
 */

namespace EO\ETicketBundle\Enum;

/**
 * Class RateEnum
 * @package EO\ETicketBundle\Enum
 */
class RateEnum
{
    const NORMAL         = "normal";
    const BABY           = "bébé";
    const CHILDREN       = "enfant";
    const SENIOR         = "sénior";
    const PREFERRED_RATE = "réduit";
    const TVA            = "t.v.a";

    static public function getValues() {
        $values[] = RateEnum::BABY;
        $values[] = RateEnum::CHILDREN;
        $values[] = RateEnum::NORMAL;
        $values[] = RateEnum::SENIOR;
        $values[] = RateEnum::PREFERRED_RATE;
        $values[] = RateEnum::TVA;

        return $values;
    }
}