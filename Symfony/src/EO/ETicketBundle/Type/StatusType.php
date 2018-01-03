<?php
/**
 * Created by PhpStorm.
 * User: belou
 * Date: 04/12/17
 * Time: 23:24
 */

namespace EO\ETicketBundle\Type;

use EO\ETicketBundle\Type\EnumType;
use EO\ETicketBundle\Enum\StatusEnum;

class StatusType extends EnumType
{
    protected $nameType = 'StatusType';
    protected $values = array(StatusEnum::STANDBY, StatusEnum::PAID);

    public function getValues()
    {
        return $this->values;
    }
}