<?php

namespace EO\ETicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EO\ETicketBundle\Entity\Booking;
use EO\ETicketBundle\Enum\RateEnum;
use EO\ETicketBundle\Type\RateType;

/**
 * Ticket
 *
 * @ORM\Table(name="eo_ticket")
 * @ORM\Entity(repositoryClass="EO\ETicketBundle\Repository\TicketRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Ticket
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
     * @var string
     *
     * @ORM\Column(name="visitorName", type="string", length=255)
     */
    private $visitorName;

    /**
     * @var string
     *
     * @ORM\Column(name="visitorSurname", type="string", length=255)
     */
    private $visitorSurname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="visitorDtBirth", type="date")
     */
    private $visitorDtBirth;

    /**
     * @var \EO\ETicketBundle\Entity\Rate
     *
     * @ORM\OneToOne(targetEntity="EO\ETicketBundle\Entity\Rate", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $priceHT;

    /**
     * @var \EO\ETicketBundle\Enum\RateEnum
     *
     * @ORM\Column(type="RateType")
     */
    private $rateType;

    /**
     * @var Booking
     *
     * @ORM\ManyToOne(targetEntity="EO\ETicketBundle\Entity\Booking", inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booking;


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
     * Set visitorName
     *
     * @param string $visitorName
     *
     * @return Ticket
     */
    public function setVisitorName($visitorName)
    {
        $this->visitorName = $visitorName;

        return $this;
    }

    /**
     * Get visitorName
     *
     * @return string
     */
    public function getVisitorName()
    {
        return $this->visitorName;
    }

    /**
     * Set visitorSurname
     *
     * @param string $visitorSurname
     *
     * @return Ticket
     */
    public function setVisitorSurname($visitorSurname)
    {
        $this->visitorSurname = $visitorSurname;

        return $this;
    }

    /**
     * Get visitorSurname
     *
     * @return string
     */
    public function getVisitorSurname()
    {
        return $this->visitorSurname;
    }



    /**
     * Set priceHT
     *
     * @param float $priceHT
     *
     * @return Ticket
     */
    public function setPriceHT($priceHT)
    {
        $this->priceHT = $priceHT;

        return $this;
    }

    /**
     * Get priceHT
     *
     * @return float
     */
    public function getPriceHT()
    {
        return $this->priceHT;
    }

    /**
     * Set rateType
     *
     * @param RateType $rateType
     *
     * @return Ticket
     */
    public function setRateType($rateType)
    {
        $this->rateType = $rateType;

        return $this;
    }

    /**
     * Get rateType
     *
     * @return RateType
     */
    public function getRateType()
    {
        return $this->rateType;
    }

    /**
     * Set booking
     *
     * @param Booking $booking
     *
     * @return Ticket
     */
    public function setBooking(Booking $booking)
    {
        $this->booking = $booking;

        return $this;
    }

    /**
     * Get booking
     *
     * @return Booking
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     * Set visitorDtBirth
     *
     * @param \DateTime $visitorDtBirth
     *
     * @return Ticket
     */
    public function setVisitorDtBirth($visitorDtBirth)
    {
        $this->visitorDtBirth = $visitorDtBirth;

        return $this;
    }

    /**
     * Get visitorDtBirth
     *
     * @return \DateTime
     */
    public function getVisitorDtBirth()
    {
        return $this->visitorDtBirth;
    }



    /**
     * @ORM\PostPersist
     */
    public function decreasePlace()
    {
        $visitDate = $this->booking->getDtVisitor();

        if(!is_null($visitDate)) {
            $visitDate->decreasePlaceAvailable();
        }

    }
}
