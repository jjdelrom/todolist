<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Todo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class TodoController extends Controller
{

    private $todo;
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
        $this->todo = new Todo();
    }         

    public function listTaskAction(Request $request)
    {
        
        try{

            $usuarioLogado = $this->getuser();
            $rep = $this->em->getRepository('AppBundle:Todo');

            if ( in_array('ROLE_ADMIN',$usuarioLogado->getRoles()) ) {
               $tasks = $rep->findAll();
            }else{
                $tasks = $rep->findBy(array('user' => $usuarioLogado->getId()) );
            }

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
            $rep = $this->em->getRepository('AppBundle:Todo');

            if($form->isSubmitted() && $form->isValid()){                
                $idLogado = $this->getuser()->getId();
                $user = $this->em->getRepository('AppBundle:User')->find($idLogado);
                $task->setUser($user);
                $this->em->persist($task);
                $this->em->flush();
                $this->addFlash('success', 'registro.creado.correctamente' );
            }
        }catch (Exception $ex) {
                $this->addFlash('danger', 'error.registro' );
                echo 'Excepción capturada: ',  $ex->getMessage(), "\n";
        }

        $usuarioLogado = $this->getuser();
        if ( in_array('ROLE_ADMIN',$usuarioLogado->getRoles()) ) {
           $tasks = $rep->findAll();
        }else{
            $tasks = $rep->findBy(array('user' => $usuarioLogado->getId()) );
        }

        return $this->render('Todo/create_task.html.twig', array('form' => $form->createView(), 'tasks'=>$tasks ));

    }

    public function removeTaskAction(Request $request, int $idTask){

        try{

            $usuarioLogado = $this->getuser();
            
            $task = $this->em->getRepository('AppBundle:Todo')->find($idTask);
            if(!$task){
                throw $this->createNotFoundException('La tarea con id: '.$idTask.' no existe.');
            }

            if($task->getUser()->getId() == $usuarioLogado->getId() ){
                $this->em->remove($task);
                $this->em->flush();
                $this->addFlash('success', 'registro.eliminado.correctamente' );
            }else{
                $this->addFlash('danger', 'error.sin.permiso' );
            }


            return $this->redirectToRoute('list_tasks');
        }
        catch (Exception $ex) {
            $this->addFlash('danger', 'error.eliminar.registro' );
            echo 'Excepción capturada: ',  $ex->getMessage(), "\n";
        }
    }

    public function editTaskAction(Request $request, int $idTask){

      try{

       $task = $this->em->getRepository('AppBundle:Todo')->find($idTask);

       $form = $this->createForm(\AppBundle\Form\TodoType::class, $task);
       $form->handleRequest($request);
       $rep = $this->em->getRepository('AppBundle:Todo');

       $usuarioLogado = $this->getuser();

       if($task !== null){
           // if($task->getUser()->getId() == $usuarioLogado->getId() || in_array('ROLE_ADMIN',$usuarioLogado->getRoles())){
           if($task->getUser()->getId() == $usuarioLogado->getId() ){
               if($form->isSubmitted() && $form->isValid()){
                    $task = $form->getData();
                    $this->em->persist($task);
                    $this->em->flush();
                    $this->addFlash('success', 'registro.modificado.correctamente' );
               }        
           }else{
                $this->addFlash('danger', 'error.sin.permiso' );
           }
       }else{
            $this->addFlash('danger', 'error.sin.permiso' );
       }
      
       if ( in_array('ROLE_ADMIN',$usuarioLogado->getRoles()) ) {
          $tasks = $rep->findAll();
       }else{
           $tasks = $rep->findBy(array('user' => $usuarioLogado->getId()) );
       }

      }
      catch(Excepcition $ex){
       echo 'Excepción capturada: ',  $ex->getMessage(), "\n";
      }
      return $this->render('Todo/edit_task.html.twig', array('form' => $form->createView(), 'tasks'=>$tasks) );
     }

    public function setStateAjaxAction(Request $request, int $idTask){

        if($request->isXmlHttpRequest()){

            $rep = $this->em->getRepository('AppBundle:Todo');
            $task = $rep->find($idTask);            
            $usuarioLogado = $this->getuser();

            if($task->getUser()->getId() == $usuarioLogado->getId() ){


                if($task){
                    $state = $task->getEstado();

                    switch ($state) {
                        case $this->todo::STATUS_NOINICIADA:
                            $task->setEstado($this->todo::STATUS_INICIADA);
                            break;
                        case $this->todo::STATUS_INICIADA:
                            $task->setEstado($this->todo::STATUS_FINALIZADA);
                            break;
                        case $this->todo::STATUS_FINALIZADA:
                            $task->setEstado($this->todo::STATUS_NOINICIADA);
                            break;
                    }                
                    $this->em->persist($task);
                    $this->em->flush(); 

                    $result = array('error' => 0, 'msg' => 'modificacion.estado.ajax.correctamente','state' => $task->getEstado() );
                }else{
                    $result = array('error' => 1, 'msg' => 'error.modificar.estado.ajax' );
                } 

            }else{
                $result = array('error' => 1, 'msg' => 'No tiene permiso para editar esa tarea' );
            }

                return new JsonResponse($result);
        }
        else {
              $result = array('error' => 1, 'msg' => 'error.peticion.noajax' );
                return new JsonResponse($result);
        }
    }

    public function changeLocaleAction(Request $request) {

        $locale = $request->getLocale();
        $request->setLocale($locale);
        
        return $this->render('Todo/list_tasks.html.twig', array('tasks'=>$tasks ));     
    }   

}
