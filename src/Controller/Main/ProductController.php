<?php

namespace App\Controller\Main;

use App\Controller\CustomAbstractController;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product')]
class ProductController extends CustomAbstractController
{
    #[Route(
        '/',
        name: 'app_product_index',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "All products",
            "role" => "user"
        ],
        methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('main/product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/new',
        name: 'app_product_new',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Create new product",
            "role" => "superadmin"
        ],
        methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository $productRepository): Response
    {
        /*$product = (new Product())
                        ->setProperties([
                            ['key' => 'Key', 'value' => 'Value'],
                            ['key' => 'Key', 'value' => 'Value'],
                            ['key' => 'Key', 'value' => 'Value']
                            ]);*/
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->add($product);
            $this->addFlash('success', 'Product added successfully');
            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('main/product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}',
        name: 'app_product_show',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Detailed info for given product",
            "role" => "user"
        ],
        methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('main/product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route(
        '/{id}/edit',
        name: 'app_product_edit',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Edit given product",
            "role" => "superadmin"
        ],
        methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->add($product);
            $this->addFlash('success', 'Product edited successfully');
            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('main/product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route(
        '/{id}',
        name: 'app_product_delete',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Delete given product",
            "role" => "superadmin"
        ],
        methods: ['POST'])
    ]
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product);
            $this->addFlash('success', 'Product removed successfully');
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }
}
