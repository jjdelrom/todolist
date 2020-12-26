<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TodoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, array('required' => true, 'label' => 'nombre'))
            // ->add('fechaCreacion', DateTimeType::class, array('required' => true, 'label' => 'Fecha creacion'))
            // ->add('fechaTope', DateTimeType::class, array('required' => true, 'label' => 'Fecha finalización'))
            // ->add('fechaTope', DateTimeType::class, array('data' => new \DateTime() ))  ,'attr' => ['placeholder' => 'xxxx ']
            ->add('fechaTope', DateTimeType::class, array(
                'label' => 'fecha.tope.label',
                'required' => true,
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'date_format' => 'dd/MM/yyyy',
                'placeholder' => 'dd/MM/yyyy'
                // 'data' => new \DateTime()                
            ))
            // ->add('estado',TextType::class, array('required' => true, 'label' => 'estado'))
            ->add('estado',ChoiceType::class, array(
                'required' => true, 
                'label' => 'estado',
                'choices'  => [
                    'SIN EMPEZAR' => 'SIN EMPEZAR',
                    'INICIADA' => 'INICIADA',
                    'FINALIZADA' => 'FINALIZADA',
                ],)
            )
            ->add('reset',ResetType::class, array('label' => 'reset', 'attr' => array( 'class' => 'btn btn-success'  )))
            ->add('aceptar',SubmitType::class, array('label' => 'aceptar', 'attr' => array( 'class' => 'btn btn-primary'  )));       
             
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Todo'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_todo';
    }


}
