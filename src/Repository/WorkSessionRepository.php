<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\WorkSession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WorkSession>
 *
 * @method WorkSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkSession[]    findAll()
 * @method WorkSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkSession::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(WorkSession $entity, bool $flush = true): void
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
    public function remove(WorkSession $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return WorkSession[] Returns an array of WorkSession objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WorkSession
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findAllByUserAndCurrentTime(User $user, \DateTime $time = null)
    {
        /*return $this->createQueryBuilder('ws')
            ->join('ws.users', 'u', Join::WITH, 'u.id = :user')
            ->setParameter('user', $user)
            ->join('ws.schedule', 'sc')
            ->where(' YEARWEEK(:curdate) between YEARWEEK(ws.fromTime) AND YEARWEEK()');
        */
        if (empty($time))
            $time = new \DateTime();
        return $this->createQueryBuilder('ws')
            ->join('ws.users', 'u', Join::WITH, 'u.id = :user')
            ->setParameter('user', $user->getId())
            ->where(' :time BETWEEN ws.fromTime AND ws.toTime ')
            ->setParameter('time', $time)
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_OBJECT);
    }
}
