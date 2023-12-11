<?php

namespace App\Repository;

use App\Entity\DetailsCommandesPlantes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DetailsCommandesPlantes>
 *
 * @method DetailsCommandesPlantes|null find($id, $lockMode = null, $lockVersion = null)
 * @method DetailsCommandesPlantes|null findOneBy(array $criteria, array $orderBy = null)
 * @method DetailsCommandesPlantes[]    findAll()
 * @method DetailsCommandesPlantes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DetailsCommandesPlantesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DetailsCommandesPlantes::class);
    }

//    /**
//     * @return DetailsCommandesPlantes[] Returns an array of DetailsCommandesPlantes objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DetailsCommandesPlantes
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
