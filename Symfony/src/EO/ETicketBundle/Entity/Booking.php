<?php

namespace EO\ETicketBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use EO\ETicketBundle\Enum\BookingEnum;
use EO\ETicketBundle\Type\BookingType;

/**
 * Booking
 *
 * @ORM\Table(name="eo_booking")
 * @ORM\Entity(repositoryClass="EO\ETicketBundle\Repository\BookingRepository")
 */
class Booking
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255)
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dtBirth", type="date")
     */
    private $dtBirth;


    /**
     * @var \EO\ETicketBundle\Entity\AvailableDate
     *
     * @ORM\ManyToOne(targetEntity="\EO\ETicketBundle\Entity\AvailableDate")
     */
    private $dtVisitor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dtCreation", type="datetime")
     */
    private $dtCreation;

    /**
     * @var float
     *
     * @ORM\Column(name="invoice", type="float")
     */
    private $invoice;


    /**
     * @var BookingEnum
     *
     * @ORM\Column(type="BookingType")
     */
    private $bookingType;


    /**
     * @var \EO\ETicketBundle\Entity\Ticket
     *
     * @ORM\OneToMany(targetEntity="EO\ETicketBundle\Entity\Ticket", mappedBy="booking")
     */
    private $tickets;


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
     * Set name
     *
     * @param string $name
     *
     * @return Booking
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname
     *
     * @param string $surname
     *
     * @return Booking
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Booking
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set dtBirth
     *
     * @param \DateTime $dtBirth
     *
     * @return Booking
     */
    public function setDtBirth($dtBirth)
    {
        $this->dtBirth = $dtBirth;

        return $this;
    }

    /**
     * Get dtBirth
     *
     * @return \DateTime
     */
    public function getDtBirth()
    {
        return $this->dtBirth;
    }

    /**
     * Set dtVisitor
     *
     * @param \EO\ETicketBundle\Entity\AvailableDate $dtVisitor
     *
     * @return Ticket
     */
    public function setDtVisitor(\EO\ETicketBundle\Entity\AvailableDate $dtVisitor = null)
    {
        $this->dtVisitor = $dtVisitor;

        return $this;
    }

    /**
     * Get dtVisitor
     *
     * @return \EO\ETicketBundle\Entity\AvailableDate
     */
    public function getDtVisitor()
    {
        return $this->dtVisitor;
    }

    /**
     * Set dtCreation
     *
     * @param \DateTime $dtCreation
     *
     * @return Booking
     */
    public function setDtCreation($dtCreation)
    {
        $this->dtCreation = $dtCreation;

        return $this;
    }

    /**
     * Get dtCreation
     *
     * @return \DateTime
     */
    public function getDtCreation()
    {
        return $this->dtCreation;
    }

    /**
     * Set invoice
     *
     * @param float $invoice
     *
     * @return Booking
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Get invoice
     *
     * @return float
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * Set bookingType
     *
     * @param BookingType $bookingType
     *
     * @return Booking
     */
    public function setBookingType($bookingType)
    {
        $this->bookingType = $bookingType;

        return $this;
    }

    /**
     * Get bookingType
     *
     * @return BookingType
     */
    public function getBookingType()
    {
        return $this->bookingType;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->dtCreation = new \DateTime();
    }

    /**
     * Add ticket
     *
     * @param \EO\ETicketBundle\Entity\Ticket $ticket
     *
     * @return Booking
     */
    public function addTicket(\EO\ETicketBundle\Entity\Ticket $ticket)
    {
        $this->tickets[] = $ticket;

        $ticket->setBooking($this);

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param \EO\ETicketBundle\Entity\Ticket $ticket
     */
    public function removeTicket(\EO\ETicketBundle\Entity\Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }
}
