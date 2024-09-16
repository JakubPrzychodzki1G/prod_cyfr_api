<?php

namespace App\Repository;

use App\Entity\SwimGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SwimGroup>
 *
 * @method SwimGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method SwimGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method SwimGroup[]    findAll()
 * @method SwimGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SwimGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SwimGroup::class);
    }

//    /**
//     * @return SwimGroup[] Returns an array of SwimGroup objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SwimGroup
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
