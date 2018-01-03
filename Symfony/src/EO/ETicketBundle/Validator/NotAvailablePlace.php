<?php
/**
 * Created by PhpStorm.
 * User: belou
 * Date: 24/11/17
 * Time: 15:20
 */

namespace EO\ETicketBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class NotAvailablePlace
 * @package EO\ETicketBundle\Validator
 * @Annotation
 */
class NotAvailablePlace extends Constraint
{
    public $message = 'Le nombre de place disponible est insuffisant. Il reste %nbPlace% places';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}
