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

use AppBundle\Form\UserType;

/**
 * Lógica de todas las pantallas relacionadas con el apartado de usuarios.
 */
class UserController extends Controller
{



    public function registroAction(Request $request, ValidatorInterface $validator, UserPasswordEncoderInterface $passwordEncoder){
        try{

            $em = $this->getDoctrine()->getManager();


            $error = []; $last_username = ""; $mensaje = "";

            $nombre_usuario = $request->request->get('nombre_usuario');
            $pass           = $request->request->get('pass');
            $nombre         = $request->request->get('nombre');
            $apellidos      = $request->request->get('apellidos');

            if($nombre_usuario == "" || $pass == "" || $nombre == "" || $apellidos == ""){
                $mensaje = "FALTA ALGUN CAMPO";
            }else{

                $rep = $em->getRepository('AppBundle:User');
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

                    $em->persist($usuario);
                    $em->flush();

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





}
