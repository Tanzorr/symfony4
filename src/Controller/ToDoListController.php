<?php

namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Task;

class ToDoListController extends AbstractController
{
    /**
     * @Route("/to/do/list", name="to_do_list")
     */
    public function index()
    {
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findBy([], ['id' => 'DESC']);
        return $this->render('to_do_list/index.html.twig', [
            'controller_name' => 'ToDoListController',
            'tasks' => $tasks
        ]);
    }

    /**
     * @Route("/create", name="create_task", methods={"POST"})
     */
    public function create(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = new Task();
        $title = trim($request->request->get('title'));
        if (empty($title)) {
            return $this->redirectToRoute('to_do_list');
        }else{
            $task->setTitle($title);
            $entityManager->persist($task);
            $entityManager->flush();
            return $this->redirectToRoute('to_do_list');
        }

    }

    /**
     * @Route("/switch-status/{id}", name="switch_status")
     */
    public function switchStatus($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);
        $task->setStatus(! $task->getStatus());
        $entityManager->flush();
        return $this->redirectToRoute('to_do_list');
    }

    /**
     * @Route("/delete/{id}", name="task_delete")
     */
    public function delete (Task $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove( $id);
        $entityManager->flush();
        return $this->redirectToRoute('to_do_list');
    }
}
