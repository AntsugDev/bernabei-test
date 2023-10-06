<?php

namespace App\Form;

use App\Entity\Login;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',TextType::class,['required' => true,
                'attr'=>[
                    'class'=>"form-control" ,
                    'autocomplete'=>"username",
                    'autofocus'=> true
                ],'data' => $options['entity']->getEmail()])
            ->add('password',PasswordType::class,['required' => true,
                'attr'=>[
                    'class'=>"form-control" ,
                    'autocomplete'=>"current-password",
                    'readonly'=> true
                ],'empty_data' =>123456
            ])
            ->add('login',SubmitType::class,['label' => 'Login','attr' => ['class'=> 'btn btn-lg btn-primary']])
        ;
        $builder->setMethod('POST');

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('entity');
        $resolver->setDefaults([
            'data_class' => Login::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return "formLogin";
    }

}
