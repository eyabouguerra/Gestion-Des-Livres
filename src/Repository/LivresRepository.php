<?php

namespace App\Repository;

use App\Entity\Livres;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Livres>
 *
 * @method Livres|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livres|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livres[]    findAll()
 * @method Livres[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livres::class);
    }

//    /**
//     * @return Livres[] Returns an array of Livres objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Livres
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


public function searchLivres($query)
{
    $queryBuilder = $this->createQueryBuilder('l');

    if ($query) {
        $queryBuilder->andWhere(
            $queryBuilder->expr()->orX(
                $queryBuilder->expr()->like('l.titre', ':query'),
                $queryBuilder->expr()->like('l.Auteur', ':query'), // Correction du nom du champ de l'auteur
            )
        )->setParameter('query', '%'.$query.'%');
    }

    return $queryBuilder->getQuery()->getResult();
}





}
