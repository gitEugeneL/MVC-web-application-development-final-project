<?php

namespace App\Service;


use App\Dto\CreateManagerDto;
use App\Dto\GetManagerDto;
use App\Entity\Manager;
use App\Entity\User;
use App\Repository\ManagerRepository;
use App\Repository\UserRepository;

class ManagerService
{
    private readonly UserRepository $userRepository;
    private readonly ManagerRepository $managerRepository;
    public function __construct(UserRepository $userRepository, ManagerRepository $managerRepository)
    {
        $this->userRepository = $userRepository;
        $this->managerRepository = $managerRepository;
    }

    public function createManager(CreateManagerDto $dto)
    {
        if ($this->userRepository->findOneBy(['email' => $dto->getEmail()]) !== null) {}
            // todo "This Manager already exist", 422"

        $manager = (new Manager())
            ->setFirstName($dto->getFirstName())
            ->setLastName($dto->getLastName())
            ->setPhone($dto->getPhone())
            ->setAuthUser((new User())
                ->setEmail($dto->getEmail())
                ->setPassword(password_hash($dto->getPassword(), PASSWORD_DEFAULT))
                ->setRoles(["ROLE_MANAGER"]));

        $this->managerRepository->save($manager, true);

        $dto = new GetManagerDto();
        $dto->setId($manager->getId());
        $dto->setFirstName($manager->getFirstName());
        $dto->setLastName($manager->getLastName());
        $dto->setEmail($manager->getAuthUser()->getEmail());
        $dto->setPhone($manager->getPhone());
        return $dto;
    }
}

