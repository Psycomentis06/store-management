<?php

namespace App\Repository;

use App\Entity\Schedule;
use App\Entity\WorkEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @extends ServiceEntityRepository<WorkEvent>
 *
 * @method WorkEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkEvent[]    findAll()
 * @method WorkEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkEvent::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(WorkEvent $entity, bool $flush = true): void
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
    public function remove(WorkEvent $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return WorkEvent[] Returns an array of WorkEvent objects
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
    public function findOneBySomeField($value): ?WorkEvent
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByWeek(\DateTime $date)
    {
        return $this->createQueryBuilder('we')
            ->where('YEARWEEK(we.fromDate) = YEARWEEK(:date)')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_OBJECT);
    }

    public function findByCurrenctWeek()
    {
        return $this->createQueryBuilder('we')
            ->where('YEARWEEK(we.fromDate) = YEARWEEK(CURDATE())')
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_OBJECT);
    }

    public function findByWeekAndSchedule(\DateTime $date, Schedule $schedule)
    {
        return $this->createQueryBuilder('we')
            ->join('we.schedules', 's', 'WITH', 's.id = :schedule')
            ->setParameter('schedule', $schedule->getId())
            ->where('YEARWEEK(we.fromDate) = YEARWEEK(:date)')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_OBJECT);
    }

    public function findByCurrentWeekAndSchedule(Schedule $schedule)
    {
        return $this->createQueryBuilder('we')
            ->join('we.schedules', 's', 'WITH', 's.id = :schedule')
            ->setParameter('schedule', $schedule->getId())
            ->where('YEARWEEK(we.fromDate) = YEARWEEK(:curdate)')
            ->setParameter('curdate', (new \DateTime())->format('Y-m-d'))
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_OBJECT);
    }
}
