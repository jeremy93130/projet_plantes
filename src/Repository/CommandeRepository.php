<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commande>
 *
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    //    /**
//     * @return Commande[] Returns an array of Commande objects
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

    //    public function findOneBySomeField($value): ?Commande
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
            ->leftJoin('d.plante', 'p') // Assurez-vous que 'plante' correspond au nom de l'association dans l'entité Commande
            ->andWhere('d.id = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getAllCommandesWithDetails()
    {
        return $this->createQueryBuilder('c')
            ->select('c.id as commande_id, c.dateCommande, c.etatCommande, c.total')
            ->addSelect('d.id as detail_id, d.quantite')
            ->addSelect('p.id as plante_id, p.nom_plante, p.description_plante, p.prix_plante, p.stock, p.image, p.caracteristiques, p.entretien')
            ->addSelect('u.id as user_id, u.nom as user_nom, u.prenom as user_prenom, u.email as user_email, u.telephone as user_telephone, u.roles as user_roles')
            ->addSelect('a.id as adresse_id, a.adresse, a.codePostal, a.ville, a.pays, a.instruction_livraison, a.nomComplet')
            ->leftJoin('c.detailsCommandes', 'd')
            ->leftJoin('d.plante', 'p')
            ->leftJoin('c.client', 'u')
            ->leftJoin('u.adresses', 'a')
            ->andWhere('u.id = :val')
            ->setParameter('val', 4)
            ->getQuery()
            ->getResult();
    }
}
