<?php
/**
 * Created by PhpStorm.
 * User: belou
 * Date: 30/01/18
 * Time: 16:35
 */

namespace EO\ETicketBundle\BookingMailer;


use Doctrine\ORM\EntityManager;
use EO\ETicketBundle\Entity\Contact;
use EO\ETicketBundle\Entity\Museum;
use EO\ETicketBundle\Enum\MessageEnum;
use EO\ETicketBundle\Type\Messages;
use Symfony\Bundle\TwigBundle\TwigEngine;

use EO\ETicketBundle\Entity\Booking;

class EOBookingMailer {

    private $mailer;
    private $twig;
    private $manager;

    /**
     * EOBookingMailer constructor.
     * @param $mailer
     */
    public function __construct(\Swift_Mailer $mailer, TwigEngine $twig, EntityManager $manager)
    {
        $this->mailer  = $mailer;
        $this->twig    = $twig;
        $this->manager = $manager;
    }

    public function confirm(Booking $booking, $invoice)
    {
        $museumRepo = $this->manager->getRepository('EOETicketBundle:Museum');
        $museum = $museumRepo->findOneBy(array('name' => 'Musée du Louvre'));
        if(is_null($museum)) {
            return;
        }

        $contact = $museum->getContact();


        $from = $contact->getToArray();
        $to   = $booking->getEmail();
        $subject = sprintf(Messages::getInstance()->get(MessageEnum::MAIL_SUBJECT), $booking->getBookingCode());

        $body = $this->twig->render('@EOETicket/sections/mail.html.twig',
        array("subject" => "Confirmation reservation ".$booking->getBookingCode(),
            "bookingCode" => $booking->getBookingCode(),
            "buyerSurname" => $booking->getSurname(),
            "bookingType" => $booking->getBookingTypeLabel(),
            "dateVisitor" => $booking->getDtVisitor()->formatDateToLetter(),
            "invoice" => $invoice,
            "nbTickets" => count($booking->getTickets()),
            "tickets" => $booking->getTickets(),
            "museum" => $museum));

        $this->send($from, $to, $subject, $body);
    }

    private function send($from, $to, $subject, $body)
    {
        $message = \Swift_Message::newInstance();

        $message->setTo($to)
                ->setFrom($from)
                ->setSubject($subject)
                ->setBody($body)
                ->setContentType("text/html");

        $this->mailer->send($message);
    }

}