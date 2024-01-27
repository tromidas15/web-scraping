<?php

namespace App\Repository;

use App\Entity\MEP;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MEP>
 *
 * @method MEP|null find($id, $lockMode = null, $lockVersion = null)
 * @method MEP|null findOneBy(array $criteria, array $orderBy = null)
 * @method MEP[]    findAll()
 * @method MEP[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MEPRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MEP::class);
    }

//    /**
//     * @return MEP[] Returns an array of MEP objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MEP
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
