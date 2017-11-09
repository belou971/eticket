<?php
/**
 * Created by PhpStorm.
 * User: belou
 * Date: 30/10/17
 * Time: 22:14
 */

namespace EO\ETicketBundle\Form;

use EO\ETicketBundle\Type\BookingType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingChoiceType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => BookingType::getValues(),
            'multiple' => false,
            'expanded' => true
        ));
    }

    public function  buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options); // TODO: Change the autogenerated stub

        $builder->addEventListener(FormEvents::POST_SUBMIT, array($this, 'onMyPreSubmit'));
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    public function getName()
    {
        return 'bookingchoice';
    }


    public function onMyPreSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        $bookingChoiceBtn = $form->getClickedButton();
        if(is_null($bookingChoiceBtn) || is_null($data)) {
            return;
        }

        echo "button name is: ".$bookingChoiceBtn->getName();
        /*$availableDateRepo = $this->entityMgr->getRepository("EOETicketBundle:AvailableDate");

        if(is_null($availableDateRepo)) {
            throw new NotFoundHttpException("The repository of AvailableDate not found");
        }*/
    }
}