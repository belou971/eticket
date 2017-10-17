<?php
/**
 * Created by PhpStorm.
 * User: belou
 * Date: 16/10/17
 * Time: 17:09
 */

namespace EO\ETicketBundle\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

abstract class EnumType extends Type
{
    protected $nameType;
    protected $values = array();

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param array $fieldDeclaration The field declaration.
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform The currently used database platform.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $lovalues = array_map(function($val) { return "'".$val."'"; }, $this->values);

        return "enum(".implode(", ", $lovalues).")";
    }

    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return $this->nameType;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if(!in_array($value, $this->values, true)){
            throw new \UnexpectedValueException(sprintf('La valeur "%s" n\'est pas un type de %s reconnu. Les valeurs
             attendues sont [%s]',
                $value, $this->nameType, implode(", ", $this->values)));
        }

        return $value;
        /*try {
            $booking_type = new BookingEnum($value);
            return $booking_type;
        } catch (\UnexpectedValueException $ex) {
            echo "La valeur '$value' n'est pas un type de réservation reconnu.
             Les valeurs attendues sont [" . BookingEnum::HALF . "," . BookingEnum::FULL . "]" . PHP_EOL;
        }*/
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}