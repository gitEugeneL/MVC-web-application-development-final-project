<?php

namespace App\Controller;

use App\Entity\Manager;
use App\Entity\User;
use App\Repository\ManagerRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route('/manager')]
class ManagerController extends AbstractController
{
    private readonly UserRepository $userRepository;
    private readonly ManagerRepository $managerRepository;
    private readonly ValidatorInterface $validatorInterface;
    public function __construct(UserRepository $userRepository, ManagerRepository $managerRepository,
                                ValidatorInterface $validatorInterface)
    {
        $this->userRepository = $userRepository;
        $this->managerRepository = $managerRepository;
        $this->validatorInterface = $validatorInterface;
    }


    #[Route('/create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($this->userRepository->findOneBy(['email' => $data['email']]) !== null)
            return $this->json("This Manager already exist", 422);
        if (strlen($data['password']) < 4)
            return $this->json("Password isn't valid", 422);

        $manager = (new Manager())
            ->setFirstName($data['firstName'])
            ->setLastName($data['lastName'])
            ->setPhone($data['phone'])
            ->setAuthUser((new User())
                    ->setEmail($data['email'])
                    ->setPassword(password_hash($data['password'], PASSWORD_DEFAULT))
                    ->setRoles(["ROLE_MANAGER"]));

        $errors = $this->validatorInterface->validate($manager);
        if (count($errors) > 0)
            return $this->json($errors, 422);

        $this->managerRepository->save($manager, true);

        $responseData = [
            'id' => $manager->getId(),
            'firstName' => $manager->getFirstName(),
            'lastName' => $manager->getLastName(),
            'email' => $manager->getAuthUser()->getEmail(),
            'phone' => $manager->getPhone()
        ];
        return $this->json($responseData, 201);
    }
}

