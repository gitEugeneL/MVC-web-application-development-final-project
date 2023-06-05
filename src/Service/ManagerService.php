<?php

namespace App\Service;

use App\Dto\CreateManagerDto;
use App\Dto\GetManagerDto;
use App\Entity\Manager;
use App\Entity\User;
use App\Exception\ApiException;
use App\Repository\ManagerRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;


readonly class ManagerService
{
    private UserRepository $userRepository;
    private ManagerRepository $managerRepository;
    private ApiException $apiException;
    public function __construct(UserRepository $userRepository, ManagerRepository $managerRepository,
                                ApiException $apiException)
    {
        $this->userRepository = $userRepository;
        $this->managerRepository = $managerRepository;
        $this->apiException = $apiException;
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


        return $this->createManagerDto($manager);
    }


    public function getAuthManager(UserInterface $authUser): GetManagerDto
    {
        $manager = $this->managerRepository
            ->findOneBy(["authUser" => $this->userRepository
                ->findOneBy(['email' => $authUser->getUserIdentifier()])]);

        if (!$manager)
            $this->apiException->exception("This Manager doesn't exist", 422);

        return $this->createManagerDto($manager);
    }


    private function createManagerDto(Manager $manager): GetManagerDto
    {
        $dto = new GetManagerDto();
        $dto->setId($manager->getId());
        $dto->setFirstName($manager->getFirstName());
        $dto->setLastName($manager->getLastName());
        $dto->setPhone($manager->getPhone());
        $dto->setEmail($manager->getAuthUser()->getEmail());
        return $dto;
    }

}