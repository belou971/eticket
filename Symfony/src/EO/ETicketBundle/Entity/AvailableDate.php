<?php

namespace EO\ETicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * AvailableDate
 *
 * @ORM\Table(name="eo_available_date")
 * @ORM\Entity(repositoryClass="EO\ETicketBundle\Repository\AvailableDateRepository")
 */
class AvailableDate
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", unique=true, type="date")
     * @Assert\Date(message = "La date n'est pas valide")
     * @Assert\NotNull(message = "Quelle est la date de visite?")
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="placeAvailable", type="integer")
     */
    private $placeAvailable;

    /**
     * AvailableDate constructor.
     *
     */
    public function __construct()
    {
        $this->placeAvailable = 1000;
        $this->date = null;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return AvailableDate
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set placeAvailable
     *
     * @param integer $placeAvailable
     *
     * @return AvailableDate
     */
    public function setPlaceAvailable($placeAvailable)
    {
        $this->placeAvailable = $placeAvailable;

        return $this;
    }

    /**
     * Get placeAvailable
     *
     * @return int
     */
    public function getPlaceAvailable()
    {
        return $this->placeAvailable;
    }


    public function decreasePlaceAvailable($nbBookedPlace)
    {
        $this->placeAvailable -= $nbBookedPlace;
    }


    public function formatDateToLetter()
    {
        setlocale(LC_TIME, 'fr_FR.utf8');
        return strftime("%A %e %B %Y", $this->date->getTimestamp());
    }

    /**
     * @param ExecutionContextInterface $context
     * @Assert\IsTrue(message = "Les réservations sont terminées pour cette date")
     * @return bool
     */
    public function isDate()
    {
        if(is_null($this->date)) {
            return false;
        }

        //si date correspond à la celle de maintenant
        //verifier que l'heure actuelle est plus petite que 14h
        $today_str = date_format(new \DateTime(), 'Y-m-d');
        $today = new \DateTime($today_str);
        $nbOfDay = $today->diff($this->getDate())->format('%R%a');
        if($nbOfDay > 0) {
            return true;
        }
        else  if ($nbOfDay < 0) {
            return false;
        }

        $end_booking = $today->setTime(14,00);
        $datetime_now = date_create();
        if($datetime_now > $end_booking)
        {
            return false;
        }

        return true;
    }

    /**
     * @param ExecutionContextInterface $context
     * @Assert\IsTrue(message = "Il n'y a plus de places disponibles pour cette date")
     * @return bool
     */
    public function hasPlace() {
        if($this->placeAvailable <= 0)
            return false;

        return true;
    }
}
