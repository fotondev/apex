<?php

namespace App\Repository;

use App\Entity\RaceEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RaceEvent>
 *
 * @method RaceEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method RaceEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method RaceEvent[]    findAll()
 * @method RaceEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RaceEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RaceEvent::class);
    }


//    /**
//     * @return RaceEvent[] Returns an array of RaceEvent objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RaceEvent
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
