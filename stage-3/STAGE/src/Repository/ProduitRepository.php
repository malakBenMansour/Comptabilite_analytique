<?php

namespace App\Repository;

use App\Entity\Produit;
use App\Entity\ProduitSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use http\QueryString;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }
    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('p')->where('p.quantite<1000');
    }

    /**

     * @return Query
     */
    public function findAllVisibleQuery(ProduitSearch $search):Query
    {
        $query=$this->findVisibleQuery();
        if($search->getMaxPrice())
        {
            $query=$query
                ->where('p.prix<=:maxPrice')
                ->setParameter('maxPrice',$search->getMaxPrice());
        }
        if($search->getMinQuantite())
        {
            $query=$query
                ->where('p.quantite<=:minQuantite')
                ->setParameter('minQuantite',$search->getMinQuantite());
        }
        return $query->getQuery();
    }

    // /**
    //  * @return Produit[] Returns an array of Produit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Produit
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */



    public function countByprix()
    {

        $query=$this->createQueryBuilder('a')->select('count(a.prix)as count')->groupby('a.nom');
        return $query->getQuery()->getResult();
    }

}
