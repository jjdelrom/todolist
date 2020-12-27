<?php

namespace AppBundle\Services;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\Container;



class LoggerCustom extends Container
{
	// /**
	//  * Constructor
	//  */
 //    public function __construct() {
 //    }

 function log($tipo, $texto, $archivo){
    $texto .= ' // FECHA:'.date("Y-m-d H:i:s");
    $fs = new Filesystem();
    $fs->appendToFile($archivo, $tipo.' --> '.$texto.PHP_EOL);
    $fs->appendToFile($archivo, "------------------------------------------------------------------------------------------".PHP_EOL);
 }
 
}