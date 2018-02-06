<?php

namespace EO\ETicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Museum
 *
 * @ORM\Table(name="museum")
 * @ORM\Entity(repositoryClass="EO\ETicketBundle\Repository\MuseumRepository")
 */
class Museum
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var \EO\ETicketBundle\Entity\Contact
     *
     * @ORM\OneToOne(targetEntity="\EO\ETicketBundle\Entity\Contact", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $contact;

    /**
     * @var \EO\ETicketBundle\Entity\Horaire
     *
     * @ORM\OneToMany(targetEntity="EO\ETicketBundle\Entity\Horaire", mappedBy="museum", cascade={"remove"})
     */
    private $horaires;

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
     * @return Museum
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
     * Set address
     *
     * @param string $address
     *
     * @return Museum
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set contact
     *
     * @param \EO\ETicketBundle\Entity\Contact $contact
     *
     * @return Museum
     */
    public function setContact(\EO\ETicketBundle\Entity\Contact $contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return \EO\ETicketBundle\Entity\Contact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->horaires = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add horaire
     *
     * @param \EO\ETicketBundle\Entity\Horaire $horaire
     *
     * @return Museum
     */
    public function addHoraire(\EO\ETicketBundle\Entity\Horaire $horaire)
    {
        $this->horaires[] = $horaire;

        $horaire->setMuseum($this);

        return $this;
    }

    /**
     * Remove horaire
     *
     * @param \EO\ETicketBundle\Entity\Horaire $horaire
     */
    public function removeHoraire(\EO\ETicketBundle\Entity\Horaire $horaire)
    {
        $this->horaires->removeElement($horaire);
    }

    /**
     * Get horaires
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHoraires()
    {
        return $this->horaires;
    }
}
