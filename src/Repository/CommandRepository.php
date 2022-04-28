<?php

namespace App\Repository;

use App\Entity\Command;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Command>
 *
 * @method Command|null find($id, $lockMode = null, $lockVersion = null)
 * @method Command|null findOneBy(array $criteria, array $orderBy = null)
 * @method Command[]    findAll()
 * @method Command[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Command::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Command $entity, bool $flush = true): void
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
    public function remove(Command $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findCommandBetweenDates($minDate, $maxDate)
    {
        return $this->createQueryBuilder('c')
        ->where('c.createdAt > :date_min')
        ->andWhere('c.createdAt < :date_max')
        ->andWhere('c.status = 200 OR c.status = 300 OR c.status = 400 OR c.status = 500')
        ->setParameter('date_min', $minDate)
        ->setParameter('date_max', $maxDate)
        ->getQuery()->getResult();
    }

    public function findBasketBetweenDates($minDate, $maxDate)
    {
        return $this->createQueryBuilder('b')
        ->where('b.createdAt > :date_min')
        ->andWhere('b.createdAt < :date_max')
        ->andWhere('b.status = 100')
        ->setParameter('date_min', $minDate)
        ->setParameter('date_max', $maxDate)
        ->getQuery()->getResult();
    }

    public function findVenteBetweenDates($minDate, $maxDate)
    {
        return $this->createQueryBuilder('v')
        ->where('v.createdAt > :date_min')
        ->andWhere('v.createdAt < :date_max')
        ->andWhere('v.status = 200 OR v.status = 300')
        ->setParameter('date_min', $minDate)
        ->setParameter('date_max', $maxDate) 
        ->getQuery()->getResult();
    }

    public function findConversionBetweenDates($minDate, $maxDate)
    {
        return $this->createQueryBuilder('co')
        ->where('co.createdAt > :date_min')
        ->andWhere('co.createdAt < :date_max')
        ->andWhere('co.status = 100 OR co.status = 200 OR co.status = 300')
        ->setParameter('date_min', $minDate)
        ->setParameter('date_max', $maxDate)
        ->getQuery()->getResult();
    }

    public function findConvPanierBetweenDates($minDate, $maxDate)
    {
        return $this->createQueryBuilder('cp')
        ->where('cp.createdAt > :date_min')
        ->andWhere('cp.createdAt < :date_max')
        ->setParameter('date_min', $minDate)
        ->setParameter('date_max', $maxDate)
        ->getQuery()->getResult();
    }

    public function findValuesBetweenDates($minDate, $maxDate){
        return $this->createQueryBuilder('va')
        ->where('va.createdAt > :date_min')
        ->andWhere('va.createdAt < :date_max')
        ->setParameter('date_min', $minDate)
        ->setParameter('date_max', $maxDate)
        ->getQuery()->getResult();
    }

    public function findRecurrenceNewBetweenDates($minDate, $maxDate){
        return $this->createQueryBuilder('r')
        ->innerJoin('r.user','u')
        ->andWhere('u.createdAt > :date_min')
        ->andWhere('u.createdAt < :date_max')
        ->andWhere('r.status = 200 OR r.status = 300')
        ->setParameter('date_min', $minDate)
        ->setParameter('date_max', $maxDate)
        ->getQuery()->getResult();
    }

   
    // /**
    //  * @return Command[] Returns an array of Command objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Command
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
