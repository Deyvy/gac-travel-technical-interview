<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'products')]
    public function index(): Response
    {
        return $this->render('product/products.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }
}
