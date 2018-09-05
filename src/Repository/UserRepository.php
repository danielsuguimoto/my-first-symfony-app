<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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

    public function findAllWithMoreThen5Posts() {
        return $this->findAllWithMoreThen5PostsQuery()
                    ->getQuery()
                    ->getResult();
    }
    
    public function findAllWithMoreThen5PostsExceptUser(User $user) {
        return $this->findAllWithMoreThen5PostsQuery()
                    ->andHaving('u != :user')
                    ->setParameter('user', $user)
                    ->getQuery()
                    ->getResult();
    }
    
    private function findAllWithMoreThen5PostsQuery() : QueryBuilder {
         return $this->createQueryBuilder('u')
                    ->select('u')
                    ->innerJoin('u.posts', 'mp')
                    ->groupBy('u')
                    ->having('count(mp) > 5');
    }
}
