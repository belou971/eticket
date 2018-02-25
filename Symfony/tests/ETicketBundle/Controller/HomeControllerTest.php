<?php
/**
 * Created by PhpStorm.
 * User: belou
 * Date: 26/02/18
 * Time: 00:03
 */

namespace EO\ETicketBundle\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testShowBookingStep() {

        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertCount(1, $crawler->filter('div.step-booking'));
    }
}