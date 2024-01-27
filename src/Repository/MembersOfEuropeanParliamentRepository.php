<?php

namespace App\Repository;

use App\Entity\MembersOfEuropeanParliament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MembersOfEuropeanParliament>
 *
 * @method MembersOfEuropeanParliament|null find($id, $lockMode = null, $lockVersion = null)
 * @method MembersOfEuropeanParliament|null findOneBy(array $criteria, array $orderBy = null)
 * @method MembersOfEuropeanParliament[]    findAll()
 * @method MembersOfEuropeanParliament[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MembersOfEuropeanParliamentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MembersOfEuropeanParliament::class);
    }

}
