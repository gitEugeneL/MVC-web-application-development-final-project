<?php

namespace App\Service;

use App\Dto\CreateManagerDto;
use App\Dto\GetManagerDto;
use App\Entity\Manager;
use App\Entity\User;
use App\Exception\ApiException;
use App\Mapper\ManagerMapper;
use App\Repository\ManagerRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;


readonly class ManagerService
{
    private UserRepository $userRepository;
    private ManagerRepository $managerRepository;
    private ApiException $apiException;
    private ManagerMapper $managerMapper;
    public function __construct(UserRepository $userRepository, ManagerRepository $managerRepository,
                                ManagerMapper $managerMapper, ApiException $apiException)
    {
        $this->userRepository = $userRepository;
        $this->managerRepository = $managerRepository;
        $this->apiException = $apiException;
        $this->managerMapper = $managerMapper;
    }

    public function createManager(CreateManagerDto $dto): GetManagerDto
    {
        if ($this->userRepository->findOneBy(['email' => $dto->getEmail()]) !== null)
        {
            $this->apiException->exception("This Manager already exist", 422);
        }
        $manager = (new Manager())
            ->setFirstName($dto->getFirstName())
            ->setLastName($dto->getLastName())
            ->setPhone($dto->getPhone())
            ->setAuthUser((new User())
                ->setEmail($dto->getEmail())
                ->setPassword(password_hash($dto->getPassword(), PASSWORD_DEFAULT))
                ->setRoles(["ROLE_MANAGER"]));

        $this->managerRepository->save($manager, true);
        return $this->managerMapper->createManagerDto($manager);
    }


    public function getAuthManager(UserInterface $authUser): GetManagerDto
    {
        $manager = $this->managerRepository
            ->findOneBy(["authUser" => $this->userRepository
                ->findOneBy(['email' => $authUser->getUserIdentifier()])]);

        if (!$manager)
            $this->apiException->exception("This Manager doesn't exist", 422);

        return $this->managerMapper->createManagerDto($manager);
    }
}