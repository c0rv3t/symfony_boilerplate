<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'task_index', methods: ['GET'])]
    public function index(): Response
    {
        $tasks = [];

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route('/tasks/new', name: 'task_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $taskData = $request->request->all();
            return $this->redirectToRoute('task_index');
        }

        return $this->render('task/create.html.twig');
    }

    #[Route('/tasks/{id}/edit', name: 'task_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request): Response
    {
        $task = null;

        if (!$task) {
            throw $this->createNotFoundException("La tâche avec l'id $id n'existe pas.");
        }

        if ($request->isMethod('POST')) {
            $updatedData = $request->request->all();

            return $this->redirectToRoute('task_index');
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/tasks/{id}', name: 'task_view', methods: ['GET'])]
    public function view(int $id): Response
    {
        $task = null;

        if (!$task) {
            throw $this->createNotFoundException("La tâche avec l'id $id n'existe pas.");
        }

        return $this->render('task/view.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/tasks/{id}/delete', name: 'task_delete', methods: ['POST'])]
    public function delete(int $id): Response
    {
        return $this->redirectToRoute('task_index');
    }
}
