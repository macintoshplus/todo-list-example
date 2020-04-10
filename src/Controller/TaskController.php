<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\TodoList;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/todo/{list}/task")
 */
class TaskController extends AbstractController
{

    /**
     * @Route("/new", name="task_new", methods={"GET","POST"})
     * @IsGranted("LIST_ADD_TASK", subject="list")
     */
    public function new(Request $request, TodoList $list): Response
    {
        $task = new Task();
        $task->setList($list);
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->denyAccessUnlessGranted('TASK_ADD', $task);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('todo_list_show', ['id'=>$list->getId()]);
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'todo_list' => $list,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="task_show", methods={"GET"})
     * @IsGranted("TASK_VIEW", subject="task")
     */
    public function show(Task $task, TodoList $list): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
            'todo_list' => $list,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="task_edit", methods={"GET","POST"})
     * @IsGranted("TASK_EDIT", subject="task")
     */
    public function edit(Request $request, Task $task, TodoList $list): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('todo_list_show', ['id'=>$list->getId()]);
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'todo_list' => $list,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="task_delete", methods={"DELETE"})
     * @IsGranted("TASK_DELETE", subject="task")
     */
    public function delete(Request $request, Task $task, TodoList $list): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('todo_list_show', ['id'=>$list->getId()]);
    }
}
