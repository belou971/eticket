<?php
/**
 * Created by PhpStorm.
 * User: belou
 * Date: 24/11/17
 * Time: 15:31
 */

namespace EO\ETicketBundle\Validator;

use Doctrine\ORM\EntityManager;
use EO\ETicketBundle\Entity\Booking;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotAvailablePlaceValidator extends  ConstraintValidator
{

    private  $entityMgr;

    /**
     * InvalidRateValidator constructor.
     * @param $entityMgr
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityMgr = $entityManager;
    }


    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($booking, Constraint $constraint)
    {
        $availableDateRepo = $this->entityMgr->getRepository('EOETicketBundle:AvailableDate');
        if(is_null($availableDateRepo)){
            throw NotFoundHttpException("Impossible to access to the number of available place information.");
        }

        $availableDate = $booking->getDtVisitor();
        $dateFound = $availableDateRepo->findOneBy(array('date' => $availableDate->getDate()));
        if( !is_null($dateFound) ) {

            $hasAvailablePlace = $dateFound->getPlaceAvailable() - count($booking->getTickets());
            if($hasAvailablePlace < 0) {
                $this->context
                    ->buildViolation($constraint->message)
                    ->setParameters(array('%nbPlace%' => $dateFound->getPlaceAvailable()))
                    ->atPath('dtVisitor')
                    ->addViolation();
            }
        }

    }
}