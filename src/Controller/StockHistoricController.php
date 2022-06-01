<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\StockHistoric;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StockHistoricController extends AbstractController
{
    #[Route('/stockHistoric/{id}', name: 'stock_historic')]
    public function index(Request $request): Response
    {
        // Recuperamos el id del producto
        $id = $request->get('id');
        // Obtenemos el producto por el id
        $product = $this->getDoctrine()->getRepository(Products::class)->find($id);
        // Obtenemos el histórico a través del producto 
        $stockHistoric = $this->getDoctrine()->getRepository(StockHistoric::class)->findBy(['product' => $product]);
        
        return $this->render('stock_historic/historic.html.twig', [
            'stockHistoric' => $stockHistoric
        ]);
    }
}
