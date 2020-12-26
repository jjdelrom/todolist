<?php

namespace AppBundle\Service;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/**
 * Comprueba y codifica las contraseñas de los usuarios.
 */
class CustomMd5PasswordEncoder implements PasswordEncoderInterface
{
	/**
	 * Constructor
	 */
    public function __construct() {
    }

    /**
     * Codifica la contraseña.
     * @param  string $raw  Es la contraseña que va a ser codificada.
     * @param  string $salt Salt
     * @return string      La contraseña codificada.
     */
    public function encodePassword($raw, $salt) {
        return md5($raw);
    }

    /**
     * Comprueba si una contraseña es válida. Compara una contraseña sin codificar con una contraseña codificada.
     * @param  string  $encoded Una contraseña codificada.
     * @param  string  $raw     Una contraseña sin codificar.
     * @param  string  $salt    Salt
     * @return boolean    True si la contraseña es válida, false en caso contrario.
     */
    public function isPasswordValid($encoded, $raw, $salt) {
        return md5($raw) == $encoded;
    }
}