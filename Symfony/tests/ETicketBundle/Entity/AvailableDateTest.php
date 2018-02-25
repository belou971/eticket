<?php
/**
 * Created by PhpStorm.
 * User: belou
 * Date: 25/02/18
 * Time: 23:21
 */


namespace EO\ETicketBundle\Tests\Entity;

use EO\ETicketBundle\Entity\AvailableDate;
use PHPUnit\Framework\TestCase;

class AvailableDateTest extends TestCase
{

    public function testHasPlace() {
        $bookingDate = new AvailableDate();
        $bookingDate->setDate(new \DateTime('2018-03-29'));
        $bookingDate->setPlaceAvailable(2);

        $this->assertTrue($bookingDate->hasPlace());
    }

    public function testDecreasePlaceAvailable() {
        $bookingDate = new AvailableDate();
        $bookingDate->setDate(new \DateTime('2018-03-29'));
        $bookingDate->setPlaceAvailable(10);

        $bookingDate->decreasePlaceAvailable(6);

        $this->assertEquals(10 - 6, $bookingDate->getPlaceAvailable());
    }
}