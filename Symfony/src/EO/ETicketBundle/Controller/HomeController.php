<?php

namespace EO\ETicketBundle\Controller;

use Doctrine\ORM\EntityManager;
use EO\ETicketBundle\Entity\AvailableDate;
use EO\ETicketBundle\Entity\Booking;
use EO\ETicketBundle\Entity\Ticket;
use EO\ETicketBundle\Enum\MessageEnum;
use EO\ETicketBundle\Form\AvailableDateType;
use EO\ETicketBundle\Form\BookingType;
use EO\ETicketBundle\Form\TicketType;
use EO\ETicketBundle\Type\Messages;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Session\Session;

class HomeController extends Controller
{
    public function indexAction(Request $request)
    {
        $entityMgr = $this->getDoctrine()->getManager();
        $booking = new Booking($entityMgr);
        $form = $this->createForm(BookingType::class, $booking);
        if(!$request->getSession()->has('booking')) {
            $request->getSession()->set('booking', $booking);
        }

        if($request->isMethod('POST') )
        {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                $entityMgr->persist($booking);

                foreach($booking->getTickets() as $ticket)
                {
                    $ticket->setBooking($booking);

                    $entityMgr->persist($ticket);
                }

                //Update the number of available places for the visitor day
                $visitorDay = $booking->getDtVisitor();
                $visitorDay->decreasePlaceAvailable( count($booking->getTickets()) );
                $entityMgr->persist($visitorDay);

                $entityMgr->flush();

                return $this->redirectToRoute('eoe_ticket_paiement');
            }
        }

        //var_dump($request->getSession()->get('booking'));
        //getStepView($step_name, $form)
        return $this->render('EOETicketBundle:Home:index.html.twig', array('form' => $form->createView()));
    }

    public function dateAction(Request $request)
    {
        if($request->isMethod('POST')) {

            $date_msg  = '';
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

    public function paiementAction(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $request->getSession()->getFlashBag()->add('notice', 'Réservation enregistrée');

            return $this->render('EOETicketBundle:Home:paiement.html.twig');
        }

        $request->getSession()->getFlashBag()->add('error', 'Echec lors de la réservation');

        return $this->render('EOETicketBundle:Home:paiement.html.twig');
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