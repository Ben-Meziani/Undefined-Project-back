<?php

namespace App\Repository;

use App\Entity\Dice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Dice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dice[]    findAll()
 * @method Dice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dice::class);
    }

    // /**
    //  * @return Dice[] Returns an array of Dice objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Dice
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
