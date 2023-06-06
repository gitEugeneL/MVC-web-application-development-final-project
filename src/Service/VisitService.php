<?php

namespace App\Service;

use App\Entity\Visit;
use App\Exception\ApiException;
use App\Repository\DoctorRepository;
use App\Repository\PatientRepository;
use App\Repository\UserRepository;
use App\Repository\VisitRepository;
use App\Visit\CreateVisitDto;
use App\Visit\FindFreeTimeDto;
use App\Visit\GetVisitDto;
use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;

class VisitService
{
    private VisitRepository $visitRepository;
    private DoctorRepository $doctorRepository;
    private PatientRepository $patientRepository;
    private UserRepository $userRepository;
    private ApiException $apiException;
    public function __construct(VisitRepository $visitRepository, DoctorRepository $doctorRepository,
                                PatientRepository $patientRepository, UserRepository $userRepository,
                                ApiException $apiException)
    {
        $this->userRepository = $userRepository;
        $this->patientRepository = $patientRepository;
        $this->doctorRepository = $doctorRepository;
        $this->visitRepository = $visitRepository;
        $this->apiException = $apiException;
    }


    public function findFreeDayTime(FindFreeTimeDto $dto): array
    {
        $doctor = $this->doctorRepository->find($dto->getDoctorId());
        if(!$doctor)
            $this->apiException->exception("This Doctor doesn't exist", 422);

        $day = $dto->getDay();
        if ($day <= new \DateTimeImmutable())
            $this->apiException->exception("Invalid day. Please select a future date.", 422);

        return $this->visitRepository->findDoctorTime($day, $doctor);
    }


    public function createVisit(UserInterface $authUser, CreateVisitDto $dto): GetVisitDto
    {
        $patient = $this->patientRepository->findOneBy(["authUser" => $this->userRepository
            ->findOneBy(['email' => $authUser->getUserIdentifier()])]);
        if(!$patient)
            $this->apiException->exception("This Patient doesn't exist", 422);

        $doctor = $this->doctorRepository->findOneBy(["id" => $dto->getDoctorId()]);
        if(!$doctor)
            $this->apiException->exception("This Doctor doesn't exist", 422);

        $day = $dto->getDay();
        if ($day <= new \DateTimeImmutable())
            $this->apiException->exception("Invalid day. Please select a future date.", 422);

        $startTime = $dto->getStartTime();

        $allowedTimes = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];
        if (!in_array($startTime->format('H:i'), $allowedTimes))
            $this->apiException->exception("This time is not valid (08:00 - 18:00)", 422);

        $visit = $this->visitRepository->findBy(["date" => $day, "startTime" => $startTime, "doctor" => $doctor->getId()]);
        if (count($visit) > 0)
            $this->apiException->exception("the Doctor is busy at this time", 422);

        $endTime = $startTime->modify('+1 hour');

        $visit = new Visit();
        $visit->setDate($day);
        $visit->setStartTime($startTime);
        $visit->setEndTime($endTime);
        $visit->setPatient($patient);
        $visit->setDoctor($doctor);

        $this->visitRepository->save($visit, true);

        return $this->createGetVisitDto($visit);
    }


    public function showVisitsByPatient(UserInterface $authPatient, bool $past = false): array
    {
        $patient = $this->patientRepository->findOneBy(["authUser" => $this->userRepository
            ->findOneBy(['email' => $authPatient->getUserIdentifier()])]);
        if(!$patient)
            $this->apiException->exception("This Patient doesn't exist", 422);

        $visits = $this->visitRepository->findBy(['patient' => $patient]);
        return $this->findVisits($visits, $past);
    }


    public function showVisitsByDoctor(UserInterface $authDoctor, bool $past = false): array
    {
        $doctor = $this->doctorRepository->findOneBy(["authUser" => $this->userRepository
            ->findOneBy(['email' => $authDoctor->getUserIdentifier()])]);

        if(!$doctor)
            $this->apiException->exception("This Doctor doesn't exist", 422);

        $visits = $this->visitRepository->findBy(['doctor' => $doctor]);
        return $this->findVisits($visits, $past);
    }


    public function showVisits(bool $past = false): array
    {
        $visits = $this->visitRepository->findAll();
        return $this->findVisits($visits, $past);
    }


    private function findVisits(array $visits, bool $past): array
    {
        $futureVisits = array_filter($visits, function ($visit) use ($past) {
            $today = new DateTime();
            return $past ? ($visit->getDate() < $today) : ($visit->getDate() >= $today);
        });

        $visitsDTOs = [];
        foreach ($futureVisits as $visit)
            $visitsDTOs[] = $this->createGetVisitDto($visit);

        return $visitsDTOs;
    }


    private function createGetVisitDto(Visit $visit): GetVisitDto
    {
        $day = $visit->getDate()->format('Y-m-d');
        $startTIme = $visit->getStartTime()->format('H:i');
        $endTime = $visit->getEndTime()->format('H:i');

        $dto = new GetVisitDto();
        $dto->setId($visit->getId());
        $dto->setDay($day);
        $dto->setStartTime($startTIme);
        $dto->setEndTime($endTime);
        $dto->setPatientId($visit->getPatient()->getId());
        $dto->setDoctorId($visit->getDoctor()->getId());
        return $dto;
    }
}