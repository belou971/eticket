<?php

namespace EO\ETicketBundle\Form;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvailableDateType extends AbstractType
{
    private $entityMgr;

    /**
     * AvailableDateType constructor.
     * @param $entityManager
     */
    public function __construct( /*EntityManager $entityManager*/)
    {
        /*$this->entityMgr = $entityManager;*/
    }


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DatePickerType::class, array())

            ->add('save', SubmitType::class);

        //$builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
       // $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
        //$builder->addEventListener(FormEvents::POST_SUBMIT, array($this, 'onPostSubmit'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EO\ETicketBundle\Entity\AvailableDate'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'eo_eticketbundle_availabledate';
    }

    public function onPreSetData(FormEvent $event)
    {
        /*$availableDateRepo = $this->entityMgr->getRepository("EOETicketBundle:AvailableDate");

        if(is_null($availableDateRepo)) {
            throw new NotFoundHttpException("The repository of AvailableDate not found");
        }*/
    }
}
