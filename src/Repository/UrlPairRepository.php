<?php

namespace App\Repository;

use App\Entity\UrlPair;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UrlPair|null find($id, $lockMode = null, $lockVersion = null)
 * @method UrlPair|null findOneBy(array $criteria, array $orderBy = null)
 * @method UrlPair[]    findAll()
 * @method UrlPair[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UrlPairRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UrlPair::class);
    }

//    /**
//     * @return UrlPair[] Returns an array of UrlPair objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UrlPair
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
