<?php
/**
 * Created by PhpStorm.
 * User: Eve ODIN
 * Date: 24/10/2017
 * Time: 13:47
 */

Namespace EO\ETicketBundle\Type;

use EO\ETicketBundle\Enum\MessageEnum;

class Messages
{
    private static $instance = NULL;
    private $mtable;


    public static function getInstance()
    {
        if(!is_null(Messages::$instance)) {
            return Messages::$instance;
        }

        Messages::$instance = new Messages();
        return Messages::$instance;
    }

    public function get($reference)
    {
        if(MessageEnum::exists($reference)) {
            return $this->mtable[$reference];
        }

        return $this->mtable[MessageEnum::UNKOWN_REFERENCE];
    }

    /**
     * Message constructor.
     */
    private function __construct()
    {
        $this->mtable = array(
            MessageEnum::BOOKING_DATE               => "Vous souhaitez réserver pour le %s ",
            MessageEnum::AVAILABLE_DATE_YES         => "Il reste %d place(s) disponible(s)",
            MessageEnum::AVAILABLE_DATE_NO          => "Il n'y a plus de places disponibles",
            MessageEnum::AVAILABLE_DATE_OUT_OF_DATE => "Nous sommes désolés, les réservations sont terminées pour le %s",
            MessageEnum::UNKOWN_REFERENCE           => "Cette référence est inconnue",
            MessageEnum::AVAILABLE_PLACE_NO         => "Le nombre de place disponible , pour la date du %s, est insuffisant pour terminer votre réservation.",
            MessageEnum::BOOKING_SUCCEEDED          => "Merci pour votre réservation. Sous 48 heures, vous recevrez vos billets à l'adresse mail suivante: %s.",
            MessageEnum::BOOKING_FAILED             => "Nous sommes désolés, votre réservation n'a pas été prise en compte."
        );
    }
}