<?php
/**
 * Created by PhpStorm.
 * User: Eve ODIN
 * Date: 24/10/2017
 * Time: 14:14
 */

namespace EO\ETicketBundle\Enum;


class MessageEnum
{
    /*  References    */
    const BOOKING_DATE               = "booking-date";
    const AVAILABLE_DATE_YES         = "available-date-yes";
    const AVAILABLE_DATE_NO          = "available-date-no";
    const AVAILABLE_DATE_OUT_OF_DATE = "available-date-out-of-date";
    const UNKOWN_REFERENCE           = "unknown-ref";
    const AVAILABLE_PLACE_NO         = "available-place-no";
    const BOOKING_SUCCEEDED          = "booking-succeed";
    const BOOKING_FAILED             = "booking-failed";
    const MAIL_SUBJECT               = "mail-subject";

    /**
     * List of all references of messages
     *
     * @var array
     */
    private static $table = [
                             self::BOOKING_DATE,
                             self::AVAILABLE_DATE_YES,
                             self::AVAILABLE_DATE_NO,
                             self::AVAILABLE_DATE_OUT_OF_DATE,
                             self::UNKOWN_REFERENCE,
                             self::AVAILABLE_PLACE_NO,
                             self::BOOKING_SUCCEEDED,
                             self::BOOKING_FAILED,
                             self::MAIL_SUBJECT
                            ];

    /**
     * @param  string $reference Represents a message reference
     * @return bool returns true if that reference exists in the reference table, false otherwise
     */
    public static function exists($reference)
    {
        return in_array($reference, self::$table);
    }
}