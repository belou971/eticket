<?php
/**
 * Created by PhpStorm.
 * User: belou
 * Date: 20/11/17
 * Time: 14:55
 */

namespace  EO\ETicketBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EO\ETicketBundle\Entity\Rate;
use EO\ETicketBundle\Enum\RateEnum;

class RateFixture implements  FixtureInterface {

    public function load(ObjectManager $manager)
    {
        $types = RateEnum::getValues();

        foreach( $types as $type) {
            $rate = new Rate();
            $rate->setRateType($type);

            if($type === RateEnum::BABY) {
                $rate->setAgeMax(2);
                $rate->setValue(0.0);
            }

            if($type === RateEnum::CHILDREN) {
                $rate->setAgeMax(11);
                $rate->setValue(8.0);
            }

            if($type === RateEnum::SENIOR) {
                $rate->setAgeMax(300);
                $rate->setValue(12.0);
            }

            if($type === RateEnum::PREFERRED_RATE) {
                $rate->setValue(10.0);
            }

            if($type === RateEnum::NORMAL) {
                $rate->setAgeMax(59);
                $rate->setValue(16.0);
            }

            if($type === RateEnum::TVA) {
                $rate->setValue(0.2);
            }

            $manager->persist($rate);
        }

        $manager->flush();
    }
}