<?php

namespace EO\ETicketBundle\Repository;

/**
 * RateRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RateRepository extends \Doctrine\ORM\EntityRepository
{
    public function getRateByAge($age) {
        $qb = $this->createQueryBuilder('r');

        $qb->where('r.ageMax is not null')
            ->andWhere('r.ageMax >= :age')
            ->setParameter('age', $age)
            ->orderBy('r.ageMax', 'ASC')
            ->setMaxResults(1);

        $query = $qb->getQuery();

        return $query->getSingleResult();
    }

    public function jsonFindAll() {
        $rates = $this->findAll();

        return json_encode($this->formatToJsonArray($rates), JSON_UNESCAPED_UNICODE);
    }

    private function formatToJsonArray($rates) {

        $jsonRates = array();

        foreach($rates as $rate) {
            $rateRow = array('id' => $rate->getId(),
                             'type' => $rate->getRateType(),
                             'ageMax' => $rate->getAgeMax(),
                             'value' => $rate->getValue());

            $jsonRates[] = $rateRow;
        }

        return $jsonRates;
    }
}
