<?php

namespace App\Repository;

use App\Entity\Fridge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fridge>
 */
class FridgeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fridge::class);
    }

    public function list()
    {
        return $this->findAll();
    }
    public function save(Fridge $fridge, bool $flush = false): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($fridge);
        if ($flush) {
            $entityManager->flush();
        }
    }

    // Metoda do usuwania Fridge
    public function remove(Fridge $fridge, bool $flush = false): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($fridge);
        if ($flush) {
            $entityManager->flush();
        }
    }

    //    /**
    //     * @return Fridge[] Returns an array of Fridge objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Fridge
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
