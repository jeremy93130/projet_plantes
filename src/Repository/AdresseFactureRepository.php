<?php

namespace App\Repository;

use App\Entity\AdresseFacture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdresseFacture>
 *
 * @method AdresseFacture|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdresseFacture|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdresseFacture[]    findAll()
 * @method AdresseFacture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdresseFactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdresseFacture::class);
    }

    public function findByLast($id): ?AdresseFacture
    {
        return $this->createQueryBuilder('af')
            ->andWhere('af.client = :val')
            ->setParameter('val', $id)
            ->orderBy('af.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

//    /**
//     * @return AdresseFacture[] Returns an array of AdresseFacture objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AdresseFacture
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
