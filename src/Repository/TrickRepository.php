<?php

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trick>
 *
 * @method Trick|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trick|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trick[]    findAll()
 * @method Trick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }

    /**
     * Récupère tous les tricks avec leurs images respectives
     *
     * @return Trick[]
     */
    public function findAllWithImages(): array
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.images', 'i')
            ->addSelect('i')
            ->getQuery()
            ->getResult();
    }

    public function findAllWithImagesByUserId($userId): array
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.images', 'i')
            ->addSelect('i')
            ->where('t.user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère un trick par son slug avec ses images respectives
     *
     * @param string $slug
     * @return Trick|null
     */
    public function findOneBySlugWithImages(string $slug): ?Trick
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.images', 'i')
            ->addSelect('i')
            ->where('t.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function removeTrickAndAssociations(int $trickId): void
    {
        $entityManager = $this->getEntityManager();
        $trick = $this->find($trickId);

        if ($trick) {
            $entityManager->remove($trick);
            $entityManager->flush();
        }
    }

    /**
     * Récupère jusqu'à 3 tricks avec leurs images respectives
     *
     * @return array
     */
    public function findTricksWithImages(): array
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.images', 'i')
            ->addSelect('i')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }
    //    /**
    //     * @return Trick[] Returns an array of Trick objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Trick
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
