<?php

namespace App\Repository;

use App\Entity\Doctor;
use App\Entity\Visit;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Visit>
 *
 * @method Visit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visit[]    findAll()
 * @method Visit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visit::class);
    }

    public function save(Visit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Visit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findDoctorTime(\DateTimeInterface $date, Doctor $doctor): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('t')
            ->from('App\Entity\Visit', 't')
            ->where('t.doctor = :doctor')
            ->andWhere('t.date = :date')
            ->setParameter('doctor', $doctor)
            ->setParameter('date', $date->format('Y-m-d'));

        $visits = $qb->getQuery()->getResult();

        $startDateTime = \DateTime::createFromFormat('H:i', '08:00');
        $endDateTime = \DateTime::createFromFormat('H:i', '19:00');
        $interval = new \DateInterval('PT1H');

        $busyTime = [];
        foreach ($visits as $visit) {
            $startTime = $visit->getStartTime();
            $endTime = $visit->getEndTime();
            $currentTime = clone $startTime;

            while ($currentTime < $endTime) {
                $busyTime[$currentTime->format('H:i')] = true;
                $currentTime->add($interval);
            }
        }

        $freeTimeSegments = [];
        $currentTime = clone $startDateTime;

        while ($currentTime < $endDateTime) {
            $currentTimeStr = $currentTime->format('H:i');

            if (!isset($busyTime[$currentTimeStr])) {
                $freeTimeSegments[] = [
                    'start' => $currentTimeStr,
                    'end' => $currentTime->add($interval)->format('H:i')
                ];
            }
            $currentTime->add($interval);
        }
        return $freeTimeSegments;
    }


//    /**
//     * @return Visit[] Returns an array of Visit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Visit
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
