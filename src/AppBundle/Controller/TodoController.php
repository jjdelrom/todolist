<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Todo;

class TodoController extends Controller
{

    public function listTaskAction(Request $request)
    {
        try{
            $em = $this->getDoctrine()->getManager();
            $rep = $em->getRepository('AppBundle:Todo');
            $tasks = $rep->findAll();

        } catch (Exception $ex) {
            echo 'Excepción capturada: ',  $ex->getMessage(), "\n";
        }
        return $this->render('Todo/list_tasks.html.twig', array('tasks'=>$tasks ));    	
    }	

    public function createTaskAction(Request $request)
    {
        try{
            $task = new Todo();
            $form = $this->createForm(\AppBundle\Form\TodoType::class, $task);
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            $rep = $em->getRepository('AppBundle:Todo');

            if($form->isSubmitted() && $form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($task);
                $em->flush();
                $this->addFlash('success', 'registro.creado.correctamente' );
            }
        }catch (Exception $ex) {
                $this->addFlash('danger', 'error.registro' );
                echo 'Excepción capturada: ',  $ex->getMessage(), "\n";
        }
        $tasks = $rep->findAll();
        return $this->render('Todo/create_task.html.twig', array('form' => $form->createView(), 'tasks'=>$tasks ));

    }

    public function removeTaskAction(Request $request, int $idTask){

        try{
            $m = $this->getDoctrine()->getManager();
            $task = $m->getRepository('AppBundle:Todo')->find($idTask);
            if(!$task){
                throw $this->createNotFoundException('La tarea con id: '.$idTask.' no existe.');
            }

            $m->remove($task);
            $m->flush();
            $this->addFlash('success', 'registro.eliminado.correctamente' );
            return $this->redirectToRoute('list_tasks');
        }
        catch (Exception $ex) {
            $this->addFlash('danger', 'Error al eliminar el registro' );
            echo 'Excepción capturada: ',  $ex->getMessage(), "\n";
        }
    }

    public function editTaskAction(Request $request, int $idTask){

      try{
       $m = $this->getDoctrine()->getManager();
       $task = $m->getRepository('AppBundle:Todo')->find($idTask);

       $form = $this->createForm(\AppBundle\Form\TodoType::class, $task);
       $form->handleRequest($request);
       $rep = $m->getRepository('AppBundle:Todo');

       if($form->isSubmitted() && $form->isValid()){
            $task = $form->getData();
            $man = $this->getDoctrine()->getManager();
            $man->persist($task);
            $man->flush();
            $this->addFlash('success', 'registro.creado.correctamente' );
       }
        $rep = $m->getRepository('AppBundle:Todo');
        $tasks = $rep->findAll();
      }
      catch(Excepcition $ex){
       echo 'Excepción capturada: ',  $ex->getMessage(), "\n";
      }
      return $this->render('Todo/edit_task.html.twig', array('form' => $form->createView(), 'tasks'=>$tasks) );
     }


    public function changeLocaleAction(Request $request)
    {

        $locale = $request->getLocale();
        $request->setLocale($locale);
    
    
        return $this->render('Todo/list_tasks.html.twig', array('tasks'=>$tasks ));     
    }   

}
