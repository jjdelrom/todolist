<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Todo;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Services\LoggerCustom;
use AppBundle\Services\Mailer;

class TodoController extends Controller
{

    private $todo;
    private $em;
    private $mailer;

    const NEW_TASK        = 'NUEVA    TAREA';
    const EDIT_TASK       = 'EDITAR   TAREA';
    const REMOVE_TASK     = 'ELIMINAR TAREA';
    const EDIT_TASK_STATE = 'ESTADO   TAREA';

    public function __construct(EntityManagerInterface $em, LoggerCustom $logger, Mailer $mailer) {
        $this->em = $em;
        $this->todo = new Todo();
        $this->logger = new LoggerCustom();
        $this->mailer = new Mailer();
    }         

    public function listTaskAction(Request $request, \Swift_Mailer $mailer)
    {
        // echo $this->mailer->sendEmailAction($mailer, 'cheringan@hotmail.com');

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
            $usuarioLogado = $this->getuser();
            $archivo = $this->container->getParameter('kernel.root_dir').'\log\log_task.txt';
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

                $texto = 'ID USUARIO: '.$usuarioLogado->getId().' // ID TAREA: '.$task->getId();
                $this->logger->log(self::NEW_TASK, $texto, $archivo);                  
                
                $this->addFlash('success', 'registro.creado.correctamente' );
            }
        }catch (Exception $ex) {
                $this->addFlash('danger', 'error.registro' );
                echo 'Excepción capturada: ',  $ex->getMessage(), "\n";
        }

        
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
            $archivo = $this->container->getParameter('kernel.root_dir').'\log\log_task.txt';    

            $task = $this->em->getRepository('AppBundle:Todo')->find($idTask);
            if(!$task){
                throw $this->createNotFoundException('La tarea con id: '.$idTask.' no existe.');
            }

            if($task->getUser()->getId() == $usuarioLogado->getId() ){
                $idt = $task->getId();
                $this->em->remove($task);
                $this->em->flush();

                $texto = 'ID USUARIO: '.$usuarioLogado->getId().' // ID TAREA: '.$idt;
                $this->logger->log(self::REMOVE_TASK, $texto, $archivo); 

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

    public function editTaskAction(Request $request, int $idTask, \Swift_Mailer $mailer){

      try{

       $task = $this->em->getRepository('AppBundle:Todo')->find($idTask);

       $form = $this->createForm(\AppBundle\Form\TodoType::class, $task);
       $form->handleRequest($request);
       $rep = $this->em->getRepository('AppBundle:Todo');

       $usuarioLogado = $this->getuser();
       $archivo = $this->container->getParameter('kernel.root_dir').'\log\log_task.txt'; 

       if($task !== null){
           // if($task->getUser()->getId() == $usuarioLogado->getId() || in_array('ROLE_ADMIN',$usuarioLogado->getRoles())){
           if($task->getUser()->getId() == $usuarioLogado->getId() ){
               if($form->isSubmitted() && $form->isValid()){
                    $task = $form->getData();
                    $this->em->persist($task);
                    $this->em->flush();

                    $texto = 'ID USUARIO: '.$usuarioLogado->getId().' // ID TAREA: '.$task->getId();
                    $this->logger->log(self::EDIT_TASK, $texto, $archivo); 

                    $this->addFlash('success', 'registro.modificado.correctamente' );

                    // ENVIO DE EMAIL AL ADMIN CUANDO SE COMPLETAN LAS TAREAS
                    $taskNoFinish = $rep->getTaskNoFinish($task->getUser()->getId());
                    if(count($taskNoFinish) > 0){
                        $rep2 = $this->em->getRepository('AppBundle:User');
                        $admin = $rep2->findBy(array('username' => 'admin') );
                        $envio =  $this->mailer->sendEmailAction($mailer, $admin[0]->getEmail(), $task->getUser()->getId());                        
                    }

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

    public function setStateAjaxAction(Request $request, int $idTask, \Swift_Mailer $mailer){

        if($request->isXmlHttpRequest()){

            $rep = $this->em->getRepository('AppBundle:Todo');
            $task = $rep->find($idTask);            
            $usuarioLogado = $this->getuser();
            $archivo = $this->container->getParameter('kernel.root_dir').'\log\log_task.txt'; 

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

                    $texto = 'ID USUARIO: '.$usuarioLogado->getId().' // ID TAREA: '.$task->getId();
                    $this->logger->log(self::EDIT_TASK_STATE, $texto, $archivo); 

                    // ENVIO DE EMAIL AL ADMIN CUANDO SE COMPLETAN LAS TAREAS
                    // $taskNoFinish = $rep->getTaskNoFinish($task->getUser()->getId());
                    // if(count($taskNoFinish) > 0){
                    //     $rep = $this->em->getRepository('AppBundle:User');
                    //     $admin = $rep->findBy(array('username' => 'admin') );
                    //     $envio =  $this->mailer->sendEmailAction($mailer, $admin[0]->getEmail(), $task->getUser()->getId());
                    // }

                    $finalizadas = $rep->find($idTask);

                    $result = array('error' => 0, 'msg' => 'modificacion.estado.ajax.correctamente','state' => $task->getEstado());
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

    // public function changeLocaleAction(Request $request) {

    //     $locale = $request->getLocale();
    //     $request->setLocale($locale);
        
    //     return $this->render('Todo/list_tasks.html.twig', array('tasks'=>$tasks ));     
    // }   

}
