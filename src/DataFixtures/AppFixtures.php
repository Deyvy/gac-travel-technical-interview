<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use App\Entity\Products;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AppFixtures extends Fixture
{
    public function __construct(HttpClientInterface $httpClient, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->httpClient = $httpClient;
        $this->userPasswordHasher = $userPasswordHasher;
    }
    
    public function load(ObjectManager $manager): void
    {
        // Atacamos a la API para sacar los usuarios (traemos 5 usuarios para que la prueba no tarde demasiado, y haremos lo mismo con el resto)
        $users = $this->httpClient->request('GET', 'https://fakestoreapi.com/users?limit=5');
        $usersData = json_decode($users->getContent(), true);

        foreach ($usersData as $userData) {
            $user = new User();
            $user->setUsername($userData['username']);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $userData['password']));
            // Aquí ya no seteamos nada más porque se hace en el constructor


            $manager->persist($user);
        }
        // Hacemos un flush por cada parte de la prueba
        $manager->flush();

        // Ataca a la API para sacar las categorías
        $categories = $this->httpClient->request('GET', 'https://fakestoreapi.com/products/categories?limit=5');
        $categoriesData = json_decode($categories->getContent(), true);

        foreach ($categoriesData as $categoryName) {
            $category = new Categories();
            // Para las categorías solo se pasa el dato del nombre
            $category->setName($categoryName);
            // No seteamos nada más porque se hace en el constructor

            $manager->persist($category);
        }
        // Hacemos un flush por cada parte de la prueba
        $manager->flush();

        // Ataca a la API para sacar los productos
        $products = $this->httpClient->request('GET', 'https://fakestoreapi.com/products?limit=5');
        $productsData = json_decode($products->getContent(), true);

        foreach ($productsData as $productData) {
            $product = new Products();
            $product->setName($productData['title']);
            // Para que encuentre la categoría hacemos una búsqueda con el EntityManager por el nombre (que es lo que recibimos de la API)
            $category = $manager->getRepository(Categories::class)->findOneBy(['name' => $productData['category']]);
            $product->setCategory($category);
            // No seteamos nada más porque se hace en el constructor

            $manager->persist($product);
        }

        $manager->flush();
    }
}
