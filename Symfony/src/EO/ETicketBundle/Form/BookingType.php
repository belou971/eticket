<?php

namespace EO\ETicketBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Tests\Extension\Core\Type\CollectionTypeTest;
use Symfony\Component\OptionsResolver\OptionsResolver;


class BookingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dtVisitor', AvailableDateType::class)
            ->add('bookingType', BookingChoiceType::class)
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add('dtBirth', BirthdayType::class, array('format' => 'dd-MM-yyyy'))
            ->add('email', EmailType::class)
            ->add('tickets', CollectionType::class, array(
                'label' => false,
                'entry_type' => TicketType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => false
            ));
            //->add('save', SubmitType::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EO\ETicketBundle\Entity\Booking'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'eo_eticketbundle_booking';
    }


}
