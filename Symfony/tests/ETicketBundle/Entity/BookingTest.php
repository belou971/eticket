<?php
/**
 * Created by PhpStorm.
 * User: belou
 * Date: 25/02/18
 * Time: 23:43
 */

namespace EO\ETicketBundle\Tests\Entity;

use EO\ETicketBundle\Entity\Booking;
use PHPUnit\Framework\TestCase;

class BookingTest extends TestCase
{

    public function testGenerate() {
        $bookingDate = new Booking();
        $code = $bookingDate->generateCode(12);

        $this->assertEquals(12, strlen($code));
    }
}