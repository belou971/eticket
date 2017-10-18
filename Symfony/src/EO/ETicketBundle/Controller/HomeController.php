<?php

namespace EO\ETicketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('EOETicketBundle:Home:index.html.twig');
    }
}
