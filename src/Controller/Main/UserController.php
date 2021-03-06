<?php

namespace App\Controller\Main;

use App\Controller\CustomAbstractController;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends CustomAbstractController
{
    #[Route(
        '/',
        name: 'app_user_index',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Users | Employees list page",
            "role" => "superadmin"
        ],
        methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route(
        '/new',
        name: 'app_user_new',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Create new user | employee",
            "role" => "superadmin"
        ],
        methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $userPasswordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            if (count($user->getRolesObj()) >= 1) {
                $userRepository->add($user);
                $this->addFlash('success', 'New user added successfully');
            } else {
                $this->addFlash('error', 'User not added: At least one Role should be present');
            }
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route(
        '/{id}',
        name: 'app_user_show',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Detailed info for a given User",
            "role" => "superadmin"
        ],
        methods: ['GET']
    )]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route(
        '/{id}/edit',
        name: 'app_user_edit',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Edit given User",
            "role" => "superadmin"
        ],
        methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$user->getManager()) {
                $hashedPassword = $userPasswordHasher->hashPassword($user, $user->getPassword());
                $user->setPassword($hashedPassword);
                if (count($user->getRolesObj()) >= 1) {
                    $userRepository->add($user);
                    $this->addFlash('success', 'User ' . $user->getId() . ' edited successfully');
                } else {
                    $this->addFlash('error', 'User not edited: At least one Role should be present');
                }
                $userRepository->add($user);
                return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
            } else {
                $this->addFlash('error', 'Users of type \'manager\' can\'t be edited or removed');
            }
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route(
        '/{id}',
        name: 'app_user_delete',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Delete Given User",
            "role" => "superadmin"
        ],
        methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            if (!$user->getManager()) {
                $userRepository->remove($user);
                $this->addFlash('success', 'User ' . $user->getId() . ' removed ');
            } else {
                $this->addFlash('error', 'Users of type \'manager\' can\'t be edited or removed');
            }
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
