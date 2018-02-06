<?php

namespace EO\ETicketBundle\Controller;


use EO\ETicketBundle\Entity\AvailableDate;
use EO\ETicketBundle\Entity\Booking;

use EO\ETicketBundle\Enum\MessageEnum;

use EO\ETicketBundle\Form\BookingType;
use EO\ETicketBundle\Type\Messages;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class HomeController extends Controller
{
    public function indexAction(Request $request)
    {
        $entityMgr = $this->getDoctrine()->getManager();
        $booking = new Booking();

        $session = $request->getSession();
        if(!is_null($session) && $session->has('booking')) {
            $booking = $session->get('booking');
        }

        $form = $this->createForm(BookingType::class, $booking);

        if($request->isMethod('POST') )
        {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {

                $session->set('booking', $form->getData());

                return $this->redirectToRoute('eoe_ticket_paiement');
            }
        }

        return $this->render('EOETicketBundle:Home:index.html.twig', array('form' => $form->createView()));
    }

    public function dateAction(Request $request)
    {
        if($request->isMethod('POST')) {
            $place_msg = '';
            $entityMgr = $this->getDoctrine()->getManager();
            $availableDateRepo = $entityMgr->getRepository('EOETicketBundle:AvailableDate');

            if(is_null($availableDateRepo)) {
                throw new NotFoundHttpException("Available date repository not found");
            }

            $dateToFind = new \DateTime($request->get('date'));
            $availableDate = $availableDateRepo->findOneBy(array('date' => $dateToFind));
            if(is_null($availableDate)) {

                $availableDate = new AvailableDate();
                $availableDate->setDate($dateToFind);

                $formatDate = $availableDate->formatDateToLetter();
                if($availableDate->isDate()) {
                    $date_msg = sprintf(Messages::getInstance()->get(MessageEnum::BOOKING_DATE), $formatDate);
                    $place_msg = sprintf(Messages::getInstance()->get(MessageEnum::AVAILABLE_DATE_YES), $availableDate->getPlaceAvailable());
                }
                else {
                    $date_msg = sprintf(Messages::getInstance()->get(MessageEnum::AVAILABLE_DATE_OUT_OF_DATE), $formatDate);
                }
                return new JsonResponse(array('date' => $date_msg, 'nbPlace' => $place_msg));
            }

            $formatDate = $availableDate->formatDateToLetter();
            if($availableDate->isDate()) {
                $date_msg = sprintf(Messages::getInstance()->get(MessageEnum::BOOKING_DATE), $formatDate);
                if ($availableDate->getPlaceAvailable() > 0) {
                    $place_msg = sprintf(Messages::getInstance()->get(MessageEnum::AVAILABLE_DATE_YES), $availableDate->getPlaceAvailable());
                } else {
                    $place_msg = sprintf(Messages::getInstance()->get(MessageEnum::AVAILABLE_DATE_NO));
                }
            } else {
                $date_msg = sprintf(Messages::getInstance()->get(MessageEnum::AVAILABLE_DATE_OUT_OF_DATE), $formatDate);
            }

            return new JsonResponse(array('date' => $date_msg, 'nbPlace' => $place_msg));
        }

        return new Response('http request method is wrong');
    }

    public function addAction(Request $request)
    {
        //check the number of available place
        $nbPlace = $this->getAvailablePlace($request->get('date'));

        $ticket_template = $this->renderView('EOETicketBundle:Form:ticketView.html.twig', array(
            'nbPlace' => $nbPlace,
            'nbTicket' => $request->get('nbaddedticket')
        ));

        $place = $nbPlace - $request->get('nbaddedticket');

        return new JsonResponse( array('ticket'=> "$ticket_template", 'place' => $place) );
    }

    public function loadAction() {
        $rateRepo = $this->getDoctrine()->getManager()->getRepository('EOETicketBundle:Rate');
        if(is_null($rateRepo)) {
            throw new NotFoundHttpException("Impossible d'accèder à la base de données");
        }

        $rates = $rateRepo->jsonFindAll();

        return new JsonResponse($rates);
    }

    private function getAvailablePlace($date)
    {
        $entityMgr = $this->getDoctrine()->getManager();
        $availableDateRepo = $entityMgr->getRepository('EOETicketBundle:AvailableDate');

        if(is_null($availableDateRepo)) {
            throw new NotFoundHttpException("Available date repository not found");
        }

        $dateToFind = new \DateTime($date);
        $availableDate = $availableDateRepo->findOneBy(array('date' => $dateToFind));

        if(is_null($availableDate)) {
            $availableDate = new AvailableDate();
            $availableDate->setDate($dateToFind);
        }

        return $availableDate->getPlaceAvailable();
    }
}