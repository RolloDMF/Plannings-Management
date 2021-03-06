<?php

namespace App\Repository;

use App\Entity\Schedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Schedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method Schedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method Schedule[]    findAll()
 * @method Schedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScheduleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Schedule::class);
    }

//    /**
//     * @return Schedule[] Returns an array of Schedule objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Schedule
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findMinSchedule($company)
    {
        $qb = $this->createQueryBuilder('s');
        return $qb
            ->andWhere('s.company = :val')
            //cheking if convertedFirstTimeStop is not null for avoid errors when the day have no schedule
            ->andWhere($qb->expr()->isNotNull('s.convertedFirstTimeStart'))
            ->setParameter('val', $company)
            ->orderBy('s.firstTimeStart', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findMaxSchedule($company)
    {
        $qb = $this->createQueryBuilder('s');
        $maxFirtsTime = $qb
            ->andWhere('s.company = :val')
            //cheking if convertedFirstTimeStop is not null for avoid errors when the day have no schedule
            ->andWhere($qb->expr()->isNotNull('s.convertedFirstTimeStop'))
            ->setParameter('val', $company)
            ->orderBy('s.firstTimeStop', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        $qb = $this->createQueryBuilder('s');
        $maxSecondTime = $qb
            ->andWhere('s.company = :val')
            //cheking if convertedFirstTimeStop is not null for avoid errors when the day have no schedule
            ->andWhere($qb->expr()->isNotNull('s.convertedSecondTimeStop'))
            ->setParameter('val', $company)
            ->orderBy('s.secondTimeStop', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        if ($maxSecondTime === null) {
            return $maxFirtsTime;
        }else{
            if ($maxFirtsTime->getConvertedFirstTimeStop() > $maxSecondTime->getConvertedSecondTimeStop()) {
                return $maxFirtsTime;
            }else {
                return $maxSecondTime;
            }
        }
    }
}
