<?php

namespace EO\ETicketBundle\Form;

use Doctrine\ORM\EntityManager;
use EO\ETicketBundle\Entity\Ticket;
use EO\ETicketBundle\Enum\RateEnum;
use EO\ETicketBundle\Repository\RateRepository;
use EO\ETicketBundle\Validator\InvalidRate;
//use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{

    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

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
                  'format' => 'dd-MM-yyyy',
                  'years' => range(date('Y')-100, date('Y'))))
            ->add('preferredRate', CheckboxType::class, array('required' => false))
            ->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) {
                $ticketData = $event->getData();
                $rateRepo = $this->em->getRepository('EOETicketBundle:Rate');

                if($ticketData->getPreferredRate() === false) {
                    if (!is_null($ticketData->getVisitorDtBirth())) {

                        //hydrate the price object in function the visitor's age
                        $visitorAge = $ticketData->getAge();
                        $rate =  $rateRepo->getRateByAge($visitorAge);
                        $ticketData->setPriceHT($rate);
                    }
                }
                else {
                    //hydrate the price object
                    $preferredRate = $rateRepo->findOneBy(array('rateType' => RateEnum::PREFERRED_RATE));
                    $ticketData->setPriceHT($preferredRate);
                }

                $event->setData($ticketData);
            });
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
        //return 'eo_eticketbundle_ticket';
        return 'ticket';
    }
}
