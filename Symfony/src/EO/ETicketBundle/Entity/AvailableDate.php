<?php

namespace EO\ETicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="date", type="date")
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
        $this->placeAvailable = 100;
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
    public function setDate($date)
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


    public function decreasePlaceAvailable()
    {
        $this->placeAvailable--;
    }
}

