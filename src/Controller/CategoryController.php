<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'categories')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        return $this->render('category/categories.html.twig', [
            'categories' => $categoriesRepository->findAll(),
        ]);
    }

    #[Route('/add', name: 'add-category')]
    public function add(Request $request, CategoriesRepository $categoriesRepository): Response
    {
        $category = new Categories();
        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Añadimos la categoría
            $categoriesRepository->add($category);
            // Una vez terminado, redirigimos de vuelta a la lista de categorías
            return $this->redirectToRoute('categories');
        }

        return $this->renderForm('category/add_category.html.twig', [
            'category' => $category,
            'categoryForm' => $form,
        ]);
    }


    #[Route('/edit/{id}', name: 'edit-category')]
    public function edit(Request $request, Categories $category, CategoriesRepository $categoriesRepository): Response
    {
        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoriesRepository->add($category);
            return $this->redirectToRoute('categories', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('category/edit_category.html.twig', [
            'category' => $category,
            'categoryForm' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_category_delete')]
    public function delete(Request $request, Categories $category, CategoriesRepository $categoriesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $categoriesRepository->remove($category);
        }

        return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
