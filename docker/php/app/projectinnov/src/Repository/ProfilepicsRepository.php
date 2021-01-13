<?php

namespace App\Repository;

use App\Entity\Profilepics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Profilepics|null find($id, $lockMode = null, $lockVersion = null)
 * @method Profilepics|null findOneBy(array $criteria, array $orderBy = null)
 * @method Profilepics[]    findAll()
 * @method Profilepics[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfilepicsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Profilepics::class);
    }

    // /**
    //  * @return Profilepics[] Returns an array of Profilepics objects
    //  */
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
    public function findOneBySomeField($value): ?Profilepics
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
