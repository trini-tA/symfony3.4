<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Todo;
use Doctrine\ORM\EntityManagerInterface;

class TodoController extends Controller{
    /**
     * @Route("/todo", name="todo.list")
     */
    public function index(Request $request){
        
        $repository = $this->getDoctrine()->getRepository(Todo::class);
        $todos = $repository->findAll();

        return $this->render('default/todo/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'todos' => $todos,
        ]);
    }

    /**
     * @Route("/todo/create", name="todo.create")
     */
    public function create(Request $request)
    {
        return $this->render('default/todo/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }


    /**
     * @Route("/todo/migrations", name="todo.migrations")
     */
    public function migrations(Request $request){
        
        $entityManager = $this->getDoctrine()->getManager();

        $todo = new Todo();
        $todo->setTitle( 'First Todo' );

        $entityManager->persist( $todo );
        $entityManager->flush();

        return new Response('Saved new todo with id ' . $todo->getId());  
    }
}
