<?php

namespace App\Repository;

use App\Entity\DigitalPurchase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DigitalPurchase>
 *
 * @method DigitalPurchase|null find($id, $lockMode = null, $lockVersion = null)
 * @method DigitalPurchase|null findOneBy(array $criteria, array $orderBy = null)
 * @method DigitalPurchase[]    findAll()
 * @method DigitalPurchase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DigitalPurchaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DigitalPurchase::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(DigitalPurchase $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(DigitalPurchase $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return DigitalPurchase[] Returns an array of DigitalPurchase objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DigitalPurchase
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
