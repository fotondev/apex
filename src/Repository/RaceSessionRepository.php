<?php

namespace App\Repository;

use App\Entity\RaceSession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RaceSession>
 *
 * @method RaceSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method RaceSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method RaceSession[]    findAll()
 * @method RaceSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RaceSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RaceSession::class);
    }

    //    /**
    //     * @return RaceSession[] Returns an array of RaceSession objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?RaceSession
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
