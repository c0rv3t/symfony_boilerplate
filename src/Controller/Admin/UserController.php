<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Security\Voter\UserVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/admin/users', name: 'admin_users')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted(UserVoter::INDEX, User::class)) {
            $this->addFlash('error', 'You do not have permission to access this page.');
            return $this->redirectToRoute('home');
        }

        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/users/create', name: 'admin_users_create')]
    public function create(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        if (!$this->isGranted(UserVoter::ADD, User::class)) {
            $this->addFlash('error', 'You do not have permission to create a user.');
            return $this->redirectToRoute('admin_users');
        }

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $roles = $user->getRoles();

            if (in_array('ROLE_ADMIN', $roles)) {
                if (!in_array('ROLE_MANAGER', $roles)) {
                    $roles[] = 'ROLE_MANAGER';
                }
                if (!in_array('ROLE_USER', $roles)) {
                    $roles[] = 'ROLE_USER';
                }
            } elseif (in_array('ROLE_MANAGER', $roles)) {
                if (!in_array('ROLE_USER', $roles)) {
                    $roles[] = 'ROLE_USER';
                }
            }
            $user->setRoles($roles);

            $hashedPassword = $passwordHasher->hashPassword($user, $form->get('password')->getData());
            $user->setPassword($hashedPassword);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'The user was successfully added.');

            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/user/form.html.twig', [
            'form' => $form,
            'is_editing' => false,
        ]);
    }

        #[Route('/admin/users/edit/{id}', name: 'admin_users_edit')]
    public function edit(User $user, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        if (!$this->isGranted(UserVoter::EDIT, $user)) {
            $this->addFlash('error', 'You do not have permission to edit a user.');
            return $this->redirectToRoute('admin_users');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $roles = $user->getRoles();
        
            if (in_array('ROLE_ADMIN', $roles)) {
                if (!in_array('ROLE_MANAGER', $roles)) {
                    $roles[] = 'ROLE_MANAGER';
                }
                if (!in_array('ROLE_USER', $roles)) {
                    $roles[] = 'ROLE_USER';
                }
            } elseif (in_array('ROLE_MANAGER', $roles)) {
                if (!in_array('ROLE_USER', $roles)) {
                    $roles[] = 'ROLE_USER';
                }
            }
            $user->setRoles($roles);

            if ($user->getId() === null) {
                $hashedPassword = $passwordHasher->hashPassword($user, $form->get('password')->getData());
                $user->setPassword($hashedPassword);
            }

            $entityManager->flush();

            $this->addFlash('success', 'The user was successfully edited.');

            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/user/form.html.twig', [
            'form' => $form,
            'is_editing' => true,
        ]);
    }

    #[Route('/admin/users/delete/{id}', name: 'admin_users_delete')]
    public function delete(User $user, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted(UserVoter::DELETE, $user)) {
            $this->addFlash('error', 'You do not have permission to delete a user.');
            return $this->redirectToRoute('admin_users');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'The user was successfully deleted.');

        return $this->redirectToRoute('admin_users');
    }
}
