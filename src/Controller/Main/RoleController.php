<?php

namespace App\Controller\Main;

use App\Controller\CustomAbstractController;
use App\Entity\Role;
use App\Form\CloneRoleType;
use App\Form\RoleType;
use App\Repository\RoleRepository;
use App\Service\RoleService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/role')]
class RoleController extends CustomAbstractController
{
    #[Route(
        '/',
        name: 'app_role_index',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Roles list page",
            "role" => "superadmin"
        ],
        methods: ['GET'])]
    public function index(RoleRepository $roleRepository): Response
    {
        return $this->render('role/index.html.twig', [
            'roles' => $roleRepository->findAll(),
        ]);
    }

    #[Route(
        '/new',
        name: 'app_role_new',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Create new role",
            "role" => "superadmin"
        ],
        methods: ['GET', 'POST'])]
    public function new(Request $request, RoleRepository $roleRepository, RoleService $roleService): Response
    {
        $role = new Role();
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $roleRepository->add($role);
            $this->addFlash('success', 'Role created');
            return $this->redirectToRoute('app_role_index', [], Response::HTTP_SEE_OTHER);
        }

        $cloneRoleForm = $this->createForm(CloneRoleType::class);
        $cloneRoleForm->handleRequest($request);

        if ($cloneRoleForm->isSubmitted() && $cloneRoleForm->isValid()) {
            $data = $cloneRoleForm->getData();
            if ($roleService->cloneRole($data['roles'], $data['role']))
                $this->addFlash('success', 'Role cloned');
            else
                $this->addFlash('error', 'Failed to clone role');
            return $this->redirectToRoute('app_role_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('role/new.html.twig', [
            'role' => $role,
            'form' => $form,
            'form2' => $cloneRoleForm
        ]);
    }

    #[Route(
        '/{id}',
        name: 'app_role_show',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Detailed info about given role",
            "role" => "superadmin"
        ],
        methods: ['GET'])]
    public function show(Role $role): Response
    {
        return $this->render('role/show.html.twig', [
            'role' => $role,
        ]);
    }

    #[Route(
        '/{id}/edit',
        name: 'app_role_edit',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Edit Given Role",
            "role" => "superadmin"
        ],
        methods: ['GET', 'POST'])]
    public function edit(Request $request, Role $role, RoleRepository $roleRepository): Response
    {
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $roleRepository->add($role);
            return $this->redirectToRoute('app_role_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('role/edit.html.twig', [
            'role' => $role,
            'form' => $form,
        ]);
    }

    #[Route(
        '/{id}',
        name: 'app_role_delete',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Delete Given Role",
            "role" => "superadmin"
        ],
        methods: ['POST'])]
    public function delete(Request $request, Role $role, RoleRepository $roleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $role->getId(), $request->request->get('_token'))) {
            $roleRepository->remove($role);
        }

        return $this->redirectToRoute('app_role_index', [], Response::HTTP_SEE_OTHER);
    }
}
