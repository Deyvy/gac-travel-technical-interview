<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductsType;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/products')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'products')]
    public function index(ProductsRepository $productsRepository): Response
    {
        return $this->render('product/products.html.twig', [
            'products' => $productsRepository->findAll(),
        ]);
    }

    #[Route('/add', name: 'add-product')]
    public function add(Request $request, ProductsRepository $productsRepository): Response
    {
        $product = new Products();
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // añadimos el producto
            $productsRepository->add($product);
            // Una vez terminado, redirigimos de vuelta a la lista de productos
            return $this->redirectToRoute('products');
        }

        return $this->render('product/add_product.html.twig', [
            'productForm' => $form->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit-product')]
    public function edit(Request $request, Products $product, ProductsRepository $productsRepository): Response
    {
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productsRepository->add($product);
            return $this->redirectToRoute('products');
        }

        return $this->renderForm('product/edit_product.html.twig', [
            'product' => $product,
            'productForm' => $form,
        ]);
    }


}
