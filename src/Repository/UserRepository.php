<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('u')
            ->where('u.something = :value')->setParameter('value', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findNotEqualId($value)
    {
        return $this->createQueryBuilder('u')
            ->where('u.id != :value')
            ->andWhere('u.username != :admin')
            ->setParameter('value', $value)
            ->setParameter('admin', 'admin')
            ->getQuery()
            ->getResult();
    }

    public function findClassement()
    {
        return $this->createQueryBuilder('u')
            ->where('u.username != :admin')
            ->setParameter('admin', 'admin')
            ->orderBy('u.userNbVictoire', 'DESC')
            ->addOrderBy('u.userNbPartie', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
