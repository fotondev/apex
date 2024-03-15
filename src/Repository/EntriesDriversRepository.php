<?php

namespace App\Repository;

use App\Entity\EntriesDrivers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EntriesDrivers>
 *
 * @method EntriesDrivers|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntriesDrivers|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntriesDrivers[]    findAll()
 * @method EntriesDrivers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntriesDriversRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntriesDrivers::class);
    }

//    /**
//     * @return EntriesDrivers[] Returns an array of EntriesDrivers objects
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

//    public function findOneBySomeField($value): ?EntriesDrivers
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
