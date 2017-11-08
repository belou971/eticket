<?php

namespace EO\ETicketBundle\Controller;

use EO\ETicketBundle\Entity\AvailableDate;
use EO\ETicketBundle\Entity\Booking;
use EO\ETicketBundle\Enum\MessageEnum;
use EO\ETicketBundle\Form\AvailableDateType;
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
        //$booking = new AvailableDate();
        //Generate AvailableDate form
        //$form = $this->createForm(AvailableDateType::class, $booking);

        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);

        if($request->isMethod('POST') )
        {
            if($form->handleRequest($request)->isSubmitted()) {

                $data =  $form->getData();
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
        return $this->render('EOETicketBundle:Home:index.html.twig', array('form' => $form->createView()));
    }

    public function dateAction(Request $request)
    {
        if($request->isMethod('POST')) {
            $entityMgr = $this->getDoctrine()->getManager();
            $availableDateRepo = $entityMgr->getRepository('EOETicketBundle:AvailableDate');

            if(is_null($availableDateRepo)) {
                throw new NotFoundHttpException("Available date repository not found");
            }

            $dateToFind = date_create_from_format('d/m/Y', $request->get('date'));
            $availableDate = $availableDateRepo->findOneBy(array('date' => $dateToFind));
            if(is_null($availableDate)) {

                $availableDate = new AvailableDate();
                $availableDate->setDate($dateToFind);
                $formatDate = $availableDate->formatDateToLetter();

                $date_msg = sprintf(Messages::getInstance()->get(MessageEnum::BOOKING_DATE), $formatDate);
                $place_msg = sprintf(Messages::getInstance()->get(MessageEnum::AVAILABLE_DATE_YES), $availableDate->getPlaceAvailable());

                return new JsonResponse(array('date' => $date_msg, 'nbPlace' => $place_msg));
            }

            $formatDate = $availableDate->formatDateToLetter();
            $date_msg = sprintf(Messages::getInstance()->get(MessageEnum::BOOKING_DATE), $formatDate);
            if($availableDate->getPlaceAvailable() > 0) {
                $place_msg = sprintf(Messages::getInstance()->get(MessageEnum::AVAILABLE_DATE_YES), $availableDate->getPlaceAvailable());
            }
            else {
                $place_msg = sprintf(Messages::getInstance()->get(MessageEnum::AVAILABLE_DATE_NO));
            }
            return new JsonResponse(array('date' => $date_msg, 'nbPlace' => $place_msg));
        }

        return new Response('http request method is wrong');
    }
}