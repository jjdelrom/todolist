<?php

namespace AppBundle\Services;

class Mailer
{

    /**
     * Constructor
     */
    public function __construct() {
    }

    public function sendEmailAction(\Swift_Mailer $mailer, $email, $idUsuario)   {

        try{
            if($email != null && $email != ''){
                    $message = (new \Swift_Message("Recuperacion Password"))
                            ->setFrom('jjdelrom2012@gmail.com')
                            ->setTo($email)
                            // ->setBody(
                            //     $this->renderView(
                            //         'Emails/email_list_closed.html.twig',
                            //         array('idUsuario' => '1')
                            //     ),
                            //     'text/html'
                            // );
                            ->setBody('El Usuario con ID: '.$idUsuario.' tiene todas sus tareas finalizadas.'  );                            
                        $res = $mailer->send($message);
                        if($res == 1){
                                $mensaje = 'ENVIADO' ;
                        }
                        else{
                            $mensaje = "NO ENVIADO";
                        }
            }

                return $mensaje;
        }
        catch (Exception $ex) {
            echo 'Excepción capturada: ',  $ex->getMessage(), "\n";
        }
    }
 
}