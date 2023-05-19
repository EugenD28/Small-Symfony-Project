<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    #[Route('/users/index', name: 'index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        $usersData = array();

        foreach ($users as $user) {
            $usersData[] = array(
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getName(),
                'countOfProject' => count($user->getProjects())
            );
        }

        return $this->render('user/index.html.twig', [
            'users' => $usersData,
        ]);
    }

    #[Route('/users/{id}', name: 'user', methods: ['GET'])]
    public function getUserInfo($id, UserRepository $userRepository)
    {
        $user = $userRepository->find($id);

        $userData = array(
            'id' => $user->getId(),
            'name' => $user->getName(),
            'projects' => $user->getProjects()
        );

        return $this->render('/user/userinfo.html.twig', [
            'user' => $userData,
        ]);
    }

    #[Route('/users/create', name: 'create_user', methods: ['POST'])]
    public function create(Request $request,ValidatorInterface $validator, UserRepository $userRepository): JsonResponse
    {
        $body = json_decode($request->getContent(), true);

        $user = new User();
        $user->setFirstName($body['firstName']);
        $user->setLastName($body['lastName']);
        $user->setEmail($body['email']);
        $user->setEnabled($body['enabled']);
        $user->setStatus($body['status']);

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }

        $userRepository->save($user, true);

        $data = $userRepository->find($user->getId());

        $user = array(
            'id' => $data->getId(),
            'first_name' => $data->getFirstName(),
            'last_name' => $data->getLastName(),
            'email' => $data->getEmail(),
            'enabled' => $data->isEnabled(),
            'status' => $data->getStatus()
        );

        return new JsonResponse($user);
    }

    #[Route('/users', name: 'get_users', methods: ['GET'])]
    public function getUsers(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();

        if (!$users) {
            throw $this->createNotFoundException(
                'No users found'
            );
        }

        $userCollection = array();

        foreach($users as $user) {
            $userCollection[] = array(
                'id' => $user->getId(),
                'first_name' => $user->getFirstName(),
                'last_name' => $user->getLastName(),
                'email' => $user->getEmail(),
                'enabled' => $user->isEnabled(),
                'status' => $user->getStatus()
            );
        }

        return new JsonResponse($userCollection);
    }

    #[Route('/users/{id}', name: 'delete_user', methods: ['DELETE'])]
    public function deleteUser(User $user, UserRepository $userRepository): Response
    {
        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$user->getId()
            );
        }
        
        $userRepository->remove($user, true);

        return new Response('User was deleted');
    }
}
