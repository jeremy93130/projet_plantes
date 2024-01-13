<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findUser($id)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id =' . $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    //    /**
    //     * @return User[] Returns an array of User objects
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

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    // SELECT * FROM `user` u 
    // JOIN address a on a.user_id = u.id
    // JOIN my_order o ON u.id = o.user_id
    // // WHERE u.id = 18;
    //     public function getCommandeByUser($id)
    //     {
    //         return $this->createQueryBuilder('u')
    //             ->select('u', 'a', 'c')
    //             ->leftJoin('u.adresses', 'a')
    //             ->leftJoin('u.commande', 'c')
    //             ->where('u.id = :userId')
    //             ->setParameter('userId', $id)
    //             ->getQuery()
    //             ->getOneOrNullResult();
    //     }

}
