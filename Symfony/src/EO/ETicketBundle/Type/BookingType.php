<?php
/**
 * Created by PhpStorm.
 * User: Eve ODIN
 * Date: 13/10/17
 * Time: 18:07
 */


namespace EO\ETicketBundle\Type;

use EO\ETicketBundle\Type\EnumType;
use EO\ETicketBundle\Enum\BookingEnum;


class BookingType extends EnumType
{
    protected $nameType = 'BookingType';
    protected $values = array(BookingEnum::HALF, BookingEnum::FULL);
}