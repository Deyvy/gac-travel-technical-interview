<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CreateUserType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/users')]
class UserController extends AbstractController
{
    #[Route('/', name: 'users')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/users.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/add', name: 'add-user')]
    public function add(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(CreateUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hacemos encode de la contraseña
            $password = $userPasswordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            );
            // Y la seteamos y guardamos encodeada
            $userRepository->upgradePassword($user, $password);
            $userRepository->add($user);
            return $this->redirectToRoute('users');
        }

        return $this->renderForm('user/add_user.html.twig', [
            'user' => $user,
            'addUserForm' => $form,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit-user')]
    public function edit(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(CreateUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hacemos encode de la contraseña
            $password = $userPasswordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            );
            // Y la seteamos y guardamos encodeada
            $userRepository->upgradePassword($user, $password);
            $userRepository->add($user);
            return $this->redirectToRoute('users', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit_user.html.twig', [
            'user' => $user,
            'editUserForm' => $form,
        ]);
    }
}
