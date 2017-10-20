<?php

namespace EO\ETicketBundle\Controller;

use EO\ETicketBundle\Entity\AvailableDate;
use EO\ETicketBundle\Form\AvailableDateType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        $bookingDate = new AvailableDate();

        //Generate AvailableDate form
        $dateForm = $this->createForm(AvailableDateType::class, $bookingDate);

        return $this->render('EOETicketBundle:Home:index.html.twig', array(
            'dateform' => $dateForm->createView()));
    }
}
