<?php

namespace App\Controller\Main;

use App\Controller\CustomAbstractController;
use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/customer')]
class CustomerController extends CustomAbstractController
{
    #[Route(
        '/',
        name: 'app_customer_index',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "List of all customers",
            "role" => "user"
        ],
        methods: ['GET'])]
    public function index(CustomerRepository $customerRepository): Response
    {
        return $this->render('/main/customer/index.html.twig', [
            'customers' => $customerRepository->findAll(),
        ]);
    }

    #[Route('/new',
        name: 'app_customer_new',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Create new customer",
            "role" => "user"
        ],
        methods: ['GET', 'POST'])]
    public function new(Request $request, CustomerRepository $customerRepository): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customerRepository->add($customer);
            $this->addFlash('success', 'Customer added successfully');
            return $this->redirectToRoute('app_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('/main/customer/new.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }

    #[Route(
        '/{id}',
        name: 'app_customer_show',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Detailed info about given customer",
            "role" => "superadmin"
        ],
        methods: ['GET'])]
    public function show(Customer $customer): Response
    {
        return $this->render('/main/customer/show.html.twig', [
            'customer' => $customer,
        ]);
    }

    #[Route(
        '/{id}/edit',
        name: 'app_customer_edit',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Edit given customer",
            "role" => "user"
        ],
        methods: ['GET', 'POST'])]
    public function edit(Request $request, Customer $customer, CustomerRepository $customerRepository): Response
    {
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customerRepository->add($customer);
            $this->addFlash('success', 'Customer edited successfully');
            return $this->redirectToRoute('app_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('/main/customer/edit.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }

    #[Route(
        '/{id}',
        name: 'app_customer_delete',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Delete given customer",
            "role" => "superadmin"
        ],
        methods: ['POST'])]
    public function delete(Request $request, Customer $customer, CustomerRepository $customerRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $customer->getId(), $request->request->get('_token'))) {
            $customerRepository->remove($customer);
            $this->addFlash('success', 'Customer removed');
        }

        return $this->redirectToRoute('app_customer_index', [], Response::HTTP_SEE_OTHER);
    }
}
