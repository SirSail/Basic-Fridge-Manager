<?php

namespace App\Repository;

use App\Entity\UserRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserRole::class);
    }

    public function save(UserRole $userRole, bool $flush = false): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($userRole);
        if ($flush) {
            $entityManager->flush();
        }
    }

    public function remove(UserRole $userRole, bool $flush = false): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($userRole);
        if ($flush) {
            $entityManager->flush();
        }
    }


    //    /**
    //     * @return UserRole[] Returns an array of UserRole objects
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

    //    public function findOneBySomeField($value): ?UserRole
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
