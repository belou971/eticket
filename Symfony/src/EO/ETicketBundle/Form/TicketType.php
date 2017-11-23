<?php

namespace EO\ETicketBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('visitorName', TextType::class, array('required' => true))
            ->add('visitorSurname', TextType::class, array('required' => true))
            ->add('visitorDtBirth', BirthdayType::class, array(
                'required' => true,
                'placeholder' => array('day'=>'Jour', 'month'=>'Mois', 'year'=>'Année'),
                'format' => 'dd-MM-yyyy'))
            ->add('priceHT', RateType::class, array('label' => false));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EO\ETicketBundle\Entity\Ticket'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'eo_eticketbundle_ticket';
    }


}
