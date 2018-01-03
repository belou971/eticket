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
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\Choice;


class BookingType extends AbstractType
{
    private $entityMgr;

    /**
     * AvailableDateType constructor.
     * @param $entityManager
     */
    public function __construct( EntityManager $entityManager)
    {
        $this->entityMgr = $entityManager;
    }

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
            ->add('dtBirth', BirthdayType::class, array(
                'format' => 'dd-MM-yyyy',
                'placeholder' => array('day'=>'Jour', 'month'=>'Mois', 'year'=>'Année'),
                'years' => range(date('Y')-100, date('Y'))))
            ->add('email', EmailType::class)
            ->add('tickets', CollectionType::class, array(
                'label'        => false,
                'entry_type'   => TicketType::class,
                'entry_options'=> array('label' => false),
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype'    => false
            ))
            ->add('save', SubmitType::class, array('label' => 'Continuer'))
            ->addEventListener(
                FormEvents::POST_SUBMIT, array($this, 'onPostSubmitDate')
            );
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

    public function onPostSubmitDate(FormEvent $event)
    {
        $bookingData = $event->getData();
        $visitDate = $bookingData->getDtVisitor();

        $dateRepo = $this->entityMgr->getRepository('EOETicketBundle:AvailableDate');
        $availDateDB = $dateRepo->findOneBy(array('date' => $visitDate->getDate()));

        if(!is_null($availDateDB))
        {
            $bookingData->setDtVisitor($availDateDB);
            $event->setData($bookingData);
        }
    }
}
