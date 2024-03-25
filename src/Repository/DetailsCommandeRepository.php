<?php

namespace App\Repository;

use App\Entity\DetailsCommande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DetailsCommande>
 *
 * @method DetailsCommande|null find($id, $lockMode = null, $lockVersion = null)
 * @method DetailsCommande|null findOneBy(array $criteria, array $orderBy = null)
 * @method DetailsCommande[]    findAll()
 * @method DetailsCommande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DetailsCommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DetailsCommande::class);
    }

    //    /**
//     * @return DetailsCommande[] Returns an array of DetailsCommande objects
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

    //    public function findOneBySomeField($value): ?DetailsCommande
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findByJoin($commande): array
    {
        return $this->createQueryBuilder('d')
            ->select('d', 'produits', 'commande')
            ->leftJoin('d.produits', 'plante')
            ->leftJoin('d.commande', 'commande')
            ->where('commande IN (:commande)')
            ->setParameter('commande', $commande)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByUserId($user): array
    {
        return $this->createQueryBuilder('detailsCommande')
            ->select('detailsCommande', 'commande', 'produit', 'adresse', 'client')
            ->leftJoin('detailsCommande.commande', 'commande')
            ->leftJoin('detailsCommande.produit', 'produit')
            ->leftJoin('commande.client', 'client')
            ->leftJoin('commande.adresses', 'adresse')
            ->andWhere('client.id = :userId')
            ->setParameter('userId', $user)
            ->getQuery()
            ->getResult();
    }
}
