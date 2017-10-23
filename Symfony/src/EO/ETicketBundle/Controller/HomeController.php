<?php

namespace EO\ETicketBundle\Controller;

use EO\ETicketBundle\Entity\AvailableDate;
use EO\ETicketBundle\Form\AvailableDateType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    public function indexAction(Request $request)
    {
        $bookingDate = new AvailableDate();

        //Generate AvailableDate form
        $dateForm = $this->createForm(AvailableDateType::class, $bookingDate);

        if($request->isMethod('POST') )
        {
            if($dateForm->handleRequest($request)->isSubmitted()) {

                $data =  $dateForm->getData();
                if(is_null($data)){
                    echo "Handle form FAILED";
                }
                elseif(is_array($data)){
                    var_dump($data);
                }
                elseif(is_string($data)) {
                    echo "data is a string: ".$data;
                }
                else {
                    echo "WTF: " ;
                    var_dump($data);
                }
            }
        }
        return $this->render('EOETicketBundle:Home:index.html.twig', array(
            'dateform' => $dateForm->createView()));
    }
}
