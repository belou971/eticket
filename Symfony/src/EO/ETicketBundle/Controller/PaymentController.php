<?php
/**
 * Created by PhpStorm.
 * User: belou
 * Date: 16/01/18
 * Time: 16:36
 */

namespace EO\ETicketBundle\Controller;

use EO\ETicketBundle\Enum\MessageEnum;
use EO\ETicketBundle\Enum\RateEnum;
use EO\ETicketBundle\Payments\StripeCheckout;

use EO\ETicketBundle\Type\Messages;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class PaymentController extends Controller
{
    public function indexAction()
    {
        if(!$this->has('session')) {
            return $this->redirectToRoute('eoe_ticket_homepage');
        }

        $session = $this->get('session');
        $session->getMetadataBag()->getLifetime();
        if(!$session->has('booking')) {
            return $this->redirectToRoute('eoe_ticket_homepage');
        }

        $booking = $session->get('booking');
        if(is_null($booking) || is_null($booking->getBookingCode())) {
            $response = new Response();
            $response->setContent("Une erreur s'est produite. veuillez revenir ultérieurement");
            $response->setStatusCode(Response::HTTP_NOT_FOUND);

            return $response;
        }

        return $this->render("@EOETicket/Payment/paiement.html.twig",
            array("bookingCode" => $booking->getBookingCode(), "invoice" => $this->getInvoice($booking)));
    }

    public function chargeAction(Request $request)
    {
        if($request->isMethod('POST')) {
            $token = $request->get('stripeToken');
            if(!is_null($request->getSession()) && $request->getSession()->has('booking')) {
                $bookingData = $request->getSession()->get('booking');

                $status = $this->updateAvailableDate($bookingData);
                if($status === MessageEnum::AVAILABLE_PLACE_NO) {
                    return $this->redirectToRoute('eoe_ticket_failure',
                        array('message' => MessageEnum::AVAILABLE_PLACE_NO));
                }

                $rate = $this->getTaxCost();
                $chargeData = StripeCheckout::charge($token, $bookingData, $rate->getValue());

                if($chargeData['paid'] === true) {
                    $this->get('session')->set('infos', $chargeData['outcome']);
                    return $this->redirectToRoute('eoe_ticket_succeed');
                }
                else {
                    $this->get('session')->set('infos', $chargeData['error']);
                    return $this->redirectToRoute('eoe_ticket_failure',
                        array('message' => MessageEnum::BOOKING_FAILED));
                }
            }
        }

        //TODO: redirect to unknown error route
        $response = new Response();
        $response->setContent("Une erreur s'est produite. veuillez revenir ultérieurement");
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
        return $response;
    }

    public function succeedAction()
    {
        if(is_null($this->get('session')) || !$this->get('session')->has('booking')) {
            return $this->redirectToRoute('eoe_ticket_homepage');
        }

        $session = $this->get('session');
        $bookingData = $session->get('booking');

        $this->saveBookingInDB($bookingData);

        $stripeInfos = array();
        if($session->has('infos')) {
            $stripeInfos = $session->get('infos');
        }

        $message = sprintf(Messages::getInstance()->get(MessageEnum::BOOKING_SUCCEEDED), $bookingData->getEmail());
        $session->remove('booking');
        $session->remove('infos');

        $this->mailerTest($bookingData);

        return $this->render("@EOETicket/Payment/confirmation.html.twig",
            array('isSaved' => true, 'stripeInfos'=> $stripeInfos, 'message' => $message));
    }

    public function failureAction(Request $request)
    {
        if(is_null($this->get('session')) || !$this->get('session')->has('booking')) {
            return $this->redirectToRoute('eoe_ticket_homepage');
        }

        $session = $this->get('session');
        $stripeInfos = array();
        if($session->has('infos')) {
            $stripeInfos = $session->get('infos');
        }
        if($session->has('booking')) {
            $bookingData = $session->get('booking');
        }
        $session->remove('infos');

        if(!$request->isMethod('GET')) {
            //TODO: Redirect 404
            $response = new Response();
            $response->setContent("Une erreur s'est produite. veuillez revenir ultérieurement");
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $response;
        }

        if( !$request->query->has('message') ||
            (($request->query->get('message') !== MessageEnum::AVAILABLE_PLACE_NO) &&
             ($request->query->get('message') !== MessageEnum::BOOKING_FAILED))
          )
        {
            //TODO: redirect 404
            $response = new Response();
            $response->setContent("Cette page est introuvable");
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $response;
        }


        if( $request->query->has('message') &&
            ($request->query->get('message') === MessageEnum::AVAILABLE_PLACE_NO)) {
            $date = $bookingData->getDtVisitor();
            $message = sprintf(Messages::getInstance()->get(MessageEnum::AVAILABLE_PLACE_NO), $date->formatDateToLetter());
        }

        if( $request->query->has('message') &&
            ($request->query->get('message') === MessageEnum::BOOKING_FAILED)) {
            $message = sprintf(Messages::getInstance()->get(MessageEnum::BOOKING_FAILED));
        }

        return $this->render("@EOETicket/Payment/confirmation.html.twig",
            array('isSaved' => false, 'stripeInfos'=> $stripeInfos, 'message' => $message));
    }

    private function mailerTest($booking)
    {
        $bMailer = $this->get('eo_eticket.bookingmailer');
        $bMailer->confirm($booking, $this->getInvoice($booking));
    }

    private function saveBookingInDB($booking)
    {
        $entityMgr = $this->getDoctrine()->getManager();

        $entityMgr->persist($booking);

        foreach ($booking->getTickets() as $ticket) {
            $ticket->setBooking($booking);

            $entityMgr->persist($ticket);
        }

        $entityMgr->flush();
    }

    private function updateAvailableDate($booking)
    {
        $entityMgr = $this->getDoctrine()->getManager();

        $visitDate = $booking->getDtVisitor();
        $dateRepo = $entityMgr->getRepository('EOETicketBundle:AvailableDate');
        $availDateDB = $dateRepo->findOneBy(array('date' => $visitDate->getDate()));

        if(!is_null($availDateDB))
        {
            $nbTicket = count($booking->getTickets());
            if($availDateDB->getPlaceAvailable() - $nbTicket >= 0) {
                $availDateDB->decreasePlaceAvailable($nbTicket);
                $booking->setDtVisitor($availDateDB);

                return MessageEnum::AVAILABLE_DATE_YES;
            }
            else {
                return MessageEnum::AVAILABLE_PLACE_NO;
            }
        }

        //No booking in DB for this date
        return MessageEnum::AVAILABLE_DATE_YES;
    }

    private function getInvoice($booking)
    {
        $rate = $this->getTaxCost();
        if(is_null($rate)) {
            throw new NotFoundHttpException("impossible to access to information from database");
        }

        return $booking->computeInvoice($rate->getValue());
    }

    private function getTaxCost()
    {
        $entityMgr = $this->getDoctrine()->getManager();
        $rateRepo = $entityMgr->getRepository('EOETicketBundle:Rate');

        if(is_null($rateRepo)) {
            throw new NotFoundHttpException("Rate repository not found");
        }

        return $rateRepo->findOneBy(array('rateType' => RateEnum::TVA));
    }
}