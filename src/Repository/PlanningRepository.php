<?php

namespace App\Repository;

use App\Entity\Planning;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Planning|null find($id, $lockMode = null, $lockVersion = null)
 * @method Planning|null findOneBy(array $criteria, array $orderBy = null)
 * @method Planning[]    findAll()
 * @method Planning[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanningRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Planning::class);
    }

//    /**
//     * @return Planning[] Returns an array of Planning objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Planning
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findLast($companyId)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.company = :val')
            ->setParameter('val', $companyId)
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }


    public function findActualByCompany($company)
    {

        $year = new \Datetime();
        $week = new \DateTime();

        return $this->createQueryBuilder('p')
            ->andWhere('p.week = :week')
            ->andWhere('p.year = :year')
            ->andWhere('p.company = :company')
            ->setParameter('week', $week->format('W'))
            ->setParameter('year', $year->format('Y'))
            ->setParameter('company', $company)
            ->orderBy('p.day')
            ->getQuery()
            ->getResult()
        ;
    }
}
