<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use AppBundle\Form\TodoType;
use AppBundle\Entity\Todo;
use Symfony\Component\HttpFoundation\JsonResponse;

class TodoController extends Controller{
    /**
     * @Route("/todo", name="todo.list")
     */
    public function index(Request $request){
        
        $repository = $this->getDoctrine()->getRepository(Todo::class);
        $todos = $repository->orderBy( 'updatedAt', 'ASC' );

        return $this->render('default/todo/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'todos' => $todos,
        ]);
    }

    /**
     * @Route("/export", name="todo.export")
     */
    public function export(Request $request){
        
        $repository = $this->getDoctrine()->getRepository(Todo::class);
        $todos = $repository->orderBy( 'updatedAt', 'ASC', 'array' );


        /*
        // First solution
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        return new Response( $serializer->serialize( $todos, 'json') );
        */

       
        return new JsonResponse( $todos );
    }

    /**
     * @Route("/todo/create", name="todo.create")
     */
    public function create(Request $request){
        
        $todo = new Todo();
        //---$form = $this->get('form.factory')->createNamed( 'todo', TodoType::class, $todo );
        $form = $this->createForm(TodoType::class, $todo );

        $form
            ->add( 'create',  SubmitType::class, [
               'label' => 'Create new todo'
            ]);
        
        //--- match form with request
        $form->handleRequest( $request );
        
        if( $request->isMethod( 'post' ) ){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist( $todo );
            $entityManager->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add( 'success', 'New todo saved with success !!!');
            $session->set( 'statut', 'success' );

            return $this->redirect( $this->generateUrl('todo.list'));

            //return new JsonResponse( $request->request->all() );
        }

        return $this->render('default/todo/edit.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'form_todo' => $form->createView(),
        ]);
    }

    /**
     * @Route("/todo/edit/{id}", name="todo.edit")
     */
    public function edit(Request $request, $id ){
        $repository = $this->getDoctrine()->getRepository(Todo::class);
        $todo = $repository->find( $id );

        //---$form = $this->get('form.factory')->createNamed( 'todo', TodoType::class, $todo );
        $form = $this->createForm(TodoType::class, $todo );

        $form
            // no :( ->setMethod('PUT')
            ->add( 'edit',  SubmitType::class, [
               'label' => 'Save edit todo'
            ]);
        
        //--- match form with request
        $form->handleRequest( $request );
        
        if( $request->isMethod( 'post' ) ){
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist( $todo );
            $entityManager->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add( 'success', 'Todo updated with success !!!');
            $session->set( 'statut', 'success' );

            return $this->redirect( $this->generateUrl('todo.list'));

            //return new JsonResponse( $request->request->all() );
        }

        return $this->render('default/todo/edit.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'form_todo' => $form->createView(),
        ]);
    }


     /**
     * @Route("/todo/delete/{id}", name="todo.delete")
     */
    public function delete(Request $request, $id ){
        $repository = $this->getDoctrine()->getRepository(Todo::class);
        $todo = $repository->find( $id );
        
        if( $todo ){
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove( $todo );
            $entityManager->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add( 'success', 'Todo deleted with success !!!');
            $session->set( 'statut', 'success' );

            return $this->redirect( $this->generateUrl('todo.list'));

            //return new JsonResponse( $request->request->all() );
        }

        $session = $request->getSession();
        $session->getFlashBag()->add( 'danger', 'Error:: can not delete todo !!!');
        $session->set( 'statut', 'danger' );
        return $this->redirect( $this->generateUrl('todo.list'));

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
