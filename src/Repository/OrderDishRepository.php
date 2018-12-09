<?php

namespace App\Repository;

use App\Entity\OrderDish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OrderDish|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderDish|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderDish[]    findAll()
 * @method OrderDish[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderDishRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OrderDish::class);
    }

    // /**
    //  * @return OrderDish[] Returns an array of OrderDish objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrderDish
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
