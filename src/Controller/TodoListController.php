<?php

namespace App\Controller;

use App\Entity\TodoList;
use App\Form\TodoListType;
use App\Repository\TodoListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/todo")
 */
class TodoListController extends AbstractController
{
    /**
     * @Route("/", name="todo_list_index", methods={"GET"})
     */
    public function index(TodoListRepository $todoListRepository): Response
    {
        return $this->render('todo_list/index.html.twig', [
            'todo_lists' => $todoListRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="todo_list_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $todoList = new TodoList();
        $form = $this->createForm(TodoListType::class, $todoList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($todoList);
            $entityManager->flush();

            return $this->redirectToRoute('todo_list_index');
        }

        return $this->render('todo_list/new.html.twig', [
            'todo_list' => $todoList,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="todo_list_show", methods={"GET"})
     */
    public function show(TodoList $todoList): Response
    {
        return $this->render('todo_list/show.html.twig', [
            'todo_list' => $todoList,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="todo_list_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TodoList $todoList): Response
    {
        $form = $this->createForm(TodoListType::class, $todoList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('todo_list_index');
        }

        return $this->render('todo_list/edit.html.twig', [
            'todo_list' => $todoList,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="todo_list_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TodoList $todoList): Response
    {
        if ($this->isCsrfTokenValid('delete'.$todoList->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($todoList);
            $entityManager->flush();
        }

        return $this->redirectToRoute('todo_list_index');
    }
}
