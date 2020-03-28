<?php

namespace App\Repository;

use App\Entity\ImageAlbum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ImageAlbum|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageAlbum|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageAlbum[]    findAll()
 * @method ImageAlbum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageAlbumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageAlbum::class);
    }

    // /**
    //  * @return ImageAlbum[] Returns an array of ImageAlbum objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ImageAlbum
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
