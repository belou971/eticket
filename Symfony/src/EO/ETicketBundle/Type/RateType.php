<?php
/**
 * Created by PhpStorm.
 * User: belou
 * Date: 16/10/17
 * Time: 23:26
 */

namespace EO\ETicketBundle\Type;

use EO\ETicketBundle\Type\EnumType;
use EO\ETicketBundle\Enum\RateEnum;

class RateType extends EnumType
{
    protected $nameType = 'RateType';
    protected $values = array(RateEnum::NORMAL,
                              RateEnum::BABY,
                              RateEnum::CHILDREN,
                              RateEnum::SENIOR,
                              RateEnum::PREFERRED_RATE,
                              RateEnum::TVA);

    public function getValues()
    {
        return $this->values;
    }
}