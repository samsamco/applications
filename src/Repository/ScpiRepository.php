<?php

namespace App\Repository;

use App\Entity\Scpi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Scpi|null find($id, $lockMode = null, $lockVersion = null)
 * @method Scpi|null findOneBy(array $criteria, array $orderBy = null)
 * @method Scpi[]    findAll()
 * @method Scpi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScpiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Scpi::class);
    }

    // /**
    //  * @return Scpi[] Returns an array of Scpi objects
    //  */
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
    public function findOneBySomeField($value): ?Scpi
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
