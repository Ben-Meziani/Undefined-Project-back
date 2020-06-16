<?php

namespace App\Repository;

use App\Entity\Pseudo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pseudo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pseudo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pseudo[]    findAll()
 * @method Pseudo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PseudoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pseudo::class);
    }

    // /**
    //  * @return Pseudo[] Returns an array of Pseudo objects
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
    public function findOneBySomeField($value): ?Pseudo
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
