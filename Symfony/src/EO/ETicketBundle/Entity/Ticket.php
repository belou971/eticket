<?php

namespace EO\ETicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EO\ETicketBundle\Entity\Booking;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Ticket
 *
 * @ORM\Table(name="eo_ticket")
 * @ORM\Entity(repositoryClass="EO\ETicketBundle\Repository\TicketRepository")
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
     * @Assert\NotBlank(message ="Le nom est vide", payload={"severity"="error"})
     */
    private $visitorName;

    /**
     * @var string
     *
     * @ORM\Column(name="visitorSurname", type="string", length=255)
     * @Assert\NotBlank(message="Le prénom est vide", payload={"severity"="error"})
     */
    private $visitorSurname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="visitorDtBirth", type="date")
     * @Assert\Date(message = "La date de naissance n'est pas valide", payload={"severity"="error"})
     * @Assert\NotBlank(message = "La date de naissance est vide", payload={"severity"="error"})
     */
    private $visitorDtBirth;

    /**
     * @var boolean
     * @ORM\Column(name="preferredRate", type="boolean", options={"default":false})
     * @Assert\Type(
     *     type="bool",
     *     message="{{ value }} n'est pas valide"
     * )
     */
    private $preferredRate;

    /**
     * @var \EO\ETicketBundle\Entity\Rate
     *
     * @ORM\ManyToOne(targetEntity="EO\ETicketBundle\Entity\Rate", inversedBy="ticket")
     * @ORM\JoinColumn(nullable=false)
     */
    private $priceHT;

    /**
     * @var Booking
     *
     * @ORM\ManyToOne(targetEntity="EO\ETicketBundle\Entity\Booking", inversedBy="tickets", cascade={"persist"})
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
     * @param \EO\ETicketBundle\Entity\Rate $priceHT
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
     * @return \EO\ETicketBundle\Entity\Rate
     */
    public function getPriceHT()
    {
        return $this->priceHT;
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
     * Set preferredRate
     *
     * @param boolean $preferredRate
     *
     * @return Ticket
     */
    public function setPreferredRate($preferredRate)
    {
        $this->preferredRate = $preferredRate;

        return $this;
    }

    /**
     * Get preferredRate
     *
     * @return boolean
     */
    public function getPreferredRate()
    {
        return $this->preferredRate;
    }

    /**
     * Get age of the visitor from the birthday
     */
    public function getAge()
    {
        $now = new \DateTime();
        $age = $now->diff($this->getVisitorDtBirth());

        return $age->format('%y');
    }
}
