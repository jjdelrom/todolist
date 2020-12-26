<?php
// src/AppBundle/Command/CreateTaskCommand.php
namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Todo;

class CreateTaskCommand extends Command
{
    protected static $defaultName = 'create:task';
    public $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->addArgument(
                'nombre',
                InputArgument::REQUIRED,
                'Nombre de la tarea'
            )
            ->addArgument(
                'fecha_tope',
                InputArgument::REQUIRED,
                'Nombre de la tarea'
            )    
            ->addArgument(
                'estado',
                InputArgument::REQUIRED,
                'Nombre de la tarea'
            )                     
            ->setName('create:task')
            ->setDescription('Creates a new task.')
            ->setHelp('This command allows you to create a new task...');
    

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

            $output->writeln([
                '<info>',
                'Formato correcto:',
                '==================',
                ' php bin/console create:task "Nombre de la tarea" "dd/mm/yyyy hh:mm:ss" "INICIADA/FINALIZADA/SIN EMPEZAR" ',
                '</info>'
            ]);  

            $task = new Todo();
            $rep = $this->em->getRepository('AppBundle:Todo');

            $name = $input->getArgument('nombre');
            $existe = $rep->findByNombre($name);

            if($existe){
                $output->writeln('<error>La tarea con nombre '.$name.' ya existe.</error>');
            }else{

                $date = $input->getArgument('fecha_tope');
                $state = $input->getArgument('estado');
                $task->setNombre($name);
                $task->setFechaCreacion(new \DateTime());
                $task->setFechaTope(new \DateTime($date));
    
                $task->setEstado(strtoupper($state));
                $this->em->persist($task);
                $this->em->flush();            

                if($task->getId()){
                    $output->writeln('<question>Se ha creado la tarea correctame con id: '.$task->getId().'</question>');
                }else{
                    $output->writeln('<error>Se ha producido un error al crear la tarea</error>');                  
                }                
            }
    }

}
?>