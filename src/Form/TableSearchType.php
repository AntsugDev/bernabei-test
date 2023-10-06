<?php

namespace App\Form;

use App\Entity\Table;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TableSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nrPage',NumberType::class,['required'=> true,
                'attr' => [
                    'class' => 'form-control',
                ],
                'data'=> $options['table']->getNrPage()
            ])
            ->add('size',ChoiceType::class,['required'=> true,
                'choices' => [
                    '5' => '5',
                    '10' => '10',
                    '20'=> '20',
                    'all' => 'all'
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'empty_data'=> $options['table']->getSize()
            ])
            ->add('order',ChoiceType::class,[
                'choices' => [
                    'DESC' => 'DESC',
                    'ACS' => 'ASC'
                ],
                'required'   => true,
                'empty_data' => $options['table']->getOrder(),
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('sortBy', TextType::class,[
                'required'=> true,
                'attr' => [
                    'class' => 'form-control',
                ],
                'data'=> $options['table']->getSortBy()
            ])
            ->add('title',TextType::class,[
                'required'=> false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'data'=> $options['table']->getTitle()
            ])
            ->add('description',TextType::class,[
                'required'=> false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'data'=> $options['table']->getDescription()
            ])
            ->add('cerca',SubmitType::class,[
                'attr' => [
                    'class' => 'btn btn-success'
                ],
            ])
        ;
        $builder->setMethod('POST');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('table');
        $resolver->setDefaults([
            'data_class' => Table::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return "searchTable";
    }

}
