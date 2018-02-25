<?php
/**
 * Created by PhpStorm.
 * User: Eve ODIN
 * Date: 13/10/17
 * Time: 18:07
 */


namespace EO\ETicketBundle\Type;

//use EO\ETicketBundle\Type\EnumType;
use EO\ETicketBundle\Enum\BookingEnum;


class BookingType extends EnumType
{
    protected $nameType = 'BookingType';
    protected $values = array(BookingEnum::HALF, BookingEnum::FULL);

    public static function getValues()
    {
        $map_values = array('Journée' => BookingEnum::FULL, 'Demi-journée' => BookingEnum::HALF);

        return $map_values;
    }

    public static function getLabel($bookingType) {
        if($bookingType === BookingEnum::FULL) {
            return 'Journée';
        }

        if($bookingType === BookingEnum::HALF) {
            return 'Demi-journée';
        }
    }
}