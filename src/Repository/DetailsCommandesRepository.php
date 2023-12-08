<?php

namespace App\Repository;

use App\Entity\DetailsCommandes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DetailsCommandes>
 *
 * @method DetailsCommandes|null find($id, $lockMode = null, $lockVersion = null)
 * @method DetailsCommandes|null findOneBy(array $criteria, array $orderBy = null)
 * @method DetailsCommandes[]    findAll()
 * @method DetailsCommandes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DetailsCommandesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DetailsCommandes::class);
    }

    //    /**
//     * @return DetailsCommandes[] Returns an array of DetailsCommandes objects
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

    //    public function findOneBySomeField($value): ?DetailsCommandes
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findByIdWithPlantes($value): array
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.plante', 'p') // Assurez-vous que 'plante' correspond au nom de l'association dans l'entité DetailsCommandes
            ->andWhere('d.id = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
