<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username',TextType::class, array('required' => true, 'label' => 'nombre.usuario'))
        ->add('password',PasswordType::class, array('required' => true, 'label' => 'password'))
        ->add('nombre',TextType::class,array('required' => true, 'label' => 'nombre'))
        ->add('apellidos',TextType::class,array('required' => true, 'label' => 'apellidos'))
        ->add('email',EmailType::class, array('required' => true, 'label' => 'email'))
        ->add('reset',ResetType::class, array('attr' => array( 'class' => 'btn btn-success'  ), 'label' => 'reset'))
        ->add('aceptar',SubmitType::class, array('attr' => array( 'class' => 'btn btn-primary'  ), 'label' => 'aceptar'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
