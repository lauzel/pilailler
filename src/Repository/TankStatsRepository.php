<?php

namespace App\Repository;

use App\Entity\TankStats;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TankStats|null find($id, $lockMode = null, $lockVersion = null)
 * @method TankStats|null findOneBy(array $criteria, array $orderBy = null)
 * @method TankStats[]    findAll()
 * @method TankStats[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TankStatsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TankStats::class);
    }

    // /**
    //  * @return TankStats[] Returns an array of TankStats objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TankStats
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
