<?php

namespace EO\ETicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EO\ETicketBundle\Enum\RateEnum;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Rate
 *
 * @ORM\Table(name="eo_rate")
 * @ORM\Entity(repositoryClass="EO\ETicketBundle\Repository\RateRepository")
 */
class Rate
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
     * @var \EO\ETicketBundle\Enum\RateEnum
     *
     * @ORM\Column(type="RateType")
     * @Assert\Choice(callback = {"RateEnum", "getValues"}, message = "Type de tarif inconnu")
     */
    private $rateType;

    /**
     * @var int
     *
     * @ORM\Column(name="ageMax", type="integer", nullable=true)
     */
    private $ageMax;

    /**
     * @var float
     *
     * @ORM\Column(name="value", type="float")
     */
    private $value;


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
     * Set value
     *
     * @param float $value
     *
     * @return Rate
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set ageMax
     *
     * @param integer $ageMax
     *
     * @return Rate
     */
    public function setAgeMax($ageMax)
    {
        $this->ageMax = $ageMax;

        return $this;
    }

    /**
     * Get ageMax
     *
     * @return integer
     */
    public function getAgeMax()
    {
        return $this->ageMax;
    }

}
