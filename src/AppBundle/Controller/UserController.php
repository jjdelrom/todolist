<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Form\UserType;

/**
 * Lógica de todas las pantallas relacionadas con el apartado de usuarios.
 */
class UserController extends Controller
{

    private $em;
    private $user;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
        $this->user = new User();
    } 

    public function registroAction(Request $request, ValidatorInterface $validator, UserPasswordEncoderInterface $passwordEncoder){
        try{

            // $em = $this->getDoctrine()->getManager();


            $error = []; $last_username = ""; $mensaje = "";

            $nombre_usuario = $request->request->get('nombre_usuario');
            $pass           = $request->request->get('pass');
            $nombre         = $request->request->get('nombre');
            $apellidos      = $request->request->get('apellidos');

            if($nombre_usuario == "" || $pass == "" || $nombre == "" || $apellidos == ""){
                $mensaje = "FALTA ALGUN CAMPO";
            }else{

                $rep = $this->em->getRepository('AppBundle:User');
                $usuarioExiste = $rep->findOneBy(array('username' => $nombre_usuario ));

                if(isset($usuarioExiste)){
                    $mensaje = 'USUARIO EXISTE';
                }else{

                    $usuario = new User();
                    $errors = $validator->validate($usuario);
                    $usuario->setFechaAlta(new \DateTime());

                    $usuario->setRoles("ROLE_USER");
                    $usuario->setUsername($nombre_usuario);
                    $usuario->setNombre($nombre);
                    $usuario->setApellidos($apellidos);
                    $password = $passwordEncoder->encodePassword($usuario, $pass);
                    $usuario->setPassword($password);

                    $this->em->persist($usuario);
                    $this->em->flush();

                    if($usuario->getId() > 0) { $mensaje = 'USUARIO CREADO'; }
                    else{ $mensaje = 'USUARIO NO CREADO'; }

                }

             }
            return $this->render('login.html.twig',array('error' => $error, 'last_username' => $last_username, 'mensaje' => $mensaje ));

        }
    catch (Exception $ex) {
        echo 'Excepción capturada: ',  $ex->getMessage(), "\n";
    }
        return $this->render('login.html.twig',array('error' => $error, 'last_username' => $last_username, 'mensaje' => "" ));
    }

    public function createUserAction(Request $request, ValidatorInterface $validator, UserPasswordEncoderInterface $passwordEncoder){
        try{

            $usuarioLogado = $this->getuser();
            if(in_array('ROLE_ADMIN',$usuarioLogado->getRoles())){
                $usuario = new User();
                $errors = $validator->validate($usuario);
                $usuario->setFechaAlta(new \DateTime());
                $form = $this->createForm(UserType::class, $usuario);
                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()){

                    $roles = $request->request->get('roles');
                    $usuario->setRoles($roles);
                    $password = $passwordEncoder->encodePassword($usuario, $usuario->getPassword());
                    $usuario->setPassword($password);

                    $usuarioExiste = $this->em->getRepository('AppBundle:User')->findByUsername($usuario->getusername());
                    if(isset($usuarioExiste[0])){
                          $usuario = $this->em->getRepository('AppBundle:User')->find($usuarioExiste[0]->getId());
                    }

                    $this->em->persist($usuario);
                    $this->em->flush();

                    $this->addFlash('success', 'registro.creado.correctamente' );
                    $usuario = new User();
                    $form = $this->createForm(UserType::class, $usuario);
                return $this->render('Usuario/create_user.html.twig', array('form' => $form->createView() ));
                }
            }else{
                $this->addFlash('danger', 'error.sin.permiso' );
            }
        }
    catch (Exception $ex) {
        echo 'Excepción capturada: ',  $ex->getMessage(), "\n";
    }

        return $this->render('Usuario/create_user.html.twig', array('form' => $form->createView() ));
    }

    public function showUsersAction(Request $request){

        try{
            $rep = $this->em->getRepository('AppBundle:User');
            $users = $rep->findAll();
        } catch (Exception $ex) {
            echo 'Excepción capturada: ',  $ex->getMessage(), "\n";
        }

        return $this->render('Usuario/show_users.html.twig', array('users'=>$users ));
    }

 public function editUserAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator, $idUser){

  try{
    $usuarioLogado = $this->getuser();
    // if($usuarioLogado->getRoles()[0] != 'ROLE_ADMIN' && $usuarioLogado->getId() != $idUsuario){
    //     $this->addFlash('danger', 'error.sin.permiso' );
    //     return $this->render('Inicio/inicio.html.twig');
    // }

   $usuario = $this->em->getRepository('AppBundle:User')->find($idUser);
   $rolOriginal = $usuario->getRoles();
   $form = $this->createForm(UserType::class, $usuario);
   $form->handleRequest($request);

   if($form->isSubmitted() && $form->isValid()){
        $roles = $request->request->get('roles');
        if(!$roles){
            $usuario->setRoles($rolOriginal[0]);
        }else{
            $usuario->setRoles($roles);
        }

        $usuario = $form->getData();
        $password = $passwordEncoder->encodePassword($usuario, $usuario->getPassword());
        $usuario->setPassword($password);

        $this->em->persist($usuario);
        $this->em->flush();
        $this->addFlash('success', 'registro.modificado.correctamente' );


        if($usuarioLogado->getRoles()[0] == 'ROLE_ADMIN'){
            return $this->redirectToRoute('show_users');
        }
        else{
            return $this->redirectToRoute('edit_user' , array('idUser' => $usuarioLogado->getId()));
        }
   }

  }
  catch(Excepcition $ex){
   echo 'Excepción capturada: ',  $ex->getMessage(), "\n";
  }
  return $this->render('Usuario/edit_user.html.twig', array('form' => $form->createView(),'usuario'=> $usuario));
 }

}
