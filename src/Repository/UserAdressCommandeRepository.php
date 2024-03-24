<?php

namespace App\Repository;

use App\Entity\UserAdressCommande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserAdressCommande>
 *
 * @method UserAdressCommande|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAdressCommande|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAdressCommande[]    findAll()
 * @method UserAdressCommande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserAdressCommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAdressCommande::class);
    }

//    /**
//     * @return UserAdressCommande[] Returns an array of UserAdressCommande objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserAdressCommande
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
