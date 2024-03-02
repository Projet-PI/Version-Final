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
    public function findBySearchQuery(\Symfony\Component\HttpFoundation\InputBag|float|bool|int|string|null $searchQuery): \Doctrine\ORM\Query
    {
        $qb = $this->createQueryBuilder('u');

        if (!empty($searchQuery)) {
            $qb->andWhere('u.Nom LIKE :search OR u.Email LIKE :search')
                ->setParameter('search', '%' . $searchQuery . '%');
        }

        return $qb->getQuery();

    }

    public function findBySearchTerm(\Symfony\Component\HttpFoundation\InputBag|float|bool|int|string|null $searchTerm)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.Nom LIKE :term OR u.Email LIKE :term')
            ->setParameter('term', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();
    }


}
