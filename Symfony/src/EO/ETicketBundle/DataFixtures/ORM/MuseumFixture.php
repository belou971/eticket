<?php
/**
 * Created by PhpStorm.
 * User: belou
 * Date: 01/02/18
 * Time: 12:39
 */

namespace EO\ETicketBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EO\ETicketBundle\Entity\Contact;
use EO\ETicketBundle\Entity\Horaire;
use EO\ETicketBundle\Entity\Museum;

class MuseumFixture implements FixtureInterface {

    public function load(ObjectManager $manager)
    {
        $contact = new Contact();
        $contact->setEmail("billetterie@louv.fr");
        $contact->setTitle("Billetterie Musée du louvre");
        $manager->persist($contact);

        $louvre = new Museum();
        $louvre->setName("Musée du Louvre");
        $louvre->setAddress("Place du Carroussel 75058 Paris CEDEX 01 France");
        $louvre->setContact($contact);

        $horaire1 = new Horaire();
        $horaire1->setDay("Lundi");
        $horaire1->setStart(9);
        $horaire1->setEnd(18);
        $louvre->addHoraire($horaire1);
        $manager->persist($horaire1);

        $horaire2 = new Horaire();
        $horaire2->setDay("Mercredi");
        $horaire2->setStart(9);
        $horaire2->setEnd(21);
        $louvre->addHoraire($horaire2);
        $manager->persist($horaire2);

        $horaire3 = new Horaire();
        $horaire3->setDay("Jeudi");
        $horaire3->setStart(9);
        $horaire3->setEnd(18);
        $louvre->addHoraire($horaire3);
        $manager->persist($horaire3);

        $horaire4 = new Horaire();
        $horaire4->setDay("Vendredi");
        $horaire4->setStart(9);
        $horaire4->setEnd(21);
        $louvre->addHoraire($horaire4);
        $manager->persist($horaire4);

        $horaire5 = new Horaire();
        $horaire5->setDay("Samedi");
        $horaire5->setStart(9);
        $horaire5->setEnd(18);
        $louvre->addHoraire($horaire5);
        $manager->persist($horaire5);

        $horaire6 = new Horaire();
        $horaire6->setDay("Dimanche");
        $horaire6->setStart(9);
        $horaire6->setEnd(18);
        $louvre->addHoraire($horaire6);
        $manager->persist($horaire6);

        $manager->persist($louvre);

        $manager->flush();
    }
}